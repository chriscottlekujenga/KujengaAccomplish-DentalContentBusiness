# WordPress Setup Guide: brushwithme.com

> Who runs this: the owner, in cPanel, following Day 12 Checklist step 3. Accomplish handles everything below in the next session once WordPress is installed and admin access is noted.
>
> Time estimate: 45 to 60 minutes, most of it one time. Nothing here touches the live review form at /review.

---

## Part 1: WordPress install (owner, cPanel, ~15 min)

1. Log in to HostGator cPanel (`https://brushwithme.com/cpanel` or the link from HostGator's welcome email).
2. Open **Softaculous Apps Installer** (or **WordPress Installer** in newer cPanel layouts).
3. Click **WordPress**, then **Install Now**.
4. Settings:
   - **Choose Domain**: brushwithme.com (the addon domain, NOT a subdomain).
   - **Directory**: leave BLANK so the site lives at the root. Do NOT type `wordpress` or anything else.
 - **Site Name**: `Brush With Me`
   - **Site Description**: `Oral care routines reviewed by a Registered Dental Hygienist.`
   - **Admin Username**: NOT `admin`. Pick anything else, note it in the password manager.
   - **Admin Password**: strong, generated, saved in the password manager.
   - **Admin Email**: the brand email (hello@brushwithme.com) if it exists, otherwise the owner's personal email for now.
5. Click Install. Softaculous gives you the admin URL: `https://brushwithme.com/wp-admin`.
6. Log in to wp admin once to confirm it works, then report "WP installed, admin access confirmed" and stop. Everything below waits for the next session.

---

## Part 2: Theme and core settings (Accomplish, ~10 min)

### Theme: GeneratePress (free)

1. wp admin > **Appearance > Themes > Add New** > search "GeneratePress" > **Install** > **Activate**.
2. **Appearance > Customize** to open the Customizer:
   - **Site Identity**: Site Title `Brush With Me`. Tagline `Oral care routines reviewed by a Registered Dental Hygienist.` Upload logo later when Canva assets exist (soft teal, rounded sans, white space; see BRAND_BRIEF.md section 5). Site icon (favicon): a simple teal checkmark or "BWM" mark, owner upload via Media later.
   - **Colors**: Primary: soft teal (e.g. `#6BA8A9` or similar from the brand palette). Background: warm white. Avoid dark backgrounds.
   - **Typography**: headings in a rounded sans (GeneratePress default works, or Quicksand/Nunito from Google Fonts if bundled). Body text: clean sans, 17 to 18px, generous line height.
   - **Layout > Sidebar Layout**: set to **No Sidebar** (or "Content One Sidebar" only if we add a newsletter box later).
   - **Layout > Container Width**: 1200px max.
3. **Settings > Reading**:
   - **Your homepage displays**: **A static page**. Homepage: `Home`. Posts page: leave blank (we link posts from Home manually, cleaner than a raw feed).
4. **Settings > General**: Site title and tagline confirmed here too.
5. **Settings > Discussion**: uncheck **"Allow link notifications from other blogs"** (pingbacks) and uncheck **"Allow people to post comments on new articles"** (no comment moderation workload for now).
6. **Settings > Permalinks**: **Post name** (e.g. `brushwithme.com/brush-or-floss-first/`). Save.
7. **Settings > Reading > Search Engine Visibility**: make sure **"Discourage search engines"** is UNCHECKED (we want indexing).
8. Evergreen setup (per Day 12 Checklist step 3): **Appearance > Customize > Blog** (in GeneratePress this is **Customize > Layout > Blog**):
   - Uncheck **"Display post date"** on single posts. Check **"Display updated date"** if the option exists (GeneratePress free may not have it; if not, skip, we note last updated manually at the top of each post).

### Child theme note

Not needed. GeneratePress free with light customizer work is fine. If we later want heavy customization, we will add the GeneratePress child theme then. Skip for now, keep the stack light.

---

## Part 3: Pages (Accomplish, ~15 min)

Create each page from the ready made copy in `content/site-pages/`. Paste the copy as written, it is already clinically reviewed and on brand.

| Page | File | Slug | Menu placement |
|---|---|---|---|
| Home | `home-page.md` | `/` (set as static homepage) | none needed |
| About | `about-page.md` | `/about` | main menu |
| Contact | `contact-page.md` | `/contact` | main menu |
| Privacy Policy | `privacy-policy.md` | `/privacy-policy` | footer only |
| Terms of Use | `terms-of-use.md` | `/terms-of-use` | footer only |
| Affiliate Disclosure | `affiliate-disclosure.md` | `/affiliate-disclosure` | footer only |
| Medical Disclaimer | `medical-disclaimer.md` | `/medical-disclaimer` | footer only |

Steps for each:

1. **Pages > Add New**. Paste the title. Paste the body copy from the file.
2. Remove the blockquote WordPress instruction note at the top of each file (the `> WordPress location: ...` line). That line is repo documentation, not page copy.
3. Fill the **[launch date]** placeholders in Privacy Policy and Terms of Use with the actual launch date.
4. **Publish** each page.
5. Build the menus: **Appearance > Menus**. Create menu "Main": Home, About, Contact. Create menu "Footer": Privacy Policy, Terms of Use, Affiliate Disclosure, Medical Disclaimer. Assign Main to Primary position, Footer to Footer position.
6. Home page hero button ("Start the routine: Read post 01") links to post 01's URL. Newsletter button links to the MailerLite signup form (Part 5) or a placeholder `/newsletter` page until MailerLite exists.

### Affiliate disclosure line on posts

Any post that contains affiliate links gets this line at the bottom, above the byline:

> This post contains affiliate links. If you buy through them, we may earn a small commission at no extra cost to you. We only recommend products Jessie would hand to her own family. See our Affiliate Disclosure.

---

## Part 4: Plugins (Accomplish, ~10 min)

Keep the stack minimal. Every plugin is a maintenance liability. Install only:

1. **MailerLite signup forms** (free, official): for the newsletter block on Home and the signup forms we build in Part 5.
2. **LiteSpeed Cache** (HostGator runs LiteSpeed): Pages > Cache. This is the performance baseline, no config needed beyond activation.
3. **Site Kit by Google** (free, official): connects the site to Search Console + Analytics in one plugin with minimal setup. Install now, activate when Search Console is verified (Day 12 Checklist step 4).

Explicitly NOT installing: SEO mega plugins (Yoast/Rank Math), page builders (Elementor), comment spam tools (comments are off), security suites (HostGator handles basic perimeter, we keep the stack light), backup plugins (host does nightly backups; repo is source of truth for content).

Rationale: a lightweight blog with GeneratePress + native WordPress + Site Kit needs no SEO plugin. Categories, clean slugs, good titles, and internal links are the SEO plan for now.

---

## Part 5: Newsletter signup (after MailerLite exists)

1. In MailerLite: create a signup form matching the Home page copy ("Get the routine in your inbox", one short email a week framing).
2. Copy the form's embed code or connect the official MailerLite WordPress plugin to the account.
3. Place the signup block on the Home page (replace the placeholder button) and consider a simple footer signup later.
4. The 4 email automation sequence copy exists in `content/email/` (subject lines and body copy, ready to paste into MailerLite automation).
5. Verify the automation: submit a test signup with the owner's address, confirm all 4 emails arrive over their scheduled days.

---

## Part 6: First publish, posts 01 and 02 (Accomplish, ~10 min)

Per PUBLISH_SCHEDULE: post 01 publishes Tuesday, post 02 Thursday, same week, quick indexed in Search Console the same day they go live.

1. **Posts > Add New**.
2. Title, body, and byline from `content/posts/01-brush-or-floss-first.md` (already review approved, 2026-10-03). Paste as is, no clinical edits without a new review packet.
3. Category: create and assign **Routines** (post 01) and **Toothpaste & Products** (post 02, or its correct category per the post file).
4. Featured image: skip until Canva assets exist (owner account, Day 12 Checklist step 2). Publish text first, images can be added to live posts without a new review.
5. Add the affiliate disclosure line if the post contains affiliate links (post 01 likely does not yet; check the file).
6. **Publish**. Confirm the URL matches the slug from the post file (Settings > Permalinks must already be set to Post name).
7. Repeat for post 02.
8. Same day: Search Console > URL Inspection > paste each live URL > **Request Indexing**. This is the quick index step from the Day 12 Checklist.

---

## Part 7: Verification checklist (end of Day 12 execution)

- [ ] brushwithme.com loads the Home page with hero, 3 columns, featured posts, newsletter block
- [ ] Main menu: Home, About, Contact. Footer menu: Privacy, Terms, Affiliate Disclosure, Medical Disclaimer
- [ ] All legal pages live and linked, no `[launch date]` placeholders remain
- [ ] Posts 01 and 02 live, correct slugs, categories assigned
- [ ] Both post URLs requested for indexing in Search Console
- [ ] No comments or pingbacks possible (Discussion settings off)
- [ ] Review form at /review still works (it is a standalone PHP app, untouched by WordPress; verify one page load)
- [ ] Newsletter block present (placeholder or live MailerLite form)
- [ ] Site title, tagline, and favicon correct
- [ ] No em dashes or en dashes anywhere on the live pages (brand rule; browser find on the live pages is enough)

Failure mode: if anything in this guide contradicts what WordPress actually shows in its current version, note it in the WORK_LOG and adapt the setting names, the goal matters more than the exact menu labels.

---

Staging complete when: pages exist as files (done, this folder), posts 01 and 02 ready (done, `content/posts/`), email copy ready (done, `content/email/`), pins ready (done, `content/pins/`), product specs ready (done, `content/product-specs/`).

Remaining owner gated items: WordPress install (Part 1), Canva account, MailerLite account, Search Console verification, Etsy shop, Pinterest account. See OWNER_ACTION_LIST.md and DAY_12_CHECKLIST.md.