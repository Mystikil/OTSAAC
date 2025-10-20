# AAC Platform

## Requirements
- PHP 8.2+
- MySQL or MariaDB
- Apache with mod_rewrite
- Composer (optional for autoloading)

## Quick Start (XAMPP)
1. Copy the `aac/` directory into your XAMPP `htdocs`.
2. Ensure the `storage/` directory is writable by the web server.
3. Browse to `http://localhost/aac/public/setup` and follow the wizard:
   - Accept the agreement
   - Provide environment and theme options
   - Configure the database and choose schema version
   - Create an admin account
   - Optionally load demo content
4. After completion, the site is ready. The wizard writes configuration files and locks itself using `config/.installed`.

## Theme Import
1. Place HTML template parts in `app/Templating/themes/<your-theme>/`.
2. Map layout skeleton to `layout.html` with slots: `{{ yield:head }}`, `{{ yield:styles }}`, `{{ yield:scripts }}`, `{{ yield:content }}`, `{{ yield:alerts }}`.
3. Move repeatable structures (navigation, footer) into `partials/*.html` and reference them with `{{ include_partial:nav }}` etc.
4. Update `config/app.php` to set `'layout' => '<your-theme>'`. The change applies instantly.

## Demo Mode
The setup wizard can seed demo data including accounts, characters, guilds, market orders, PvP records, media entries, and news. Demo rows are marked with `is_demo = 1` for easy cleanup.

## CLI Migrations
Run migrations via:
```
php scripts/migrate.php
```
This will apply base schema and the selected game schema version, respecting table prefixes.

## Security Checklist
- Argon2id password hashing via `App\Security`
- CSRF tokens on all forms
- Rate limiting on login attempts
- Configurable captcha provider stub in `config/security.php`
- Audit log helper in `modules/Admin/AuditLog.php`

## Route Map
- `/` home dashboard
- `/setup` installation wizard
- `/login`, `/register`, `/account`
- `/characters`, `/characters/create`
- `/market`, `/market/create`, `/market/offer/{id}`
- `/guilds`, `/media`, `/pvp`, `/status`
- `/admin` admin overview

## Packaging
To create a distributable archive:
```
cd aac
zip -r ../aac-release.zip .
```
