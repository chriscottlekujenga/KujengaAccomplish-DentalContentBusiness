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

The review page is designed to require a reviewer password. Keep the hash out of Git by placing this separate, server-only file next to the Must-Use plugin:

`wp-content/mu-plugins/00-bwm-review-access.php`

```php
<?php
defined( 'ABSPATH' ) || exit;
define( 'BWM_REVIEW_PASSWORD_HASH', '<password_hash output>' );
```

Do not deploy the review page to production until that configuration file is present. The password must be shared with invited reviewers through a separate private channel.
