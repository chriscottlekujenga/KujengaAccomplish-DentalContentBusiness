# DAY_12_CHECKLIST.md: Launch Sequence

> Created 2026-10-03. Consolidates everything needed to go live. Every item points at paste-ready work that already exists in the repo. Owner-gated steps are marked clearly; everything else is already done or executable the moment the account exists.

## Status: launch preparation in progress

- [x] All 15 posts drafted and dash-free, byline standardized ("Jessie Tang, RDH, 25 years")
- [x] Jessie's clinical review applied in full (32 Approve / 5 Fix; commit dd791cc)
- [x] Reviewer rules recorded in docs/BRAND_BRIEF.md section 8
- [x] Review form v3 deployed: submissions post directly to GitHub Issues (no email dependency); server backup retained (commit f29eba1)
- [x] Lead magnet production spec (content/products/LEAD_MAGNET_PRODUCTION.md) Canva-ready
- [x] Pinterest: 30-pin schedule + 15 written pin concepts (content/pins/)
- [x] Email: 4-email MailerLite automation copy, paste-ready (content/MAILERLITE_SETUP.md)
- [x] Etsy: shop skeleton + 4 product listings with titles/tags/descriptions (content/ETSY_SHOP_SKELETON.md, content/products/PRODUCT_*.md)
- [x] Publish schedule: 2 posts/week drip + quick-index plan (content/PUBLISH_SCHEDULE.md)
- [x] Video: 4 faceless scripts (content/video/VIDEO_SCRIPTS_BATCH_01.md)
- [x] Affiliate: shortlist + application packet (content/AFFILIATE_SHORTLIST.md, content/ETSY_ADS_AND_AFFILIATE_PACKET.md)

## Owner-gated steps (in order, with time estimates)

1. **GitHub token for the review form** (about 7 min): create fine-grained PAT (Issues read/write, this repo only), upload as .gh_review_token to /home4/ab39928/ via cPanel. Then signal to run the live test. (OWNER_ACTION_LIST has the exact steps.)
2. **Canva account**: completed on the free tier. Brand Kit colors set to mint #B8DED4, teal #4A9B8E, and coral #F2A48B. Lead magnet and pin artwork remain the next production task.
3. **WordPress install and design**: completed on Nexcess staging. The custom Brush With Me design, core pages, menus, first two posts, site icon, and WordPress-native review storage are live on staging. Production cutover is still pending public DNS propagation.
4. **Google Search Console** (10 min): verify brushwithme.com (the meta-tag method integrates with the WordPress install step).
5. **MailerLite account and form**: account created with hello@brushwithme.com; the 21-Day Challenge group and embedded form are drafted. DKIM, SPF, and domain-verification DNS records were added on 2026-10-07. Wait for MailerLite authentication before enabling the form and building the welcome automation.
6. **Etsy shop**: shop name BrushWithMeCo and basic preferences are set. Etsy requires owner payout identity details and its one-time setup fee before listings can be published.
7. **Pinterest business account**: Brush With Me business profile and public bio are live, with the website link added. Claim the website and schedule batch 1 after the production domain serves the Nexcess WordPress site.

## Trigger phrase

Say **"Owner setup complete - run Day 12"** when steps 2-7 are done and I execute the remaining automatable parts (site config polish, first publish, automation verification, pin scheduling confirmation).

## First-week production targets once live

- Posts 01 + 02 published and quick-indexed
- 15 pins live at 3/day
- Email automation verified end to end (test signup receives all 4 emails)
- Etsy 4 listings live; ads stay OFF until organic data exists (per ETSY_ADS_AND_AFFILIATE_PACKET.md)
- 2 videos published (scripts ready in content/video/)
