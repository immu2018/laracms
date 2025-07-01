# Upgrade Guide

This document explains how to safely upgrade your CMS to the latest version.

## General Upgrade Steps
1. **Backup your database and files.**
2. Pull the latest code or update via Composer.
3. Review the [CHANGELOG.md](./CHANGELOG.md) for breaking changes and new features.
4. Run any new migrations:
   ```
   php artisan migrate
   ```
5. If any config files or theme files have changed, merge your customizations as needed.
6. Test your site thoroughly.

## Database Changes
- If a release includes database changes, they will be handled by Laravel migrations.
- Always run `php artisan migrate` after updating.
- If manual data migration is needed, instructions will be provided in this file for each release.

## Example: Manual Data Migration
If a release requires a manual update (e.g., renaming a column):

```
php artisan db:seed --class=UpdateOldDataSeeder
```

## Troubleshooting
- If you encounter issues, check the logs and consult the changelog for breaking changes.
- For help, contact the CMS maintainer or open an issue.

---

Always read the changelog and this file before upgrading to a new major version!
