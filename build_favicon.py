#!/usr/bin/env python3
"""
Draws source/favicon.ico from a pixel map.

    .venv/bin/python build_favicon.py

A favicon is pixel art whether or not anyone treats it as such: sixteen pixels
square is not enough room for anything that is not placed deliberately. What
was here before was a 200x200 PNG named .ico, which browsers accept but then
downscale to 16px themselves — every edge in it arrived as a smudge, and there
was no crisp small size in the file at all.

So the mark is drawn at 16 and scaled by whole numbers to 32 and 48. Integer
nearest-neighbour is the whole trick: at 2x and 3x every source pixel lands on
an exact block of output pixels and nothing is ever interpolated.

The .ico is assembled by hand rather than through PIL's ICO writer, which
resizes with a smooth filter and would blur the very thing this exists to keep
sharp. An ICO is a short header, one directory entry per size, and — since
Vista — a plain PNG per entry.
"""

import struct
from io import BytesIO

from PIL import Image

OUT = "source/favicon.ico"

# The site's one colour, and white. Blue reads against a light tab strip and a
# dark one; a mark in the heading grey would vanish into one of them.
TILE = (0, 102, 204, 255)
MARK = (255, 255, 255, 255)

# A prompt: a chevron and the block cursor after it. It is the most legible
# "software" glyph that survives being sixteen pixels wide, and unlike a
# monogram it does not read as somebody's initials for Human Resources.
PIXELS = [
    "................",
    "................",
    "................",
    "..##............",
    "...##...........",
    "....##..........",
    ".....##.........",
    ".....##.........",
    "....##..........",
    "...##...........",
    "..##............",
    "................",
    ".........#####..",
    ".........#####..",
    "................",
    "................",
]


def base():
    """The mark at 1:1 — sixteen pixels square."""
    size = len(PIXELS)
    assert all(len(row) == size for row in PIXELS), "the map must be square"

    image = Image.new("RGBA", (size, size), TILE)
    for y, row in enumerate(PIXELS):
        for x, cell in enumerate(row):
            if cell == "#":
                image.putpixel((x, y), MARK)
    return image


def png_bytes(image, scale):
    """The mark at a whole multiple of its own size, as PNG bytes."""
    size = image.width * scale
    buffer = BytesIO()
    # NEAREST, not the default: every other filter averages neighbouring
    # pixels, which is exactly what turns pixel art into a smudge.
    image.resize((size, size), Image.Resampling.NEAREST).save(buffer, "PNG", optimize=True)
    return size, buffer.getvalue()


def main():
    mark = base()
    entries = [png_bytes(mark, scale) for scale in (1, 2, 3)]  # 16, 32, 48

    # ICONDIR: reserved, type 1 (icon), image count.
    header = struct.pack("<HHH", 0, 1, len(entries))
    offset = len(header) + 16 * len(entries)

    directory, payload = b"", b""
    for size, data in entries:
        # A dimension of 256 is written as 0; nothing here is that large, but
        # encoding the rule rather than the current sizes keeps it correct.
        directory += struct.pack(
            "<BBBBHHII",
            size if size < 256 else 0,
            size if size < 256 else 0,
            0,  # palette entries — 0 for a PNG payload
            0,  # reserved
            1,  # colour planes
            32,  # bits per pixel
            len(data),
            offset,
        )
        payload += data
        offset += len(data)

    with open(OUT, "wb") as file:
        file.write(header + directory + payload)

    sizes = ", ".join(f"{size}x{size}" for size, _ in entries)
    print(f"wrote {OUT} ({sizes}, {len(header + directory + payload)} bytes)")


if __name__ == "__main__":
    main()
