#!/usr/bin/env python3
"""
Regenerates the payment QR code at source/_assets/images/usdt-trc20-qr.svg.

Run after changing the address in config.php:

    .venv/bin/pip install segno
    .venv/bin/python build_qr.py

The address is not written here. The script reads it out of config.php, which is
the same value /pay/ prints on the page. One source means the picture and the
text cannot disagree, which on a payment page is the difference between a client
paying me and a client paying nobody.

Committed as a file rather than generated at build time, for the reason given in
build_og_image.py: the deploy runs on GitHub Actions and installs no Python.

SVG and not PNG. A QR code is squares. It scales to any size with no blur and no
second file for a denser screen, and the whole thing is about two kilobytes.
"""

import hashlib
import re
import sys

import segno

CONFIG = 'config.php'
OUT = 'source/_assets/images/usdt-trc20-qr.svg'

# The site's ink and white, not pure black. #1d1d1f on #ffffff measures about
# 18:1, far above anything a camera needs, so the code stays readable while
# matching every other dark mark on the site.
INK = '#1d1d1f'
PLATE = '#ffffff'

# The quiet zone the spec asks for. Four empty modules on every side. A scanner
# uses it to find the edges of the code, and a code pressed against a border
# fails on cheaper cameras.
BORDER = 4

# Base58, as Bitcoin defined it and Tron reuses. No 0, O, I or l, because those
# are the characters a person copying by hand gets wrong.
B58 = '123456789ABCDEFGHJKLMNPQRSTUVWXYZabcdefghijkmnopqrstuvwxyz'

# The first byte of a decoded Tron mainnet address. A testnet or a mistyped
# address decodes to something else, and a QR code for a testnet address is a
# QR code that silently eats a payment.
TRON_MAINNET = 0x41


def read_address(path):
    """Pulls the address out of config.php."""
    with open(path, encoding='utf-8') as handle:
        source = handle.read()

    match = re.search(r"'address'\s*=>\s*'([^']+)'", source)

    if not match:
        sys.exit(f"No 'address' key found in {path}.")

    return match.group(1)


def check_address(address):
    """
    Refuses anything that is not a valid Tron mainnet address.

    This is the point of the script that earns its place. A typo in an address
    is not a typo a reader catches: the string is 34 random characters and looks
    equally plausible either way. Base58Check exists so a machine can catch it
    instead, and four bytes of checksum make a wrong address astronomically
    unlikely to pass.

    Anything wrong exits without writing, so a stale but correct QR code
    survives a bad edit rather than being overwritten by a wrong one.
    """
    if len(address) != 34:
        sys.exit(f'Address is {len(address)} characters, expected 34: {address}')

    if not address.startswith('T'):
        sys.exit(f'A Tron mainnet address starts with T: {address}')

    number = 0

    for character in address:
        if character not in B58:
            sys.exit(f'{character!r} is not a base58 character: {address}')

        number = number * 58 + B58.index(character)

    decoded = number.to_bytes(25, 'big')
    payload, checksum = decoded[:21], decoded[21:]

    if payload[0] != TRON_MAINNET:
        sys.exit(f'Not a Tron mainnet address (prefix {payload[0]:#x}): {address}')

    expected = hashlib.sha256(hashlib.sha256(payload).digest()).digest()[:4]

    if checksum != expected:
        sys.exit(f'Checksum fails. The address is mistyped: {address}')


def to_svg(matrix, size):
    """
    Draws the module grid as one path on a white plate.

    One module is one unit, and the viewBox carries the size, therefore CSS
    decides how big the code renders and no width is baked in.

    Every dark module joins a single path rather than becoming its own <rect>.
    The file is a third of the size and a browser paints it in one operation.
    `shape-rendering="crispEdges"` turns off antialiasing, which is what keeps
    the edges hard at any scale and a scanner's job easy.
    """
    moves = []

    for y, row in enumerate(matrix):
        for x, module in enumerate(row):
            if module:
                moves.append(f'M{x + BORDER} {y + BORDER}h1v1h-1z')

    return (
        f'<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 {size} {size}" '
        f'shape-rendering="crispEdges">'
        f'<rect width="{size}" height="{size}" fill="{PLATE}"/>'
        f'<path fill="{INK}" d="{"".join(moves)}"/>'
        f'</svg>\n'
    )


def main():
    address = read_address(CONFIG)
    check_address(address)

    # Byte mode, because a Tron address mixes upper and lower case and QR's
    # alphanumeric mode holds neither lower case nor a full alphabet.
    #
    # The content is the bare address and not a `tron:` URI. Every wallet and
    # every exchange withdrawal screen reads a bare address. URI support is
    # uneven, and a scanner that does not understand the scheme pastes the
    # scheme into the address field.
    #
    # Error correction H recovers from about 30% of the code being unreadable. A
    # screen gets photographed at an angle, under a reflection, by a phone with
    # a scratched lens. The cost is a slightly denser grid and nothing else.
    code = segno.make(address, error='h', mode='byte')

    matrix = code.matrix
    size = len(matrix) + BORDER * 2

    with open(OUT, 'w', encoding='utf-8') as handle:
        handle.write(to_svg(matrix, size))

    print(f'{OUT}: {address} at version {code.version}, {size}x{size} modules.')


if __name__ == '__main__':
    main()
