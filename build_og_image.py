#!/usr/bin/env python3
"""
Regenerates the social share card at source/_assets/images/og-default.png.

Run after changing the headline or the palette:

    .venv/bin/pip install Pillow
    .venv/bin/python build_og_image.py

Committed as a PNG rather than generated at build time because the site is
static and the deploy runs on GitHub Actions, which has no rasteriser — and
because this changes about once a year.

1200x630 is the size every platform crops from. Anything important stays well
inside the middle, since Twitter/X trims the sides on some layouts.
"""

from PIL import Image, ImageDraw, ImageFont

W, H = 1200, 630
BG = "#ffffff"
INK = "#1d1d1f"
DIM = "#6e6e73"
ACCENT = "#0066cc"

FONT = "source/_assets/fonts/Inter/Inter-Variable.ttf"
OUT = "source/_assets/images/og-default.png"

# Line breaks are authored rather than wrapped, so the card can be balanced the
# same way `text-wrap: balance` balances the headline on the page.
HEADLINE = ["Your business has a", "back office. Somebody", "has to build it."]
# Three lines do not clear the footer at 92px, which is what two lines used.
HEADLINE_SIZE = 80
FOOTER_LEFT = "Hesam Rad"
FOOTER_RIGHT = "hesamrad.com"


def inter(size, weight=400, optical=None):
    """Inter at a given size and weight, with the optical axis tracking size.

    Inter's `opsz` axis is what tightens the large sizes the way a display cut
    would; leaving it at the 14pt default makes a 92px headline look loose.
    """
    font = ImageFont.truetype(FONT, size)
    font.set_variation_by_axes([optical if optical else min(32, max(14, size / 3)), weight])
    return font


def main():
    image = Image.new("RGB", (W, H), BG)
    draw = ImageDraw.Draw(image)

    margin = 88

    # The accent bar. The one piece of colour, and the only thing marking this
    # as ours rather than any other white card in a feed.
    draw.rounded_rectangle([margin, margin, margin + 64, margin + 8], radius=4, fill=ACCENT)

    headline = inter(HEADLINE_SIZE, weight=600)
    y = margin + 66
    available = W - margin * 2

    footer = inter(30, weight=500)
    baseline = H - margin - 30

    for line in HEADLINE:
        width = draw.textlength(line, font=headline)
        if width > available:
            raise SystemExit(
                f"headline line {line!r} is {width:.0f}px wide, {available}px available — "
                "shorten the line or lower HEADLINE_SIZE"
            )
        draw.text((margin, y), line, font=headline, fill=INK)
        # 1.08 line height, matching --leading-display on the site.
        y += int(HEADLINE_SIZE * 1.08)

    # Silently overlapping the footer is the failure that actually happened when
    # the headline went from two lines to three.
    if y > baseline:
        raise SystemExit(
            f"headline runs to {y}px and the footer sits at {baseline}px — "
            "drop a line or lower HEADLINE_SIZE"
        )

    draw.text((margin, baseline), FOOTER_LEFT, font=footer, fill=INK)

    right = draw.textlength(FOOTER_RIGHT, font=footer)
    draw.text((W - margin - right, baseline), FOOTER_RIGHT, font=footer, fill=DIM)

    image.save(OUT, "PNG", optimize=True)
    print(f"wrote {OUT} ({W}x{H})")


if __name__ == "__main__":
    main()
