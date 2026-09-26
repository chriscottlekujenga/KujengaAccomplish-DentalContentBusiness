# OWNER_ACTION_LIST.md — Everything That Needs You (Days 6-11 Batch)

> Created at the end of the Day 6-11 batch, updated for existing HostGator hosting. Everything AI could do is done; these are the items that require your hands, card, or signatures — in priority order. Estimated total: 60-90 minutes across a few sittings.

## 🔴 Priority 1 — Domain + hosting hookup (unblocks 5 tasks, ~10 min)

- [ ] **Purchase brushwithme.com standalone** (~$10-12/yr at Namecheap or Porkbun — you already have hosting, no bundle needed)
- [ ] **Point it at your existing HostGator cPanel** (webkujenga.com/cpanel):
  - At the registrar: set nameservers to your HostGator account's NS pair (shown in your HostGator welcome email, or cPanel → "Nameservers" widget)
  - In cPanel: Domains → Create/Addon Domain → brushwithme.com (cPanel auto-creates the document root)
  - This replaces the old Bluehost plan — costs drop to just the ~$10/yr domain (you're already paying for hosting)
- [ ] **Create the brand email in cPanel**: Email Accounts → create e.g. jessie@brushwithme.com or hello@brushwithme.com — free with your hosting (this replaces the paid Google Workspace line in the old investment doc)

## 🟠 Priority 2 — Accounts + tools (30-40 min, all free tiers)

- [ ] **Canva account** (free tier works; Pro $13/mo unlocks brand kit + magic resize — recommended but optional): canva.com
- [ ] **MailerLite account** (free to 250 subscribers): mailerlite.com — create list "BrushWithMe Main"
- [ ] **Pinterest business account**: pinterest.com/business — name "Brush With Me," paste bio from content/pins/PINTEREST_SKELETON.md
- [ ] **Etsy shop**: etsy.com/sell — verify "BrushWithMeCo" name; paste everything from content/ETSY_SHOP_SKELETON.md (announcement, About, policies, FAQ); add payment method
- [ ] **Google Search Console**: search.google.com/search-console — verify the domain (needed for quick-indexing posts)
- [ ] **WordPress on brushwithme.com**: cPanel → Softaculous/WordPress installer → install on the addon domain; Accomplish handles theme/setup in the next session

## 🟡 Priority 3 — First money decisions (5-10 min, needs your card + judgment)

- [ ] **Etsy listing fees**: fund the shop's payment balance (~$1.20 for 6 listings — automatic with payment setup)
- [ ] **Etsy Ads**: approve the $3/day test budget (spec in content/ETSY_ADS_AND_AFFILIATE_PACKET.md — starts only after 2-3 reviews exist, so this is a future-approval, not today's spend)
- [ ] **Optional Canva Pro** $13/mo — recommended for the pin/template production volume

## 🟢 Priority 4 — Jessie's review form (goes live after Priority 1)

- [ ] **Upload the review form** (after brushwithme.com is live in cPanel):
  1. cPanel → File Manager → open the `brushwithme.com` document root (created when you added the addon domain)
  2. Upload `site/review.zip` → right-click → Extract (creates the `/review` folder with `index.php`)
  3. Create `hash.php` in `/review/` with: `<?php echo password_hash('Barcelona$pain', PASSWORD_DEFAULT);`
  4. Visit `brushwithme.com/review/hash.php`, copy the hash string it shows
  5. Edit `/review/index.php`: replace `REPLACE_WITH_GENERATED_HASH` with the copied hash; delete `hash.php`
  6. Test: `brushwithme.com/review` asks for the password → correct password shows the form → wrong password shows the error
  - *(Accomplish can do steps 1-6 with you in a live session — say "let's do the form setup now")*
- [ ] **Send Jessie:** the link **`brushwithme.com/review`** + the password (text/email — she enters it once, stays unlocked per device)
  - Her estimate: 90-120 min for this first one (the biggest ever; weekly is 60-90 after)
  - The 4 systematic answers at the top matter most — they become permanent writing rules
- [ ] Confirm with her: byline years + last-name permission (blocking the About page + Etsy copy finalization)

## 🔵 Priority 5 — After her review returns

- [ ] Approve affiliate applications going out (Amazon, MySmile, Skimlinks, BURST — blurb is written, forms take ~5 min each; GLO Science + Opencure wait for site live)
- [ ] Green-light the first Pinterest pin batch going live (3/day cadence per the skeleton)

## What happens automatically once these are done

Say **"Owner setup complete — run Day 12"** and Accomplish will:
1. Apply Jessie's review fixes + record her systematic rules in BRAND_BRIEF.md
2. Build the lead magnet + 15 pins in Canva (per LEAD_MAGNET_PRODUCTION.md + PINS_BATCH_01.md)
3. Set up the site theme, pages, evergreen-date setting, posts 01-02 published + quick-indexed
4. Configure MailerLite form + welcome automation (copy is paste-ready in MAILERLITE_SETUP.md)
5. Open the Etsy listings (copy paste-ready per product specs)
6. Begin the 3/day pin cadence and 2 posts/week drip per PUBLISH_SCHEDULE.md

**The whole launch sequence is staged. Your 60-90 minutes of setup + Jessie's review is the only thing between the sprint and go-live.**