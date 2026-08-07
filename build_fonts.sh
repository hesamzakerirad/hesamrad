#!/usr/bin/env bash
#
# Regenerates the .woff2 webfonts in source/_assets/fonts/ from the .ttf
# sources kept beside them. Run this after updating a source font.
#
# Requires fonttools and brotli:
#   python3 -m venv .venv && .venv/bin/pip install fonttools brotli
#   PYTHON=.venv/bin/python ./build_fonts.sh

set -euo pipefail

PYTHON="${PYTHON:-python3}"
cd "$(dirname "$0")"

if ! "$PYTHON" -c 'import fontTools, brotli' 2>/dev/null; then
    echo "fonttools and brotli are required. See the header of this script." >&2
    exit 1
fi

# Google's latin + latin-ext + vietnamese ranges, plus the combining diacritics
# block. Text copied out of macOS arrives in NFD form (e + U+0301 rather than a
# precomposed e-acute), so dropping the combining marks would break accents that
# render fine elsewhere.
RANGES='U+0000-00FF,U+0131,U+0152-0153,U+02BB-02BC,U+02C6,U+02DA,U+02DC,U+0300-036F,U+0100-02BA,U+02BD-02C5,U+02C7-02CC,U+02CE-02D7,U+02DD-02FF,U+1AB0-1AFF,U+1DC0-1DFF,U+1D00-1DBF,U+1E00-1E9F,U+1EA0-1EF9,U+1EF2-1EFF,U+2000-206F,U+2074,U+20AC,U+2122,U+2191,U+2193,U+2212,U+2215,U+FEFF,U+FFFD,U+2020,U+20A0-20AB,U+20AD-20C0,U+2113,U+2C60-2C7F,U+A720-A7FF'

# A monospace font is also used for terminal output and ASCII diagrams, so it
# keeps box drawing, arrows, math operators and geometric shapes.
MONO_EXTRA='U+2190-21FF,U+2200-22FF,U+2500-257F,U+25A0-25FF,U+2600-26FF'

subset() {
    local input="$1" output="$2" unicodes="$3"
    shift 3
    "$PYTHON" -m fontTools.subset "$input" \
        --output-file="$output" \
        --flavor=woff2 \
        --unicodes="$unicodes" \
        "$@"
    printf '%-40s %7dK -> %5dK\n' "$(basename "$output")" \
        $(( $(wc -c <"$input") / 1024 )) $(( $(wc -c <"$output") / 1024 ))
}

for font in source/_assets/fonts/Noto-Sans/*.ttf; do
    pinned="$(mktemp -t noto).ttf"
    # Noto Sans ships a wdth axis whose default already equals its maximum and
    # which no stylesheet varies; pinning it roughly halves the file.
    "$PYTHON" -m fontTools.varLib.instancer "$font" wdth=100 -o "$pinned" >/dev/null
    subset "$pinned" "${font%.ttf}.woff2" "$RANGES"
    rm -f "$pinned"
done

for font in source/_assets/fonts/JetBrains-Mono/*.ttf; do
    subset "$font" "${font%.ttf}.woff2" "${RANGES},${MONO_EXTRA}"
done
