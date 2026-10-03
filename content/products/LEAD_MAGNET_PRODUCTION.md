# LEAD_MAGNET_PRODUCTION.md: 21-Day Tracker, Canva-Ready Build Spec

> Created Day 5 of the accelerated sprint. This is the production-ready layout spec: every page is detailed enough to build in Canva AI without further design decisions. Builds from LEAD_MAGNET_BRIEF.md (structure/conversion path) and PRODUCT_01 contents (the paid version).

## Global design system (applies to all 5 pages)

- **Canvas:** 8.5x11 in, portrait, 0.25in bleed-safe margins (also export A4 variant)
- **Palette:** Mint #B8DED4 (primary), Teal #4A9B8E (accent/dark), Warm Cream #FDF8F2 (background), Charcoal #3A3A3A (text), Coral #F2A48B (badges/highlights only)
- **Fonts:** Headers: Quicksand Bold (rounded, friendly). Body: Nunito Regular. Checkboxes/labels: Nunito Bold
- **Icons:** Canva elements: search "toothbrush flat icon," "sun minimal line," "moon minimal line" for a consistent line-weight family
- **Seal:** bottom-right every page: circular badge, "Reviewed by Jessie Tang, RDH" around a checkmark-tooth icon, Teal #4A9B8E
- **Footer:** "brushwithme.com" centered, 9pt, 60% opacity
- **Print rule:** no full-page color fills (ink-friendly); Cream background at 6% opacity only

---

## Page 1: Cover

- **Top third:** Sun + toothbrush line icons flanking the title
- **Title (centered, 44pt):** "The 21-Day Brushing Challenge"
- **Subtitle (18pt):** "Turn brushing into a habit your kid asks to do"
- **Brand mark:** "Brush With Me" wordmark above title, Teal
- **Bottom third:** illustration block: kid-at-bathroom-sink (Canva AI: "flat illustration, child brushing teeth, mint and teal palette, warm lighting")
- **Coral badge (top-right):** "FREE PRINTABLE"

## Page 2: How It Works (parent page)

- **Header:** "How the Challenge Works" (28pt)
- **Section 1, The Routine (with 3 mint circles, numbered):**
  1. "Brush 2x a day, 2 minutes each" (with small 2-2-2 icon)
  2. "Check off morning and night boxes together"
  3. "Celebrate the streaks: badges at Day 7, 14, and 21"
- **Section 2, Parent tips (4 bullets, 12pt):**
  - "Stickers beat candy: let your kid pick the sticker sheet"
  - "Missed a night? The streak restarts, the world doesn't end. Model the reset"
  - "Kid brushes first, parent 'checks corners' after"
  - "Tape the chart at KID height: ownership lives at eye level"
- **Section 3, 2-minute rule box (teal border):** "Two minutes is longer than you think. Play a song: when it ends, they're done."

## Page 3: Week 1 Tracker (Days 1-7)

- **Header:** "Week 1" + small sun icon + "(Days 1-7)"
- **Grid:** 7 rows (one per day), 3 columns: **Morning box | Night box | Floss box**
- **Row height:** 0.6in, big checkable boxes (kids mark these themselves)
- **Left margin:** day numbers in mint circles
- **Bottom band (Day 7):** Coral ribbon: "★ 7-DAY STREAK! Stick a sticker here ★" with a star-shaped empty box
- **Right margin strip (on all 3 tracker pages):** vertical text "Brush With Me: hygienist-reviewed routines"

## Page 4: Week 2 Tracker (Days 8-14)

- Identical structure to Page 3
- **Bottom band (Day 14):** Coral ribbon: "★★ 14-DAY STREAK! Halfway to champion ★★" with two-star badge box

## Page 5: Week 3 Tracker (Days 15-21) + Certificate

- **Top 60%:** identical tracker structure, Days 15-21
- **Bottom 40%, Certificate block (dashed-cut border with scissors icon):**
  - "🏆 CERTIFICATE OF COMPLETION 🏆" (24pt, Teal)
  - "This certifies that ______________ completed the 21-Day Brushing Challenge, brushing like a champion, morning and night."
  - Fill-in lines: "Brushed by: _______" / "Witnessed by (grown-up): _______" / "Date: _______"
  - Small tooth-with-crown icon
- **Footer:** "Ready for the next challenge? The full Stop-the-Brushing-Battles Kit adds rewards, scripts & troubleshooter: brushwithme.com/kit"

## Export settings

- File name: `brush-with-me-21-day-challenge.pdf`
- Canva: "PDF Print" export, 5 pages, about 2-5MB target
- Also export: `page4-week2.png` (the listing/lead-capture preview image) + `cover.png` (social/pin use)
- Grayscale test: print page 3 in B/W: boxes and text must stay fully legible (they will: no color-dependent elements)

## Email capture setup (Day 6 task, referencing this build)

- MailerLite form: "Get the FREE 21-Day Challenge" → instant-delivery automation → list "BrushWithMe Main"
- Thank-you page: kit upsell banner (PRODUCT_01)
- Deliverability: PDF under 5MB, delivery email subject: "Your 21-Day Challenge is inside 🦷 (print tonight, start tonight)"

## Production checklist (for the Day 6 build session)

- [ ] Build all 5 pages in Canva AI from this spec
- [ ] Grayscale print test on page 3
- [ ] A4 variant export
- [ ] Preview PNGs generated
- [ ] "Reviewed by Jessie Tang, RDH" seal verified on every page
- [x] Byline/years confirmed per Jessie's review: "Jessie Tang, RDH, 25 years" (locked 2026-10-03)