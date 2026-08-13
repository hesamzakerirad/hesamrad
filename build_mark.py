#!/usr/bin/env python3
"""
Draws the site mark: source/_assets/images/logo.svg and source/favicon.ico.

    .venv/bin/python build_mark.py

The mark is a four-fold rosette: four petals on the diagonals, four dots on the
axes, and a ring of small lobes around a disc at the centre.

One set of numbers makes both files. The SVG and the icon therefore cannot
disagree about the shape. All geometry is in a 512 unit square with the centre
at (256, 256). The SVG keeps those units. The icon scales them.

The icon has two levels of detail. The full mark needs approximately 24 pixels.
Below that width three things fail. The 16 lobes and the ring are each less
than one pixel and become a smudge. The four dots become single specks that
read as noise. The petals become thin strokes. `detailed=False` therefore
draws one disc in place of the rosette, removes the dots, and makes the petals
wider. The result is a four-petal mark that stays legible at 16 pixels.

Each icon size renders at 1024 pixels and then scales down with a Lanczos
filter. The mark has curves at all angles, therefore it needs the smooth
filter. The previous mark in this file was pixel art, which needed the opposite
treatment.
"""

import math
import struct
from io import BytesIO

from PIL import Image, ImageDraw

SVG_OUT = "source/_assets/images/logo.svg"
ICO_OUT = "source/favicon.ico"

# The two accent values from source/_assets/css/tokens.css. The SVG selects
# between them with a media query. The icon cannot, therefore it uses the light
# value, which stays legible on a light tab strip and on a dark one.
ACCENT_LIGHT = "#0066cc"
ACCENT_DARK = "#2997ff"

UNITS = 512
CENTRE = UNITS / 2

# Four petals on the diagonals. `dist` is the distance from the centre of the
# mark to the centre of the petal. The tip therefore reaches dist + ry, which
# is 348 of a possible 362 to the corner.
PETAL = {"dist": 203, "rx": 51, "ry": 145}

# Four dots on the axes, between the petals.
DOT = {"dist": 183, "r": 33}

# The rosette. The lobes reach ROSETTE_LOBE["dist"] + ry from the centre, which
# stays inside the tips of the petals at 58.
ROSETTE_DISC = 23
ROSETTE_RING = {"r": 31, "width": 3.5}
ROSETTE_LOBE = {"count": 16, "dist": 41, "rx": 7, "ry": 10}

# The small icon. The disc replaces the rosette and covers the same area as the
# ring, therefore the mark keeps its weight at the centre. The petals get wider
# to hold their shape at 16 pixels.
ROSETTE_SIMPLE = 30
SMALL_PETAL_SCALE = 1.25


def ellipse_points(cx, cy, rx, ry, angle_deg, steps=240):
    """An ellipse as a polygon, rotated `angle_deg` about the centre of the mark.

    PIL cannot draw a rotated ellipse. A polygon of this many segments is
    smooth at every size this file writes.
    """
    angle = math.radians(angle_deg)
    cos_a, sin_a = math.cos(angle), math.sin(angle)
    points = []

    for step in range(steps):
        theta = 2 * math.pi * step / steps
        # The point on the unrotated ellipse, relative to the centre of the mark.
        x = cx - CENTRE + rx * math.cos(theta)
        y = cy - CENTRE + ry * math.sin(theta)
        points.append((CENTRE + x * cos_a - y * sin_a, CENTRE + x * sin_a + y * cos_a))

    return points


def svg(colour_light=ACCENT_LIGHT, colour_dark=ACCENT_DARK):
    """The mark as SVG text."""
    parts = [
        f'<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 {UNITS} {UNITS}"',
        f'     width="{UNITS}" height="{UNITS}" role="img" aria-label="Hesam Rad">',
        "    <title>Hesam Rad</title>",
        "    <style>",
        f"        .mark {{ fill: {colour_light}; stroke: {colour_light}; }}",
        "        @media (prefers-color-scheme: dark) {",
        f"            .mark {{ fill: {colour_dark}; stroke: {colour_dark}; }}",
        "        }",
        "    </style>",
        '    <g class="mark">',
        "        <!-- Four petals, on the diagonals. -->",
    ]

    for angle in (45, 135, 225, 315):
        parts.append(
            f'        <ellipse cx="{CENTRE:g}" cy="{CENTRE - PETAL["dist"]:g}"'
            f' rx="{PETAL["rx"]:g}" ry="{PETAL["ry"]:g}"'
            f' transform="rotate({angle} {CENTRE:g} {CENTRE:g})"/>'
        )

    parts.append("        <!-- Four dots, on the axes. -->")
    for angle in (0, 90, 180, 270):
        parts.append(
            f'        <circle cx="{CENTRE:g}" cy="{CENTRE - DOT["dist"]:g}"'
            f' r="{DOT["r"]:g}"'
            f' transform="rotate({angle} {CENTRE:g} {CENTRE:g})"/>'
        )

    parts.append("        <!-- The rosette: lobes, then a ring, then a disc. -->")
    for index in range(ROSETTE_LOBE["count"]):
        angle = 360 * index / ROSETTE_LOBE["count"]
        parts.append(
            f'        <ellipse cx="{CENTRE:g}" cy="{CENTRE - ROSETTE_LOBE["dist"]:g}"'
            f' rx="{ROSETTE_LOBE["rx"]:g}" ry="{ROSETTE_LOBE["ry"]:g}"'
            f' transform="rotate({angle:g} {CENTRE:g} {CENTRE:g})"/>'
        )

    parts += [
        f'        <circle cx="{CENTRE:g}" cy="{CENTRE:g}" r="{ROSETTE_RING["r"]:g}"'
        f' fill="none" stroke-width="{ROSETTE_RING["width"]:g}"/>',
        f'        <circle cx="{CENTRE:g}" cy="{CENTRE:g}" r="{ROSETTE_DISC:g}"/>',
        "    </g>",
        "</svg>",
        "",
    ]

    return "\n".join(parts)


def raster(size, detailed=True, render=1024):
    """The mark as an RGBA image of `size` pixels, on a transparent ground."""
    scale = render / UNITS
    fill = (0, 102, 204, 255)

    image = Image.new("RGBA", (render, render), (0, 0, 0, 0))
    draw = ImageDraw.Draw(image)
    at = lambda value: value * scale

    petal_rx = PETAL["rx"] * (1 if detailed else SMALL_PETAL_SCALE)
    for angle in (45, 135, 225, 315):
        draw.polygon(
            [(at(x), at(y)) for x, y in ellipse_points(
                CENTRE, CENTRE - PETAL["dist"], petal_rx, PETAL["ry"], angle)],
            fill=fill,
        )

    # The dots are one pixel each below 24 pixels wide, therefore the small
    # icon leaves them out.
    if detailed:
        for angle in (0, 90, 180, 270):
            draw.polygon(
                [(at(x), at(y)) for x, y in ellipse_points(
                    CENTRE, CENTRE - DOT["dist"], DOT["r"], DOT["r"], angle)],
                fill=fill,
            )

    if detailed:
        for index in range(ROSETTE_LOBE["count"]):
            angle = 360 * index / ROSETTE_LOBE["count"]
            draw.polygon(
                [(at(x), at(y)) for x, y in ellipse_points(
                    CENTRE, CENTRE - ROSETTE_LOBE["dist"],
                    ROSETTE_LOBE["rx"], ROSETTE_LOBE["ry"], angle)],
                fill=fill,
            )

        radius, width = ROSETTE_RING["r"], ROSETTE_RING["width"] / 2
        draw.ellipse(
            [at(CENTRE - radius - width), at(CENTRE - radius - width),
             at(CENTRE + radius + width), at(CENTRE + radius + width)],
            outline=fill, width=max(1, round(at(ROSETTE_RING["width"]))),
        )
        disc = ROSETTE_DISC
    else:
        disc = ROSETTE_SIMPLE

    draw.ellipse(
        [at(CENTRE - disc), at(CENTRE - disc), at(CENTRE + disc), at(CENTRE + disc)],
        fill=fill,
    )

    return image.resize((size, size), Image.Resampling.LANCZOS)


def png_bytes(image):
    buffer = BytesIO()
    image.save(buffer, "PNG", optimize=True)
    return image.width, buffer.getvalue()


def ico(entries):
    """An ICO file: a header, one directory entry per size, then a PNG each."""
    header = struct.pack("<HHH", 0, 1, len(entries))
    offset = len(header) + 16 * len(entries)
    directory, payload = b"", b""

    for size, data in entries:
        # A dimension of 256 is written as 0. Nothing here is that large, but
        # the rule stays correct for a size added later.
        directory += struct.pack(
            "<BBBBHHII",
            size if size < 256 else 0,
            size if size < 256 else 0,
            0,  # palette entries: 0 for a PNG payload
            0,  # reserved
            1,  # colour planes
            32,  # bits per pixel
            len(data),
            offset,
        )
        payload += data
        offset += len(data)

    return header + directory + payload


def main():
    with open(SVG_OUT, "w", encoding="utf-8") as file:
        file.write(svg())
    print(f"wrote {SVG_OUT}")

    # 16 pixels gets the simple rosette. 32 and 48 get the full one.
    entries = [
        png_bytes(raster(16, detailed=False)),
        png_bytes(raster(32)),
        png_bytes(raster(48)),
    ]
    data = ico(entries)

    with open(ICO_OUT, "wb") as file:
        file.write(data)

    sizes = ", ".join(f"{size}x{size}" for size, _ in entries)
    print(f"wrote {ICO_OUT} ({sizes}, {len(data)} bytes)")


if __name__ == "__main__":
    main()
