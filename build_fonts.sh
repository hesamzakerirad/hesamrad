#!/usr/bin/env bash
#
# Regenerates the .woff2 webfonts in source/_assets/fonts/ from the .ttf
# sources kept beside them. Run this after updating a source font.
#
# Requires fonttools and brotli:
#   python3 -m venv .venv && .venv/bin/pip install fonttools brotli
#   PYTHON=.venv/bin/python ./build_fonts.sh
#
# Note on reproducibility: fontTools' varLib.instancer is not byte-stable —
# repeated runs on identical input emit slightly different files. So this
# script compares the *glyph coverage* of what it just built against what is
# already committed and keeps the committed file when the two match. Rerunning
# it therefore leaves a clean working tree unless a source font really changed.

set -euo pipefail
shopt -s nullglob

PYTHON="${PYTHON:-python3}"
cd "$(dirname "$(readlink -f "$0" 2>/dev/null || echo "$0")")"

if ! "$PYTHON" -c 'import fontTools, brotli' 2>/dev/null; then
    echo "fonttools and brotli are required. See the header of this script." >&2
    exit 1
fi

work="$(mktemp -d 2>/dev/null || mktemp -d -t fonts)"
trap 'rm -rf "$work"' EXIT

# Google's latin + latin-ext + vietnamese ranges, plus the combining diacritics
# block. Text copied out of macOS arrives in NFD form (e + U+0301 rather than a
# precomposed e-acute), so dropping the combining marks would break accents that
# render fine elsewhere.
RANGES='U+0000-00FF,U+0131,U+0152-0153,U+02BB-02BC,U+02C6,U+02DA,U+02DC,U+0300-036F,U+0100-02BA,U+02BD-02C5,U+02C7-02CC,U+02CE-02D7,U+02DD-02FF,U+1AB0-1AFF,U+1DC0-1DFF,U+1D00-1DBF,U+1E00-1E9F,U+1EA0-1EF9,U+1EF2-1EFF,U+2000-206F,U+2074,U+20AC,U+2122,U+2191,U+2193,U+2212,U+2215,U+FEFF,U+FFFD,U+2020,U+20A0-20AB,U+20AD-20C0,U+2113,U+2C60-2C7F,U+A720-A7FF'

# A monospace font also renders terminal output and ASCII diagrams, so it keeps
# box drawing, arrows and math operators.
MONO_EXTRA='U+2190-21FF,U+2200-22FF,U+2500-257F'

# Prints the sorted codepoint list of a font, so two builds can be compared
# without depending on byte equality.
coverage() {
    "$PYTHON" - "$1" <<'PY'
import sys
from fontTools.ttLib import TTFont
codepoints = set()
for table in TTFont(sys.argv[1])['cmap'].tables:
    codepoints.update(table.cmap.keys())
print(','.join(map(str, sorted(codepoints))))
PY
}

# Replaces $2 with $1 only when their glyph coverage differs.
install_if_changed() {
    local built="$1" output="$2"

    if [[ -f "$output" ]] && [[ "$(coverage "$built")" == "$(coverage "$output")" ]]; then
        printf '%-40s unchanged\n' "$(basename "$output")"
        return
    fi

    local before after
    before=$(wc -c <"$output" 2>/dev/null || echo 0)
    mv "$built" "$output"
    after=$(wc -c <"$output")
    printf '%-40s %6dK -> %5dK\n' "$(basename "$output")" $((before / 1024)) $((after / 1024))
}

for font in source/_assets/fonts/Noto-Sans/*.ttf; do
    # Noto Sans ships a wdth axis whose default already equals its maximum and
    # which no stylesheet varies; pinning it roughly halves the file.
    "$PYTHON" -m fontTools.varLib.instancer "$font" wdth=100 -o "$work/pinned.ttf" >/dev/null 2>&1
    "$PYTHON" -m fontTools.subset "$work/pinned.ttf" \
        --output-file="$work/out.woff2" --flavor=woff2 --unicodes="$RANGES" 2>/dev/null
    install_if_changed "$work/out.woff2" "${font%.ttf}.woff2"
done

for font in source/_assets/fonts/JetBrains-Mono/*.ttf; do
    "$PYTHON" -m fontTools.subset "$font" \
        --output-file="$work/out.woff2" --flavor=woff2 --unicodes="${RANGES},${MONO_EXTRA}" 2>/dev/null
    install_if_changed "$work/out.woff2" "${font%.ttf}.woff2"
done
