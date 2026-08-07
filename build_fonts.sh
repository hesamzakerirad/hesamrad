#!/usr/bin/env bash
#
# Regenerates the .woff2 webfonts in source/_assets/fonts/ from the .ttf
# sources kept beside them. Run this after updating a source font.
#
# Requires fonttools and brotli:
#   python3 -m venv .venv && .venv/bin/pip install fonttools brotli
#   PYTHON=.venv/bin/python ./build_fonts.sh
#
# Note on reproducibility: fontTools' varLib.instancer is not byte-stable, so
# rebuilding an unchanged font would produce a different file every run and
# dirty the working tree for no reason. Rather than diffing the output, this
# records a fingerprint of each source font plus the subset ranges in
# fonts.manifest and rebuilds only when that fingerprint changes.

set -euo pipefail

script_dir=$(cd -- "$(dirname -- "$0")" && pwd)
cd -- "$script_dir"

PYTHON="${PYTHON:-python3}"
FONT_ROOT='source/_assets/fonts'
MANIFEST="$FONT_ROOT/fonts.manifest"

if ! "$PYTHON" -c 'import fontTools, brotli' 2>/dev/null; then
    echo "fonttools and brotli are required. See the header of this script." >&2
    exit 1
fi

if [[ ! -d "$FONT_ROOT" ]]; then
    echo "No $FONT_ROOT directory — run this from a checkout of the site." >&2
    exit 1
fi

work="$(mktemp -d)"
trap 'rm -rf "$work"' EXIT

# Google's latin + latin-ext + vietnamese ranges, plus the combining diacritics
# block. Text copied out of macOS arrives in NFD form (e + U+0301 rather than a
# precomposed e-acute), so dropping the combining marks would break accents that
# render fine elsewhere.
RANGES='U+0000-00FF,U+0131,U+0152-0153,U+02BB-02BC,U+02C6,U+02DA,U+02DC,U+0300-036F,U+0100-02BA,U+02BD-02C5,U+02C7-02CC,U+02CE-02D7,U+02DD-02FF,U+1AB0-1AFF,U+1DC0-1DFF,U+1D00-1DBF,U+1E00-1E9F,U+1EA0-1EF9,U+1EF2-1EFF,U+2000-206F,U+2074,U+20AC,U+2122,U+2191,U+2193,U+2212,U+2215,U+FEFF,U+FFFD,U+2020,U+20A0-20AB,U+20AD-20C0,U+2113,U+2C60-2C7F,U+A720-A7FF'

# A monospace font also renders terminal output and ASCII diagrams, so it keeps
# box drawing, arrows and math operators.
MONO_EXTRA='U+2190-21FF,U+2200-22FF,U+2500-257F'

sha256() {
    if command -v sha256sum >/dev/null 2>&1; then
        sha256sum | cut -d' ' -f1
    else
        shasum -a 256 | cut -d' ' -f1
    fi
}

# Fingerprints the inputs: the source font and the exact ranges asked for.
fingerprint() {
    local source="$1" ranges="$2"
    printf '%s %s' "$(sha256 <"$source")" "$(printf '%s' "$ranges" | sha256)"
}

# The manifest is TAB-separated so a key may contain spaces or regex
# metacharacters — both of which silently broke a whitespace-split lookup.
recorded_fingerprint() {
    [[ -f "$MANIFEST" ]] || return 0
    awk -F'\t' -v key="$1" '$1 == key { print $2 }' "$MANIFEST"
}

record_fingerprint() {
    local key="$1" value="$2"

    if [[ -f "$MANIFEST" ]]; then
        awk -F'\t' -v key="$key" 'NF == 2 && $1 != key' "$MANIFEST" >"$work/manifest"
    else
        : >"$work/manifest"
    fi

    printf '%s\t%s\n' "$key" "$value" >>"$work/manifest"
    # Fixed collation so the committed order does not depend on the developer.
    LC_ALL=C sort "$work/manifest" >"$MANIFEST"
}

# The manifest records inputs, so it cannot notice a truncated or corrupted
# output. Check the woff2 signature and a plausible size before trusting one.
is_valid_woff2() {
    [[ -f "$1" ]] && [[ "$(head -c 4 "$1")" == 'wOF2' ]] && [[ "$(wc -c <"$1")" -gt 1024 ]]
}

# $1 source .ttf, $2 subset ranges, $3 "pin" to also pin the wdth axis
build_font() {
    local source="$1" ranges="$2" pin="${3:-}"
    local output="${source%.ttf}.woff2"
    local key="${output#"$FONT_ROOT"/}"
    local want
    want=$(fingerprint "$source" "$ranges")

    if is_valid_woff2 "$output" && [[ "$(recorded_fingerprint "$key")" == "$want" ]]; then
        printf '%-40s up to date\n' "$(basename "$output")"
        return
    fi

    local subset_input="$source"

    if [[ "$pin" == 'pin' ]]; then
        # Noto Sans ships a wdth axis whose default already equals its maximum
        # and which no stylesheet varies; pinning it roughly halves the file.
        if ! "$PYTHON" -m fontTools.varLib.instancer "$source" wdth=100 \
            -o "$work/pinned.ttf" >"$work/log" 2>&1; then
            cat "$work/log" >&2
            echo "Failed to pin the wdth axis of $source" >&2
            exit 1
        fi
        subset_input="$work/pinned.ttf"
    fi

    if ! "$PYTHON" -m fontTools.subset "$subset_input" \
        --output-file="$work/out.woff2" --flavor=woff2 --unicodes="$ranges" >"$work/log" 2>&1; then
        cat "$work/log" >&2
        echo "Failed to subset $source" >&2
        exit 1
    fi

    local before=0
    [[ -f "$output" ]] && before=$(wc -c <"$output")
    mv "$work/out.woff2" "$output"
    record_fingerprint "$key" "$want"
    printf '%-40s %6dK -> %5dK\n' "$(basename "$output")" $((before / 1024)) $(( $(wc -c <"$output") / 1024 ))
}

# Each family is counted separately: a single total would hide one directory
# going empty for as long as the other still had sources.
build_family() {
    local dir="$1" ranges="$2" pin="${3:-}" built=0 font

    for font in "$FONT_ROOT/$dir"/*.ttf; do
        [[ -e "$font" ]] || continue
        build_font "$font" "$ranges" "$pin"
        built=$((built + 1))
    done

    if [[ "$built" -eq 0 ]]; then
        echo "No .ttf sources found in $FONT_ROOT/$dir — nothing to regenerate." >&2
        exit 1
    fi
}

build_family Noto-Sans "$RANGES" pin
build_family JetBrains-Mono "${RANGES},${MONO_EXTRA}"
