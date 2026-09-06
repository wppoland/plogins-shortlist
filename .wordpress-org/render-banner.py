#!/usr/bin/env python3
"""Render wp.org banners with a padded safe zone.

Live CSS (wporg-plugins-2024):
  .plugin-banner img { width:100%; aspect-ratio:3.089; border-radius:... }

Rounded corners clip the PNG corners. Keep every glyph and the screenshot
card inside an inset so radius (and any future cover-crop) cannot cut them.
"""
from __future__ import annotations

from pathlib import Path

from PIL import Image, ImageDraw, ImageFilter, ImageFont

ROOT = Path(__file__).resolve().parent
W, H = 1544, 500
# ~20% vertical, ~7% horizontal. Content stays inside after corner rounding.
PAD_X, PAD_Y = 108, 100
NAVY = (11, 21, 51)
ACCENT = (194, 37, 92)
TEXT = (26, 37, 64)


def load_font(size: int, bold: bool = False) -> ImageFont.FreeTypeFont | ImageFont.ImageFont:
    names = (
        ("Arial Bold.ttf" if bold else "Arial.ttf", None),
        ("Helvetica.ttc", 1 if bold else 0),
        ("SFNS.ttf", None),
    )
    dirs = (
        Path("/System/Library/Fonts/Supplemental"),
        Path("/System/Library/Fonts"),
        Path("/Library/Fonts"),
    )
    for directory in dirs:
        for name, index in names:
            path = directory / name
            if not path.exists():
                continue
            try:
                if index is None:
                    return ImageFont.truetype(str(path), size)
                return ImageFont.truetype(str(path), size, index=index)
            except OSError:
                continue
    return ImageFont.load_default()


def rounded_mask(size: tuple[int, int], radius: int) -> Image.Image:
    mask = Image.new("L", size, 0)
    ImageDraw.Draw(mask).rounded_rectangle((0, 0, size[0] - 1, size[1] - 1), radius=radius, fill=255)
    return mask


def main() -> None:
    img = Image.new("RGB", (W, H), (255, 255, 255))
    draw = ImageDraw.Draw(img)
    for x in range(W):
        t = min(1.0, x / 980)
        draw.line(
            [(x, 0), (x, H)],
            fill=(
                int(255 - 8 * (1 - t)),
                int(246 + 9 * t),
                int(249 + 6 * t),
            ),
        )

    icon = Image.open(ROOT / "icon-256x256.png").convert("RGBA").resize((56, 56), Image.Resampling.LANCZOS)
    left_x = PAD_X
    title_font = load_font(48, True)
    sub_font = load_font(22, True)
    item_font = load_font(20, True)

    # Vertically center the left stack inside the safe box.
    stack_h = 56 + 18 + 28 + 24 + 3 * 40
    y = (H - stack_h) // 2
    img.paste(icon, (left_x, y), icon)
    draw.text((left_x + 68, y + 8), "Shortlist", font=title_font, fill=NAVY)
    y += 56 + 18
    draw.text((left_x, y), "Wishlist for WooCommerce", font=sub_font, fill=ACCENT)
    y += 28 + 24
    for label in (
        "Save without an account",
        "My Account wishlist tab",
        "No jQuery, no layout shift",
    ):
        draw.ellipse((left_x, y + 4, left_x + 26, y + 30), fill=ACCENT)
        draw.text((left_x + 38, y + 4), label, font=item_font, fill=TEXT)
        y += 40

    shot = Image.open(ROOT / "screenshot-1.png").convert("RGB")
    # Top product row only (landscape) so the card stays inside the inset.
    shot = shot.crop((48, 170, 1120, 760))
    card_h = H - 2 * PAD_Y
    card_w = int(card_h * shot.width / shot.height)
    shot = shot.resize((card_w, card_h), Image.Resampling.LANCZOS)
    mask = rounded_mask((card_w, card_h), 18)
    card_x = W - PAD_X - card_w
    card_y = PAD_Y

    shadow = Image.new("RGBA", (W, H), (0, 0, 0, 0))
    ImageDraw.Draw(shadow).rounded_rectangle(
        (card_x + 8, card_y + 12, card_x + card_w + 8, card_y + card_h + 12),
        radius=18,
        fill=(20, 40, 90, 48),
    )
    shadow = shadow.filter(ImageFilter.GaussianBlur(16))
    img = Image.alpha_composite(img.convert("RGBA"), shadow).convert("RGB")
    img.paste(shot, (card_x, card_y), mask)

    retina = ROOT / "banner-1544x500.png"
    standard = ROOT / "banner-772x250.png"
    img.save(retina, "PNG", optimize=True)
    img.resize((772, 250), Image.Resampling.LANCZOS).save(standard, "PNG", optimize=True)
    print(f"wrote {retina.name} {img.size} and {standard.name}")


if __name__ == "__main__":
    main()
