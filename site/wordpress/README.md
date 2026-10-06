# WordPress deployment source

This directory is the source of the WordPress work deployed to the Nexcess staging site.

## Included

- `mu-plugins/brush-with-me.php` is the Must-Use plugin deployed to `wp-content/mu-plugins/`.
- `assets/brush-with-me-site-icon.png` is the site icon uploaded during WordPress setup.

## What the Must-Use plugin does

- Presents the custom Brush With Me homepage treatment on the static front page.
- Adds `/review/`, a clinical-review intake form.
- Stores submitted reviews as private `Clinical Reviews` entries in WordPress Admin.

## Deployment

Upload `mu-plugins/brush-with-me.php` to:

`wp-content/mu-plugins/brush-with-me.php`

Must-Use plugins load automatically, so no admin-side activation is required. Visit the homepage once after upload so WordPress refreshes the `/review/` rewrite rule.

## Before production

The review page needs an access-control decision before the public DNS cutover. Its submissions are private in the dashboard, but the form URL itself is presently reachable without a reviewer login. Add reviewer authentication or a protected link before pointing the production domain at this WordPress install.
