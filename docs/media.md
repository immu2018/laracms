# Media Manager Module Documentation

## Overview
The Media Manager provides a modern interface for uploading, viewing, and managing images and files in the CMS. It supports multiple file uploads, bulk delete, and role-based access.

## Features
- Upload multiple files at once (AJAX)
- Allowed file types and max size are configurable (`config/media.php`)
- View files in a responsive grid
- Bulk delete with modal confirmation
- Only admins see all files; other users see only their uploads
- Delete and view actions for each file

## Key Files
- **Controller:** `app/Http/Controllers/Admin/MediaController.php`
- **Views:** `resources/views/admin/media/index.blade.php`
- **Config:** `config/media.php`
- **Model:** `app/Models/Media.php`
- **Routes:** Defined in `routes/web.php` under the `admin` group

## Main Routes
- `GET /admin/media` — List media files
- `POST /admin/media` — Upload files (AJAX)
- `POST /admin/media/bulk` — Bulk delete (with `ids` param)
- `DELETE /admin/media/{media}` — Single file delete

## Usage
- Access the media manager from the admin sidebar
- Upload files using the file input (multiple selection allowed)
- Select files with checkboxes for bulk delete
- Use the delete button for single file delete (with modal confirmation)

## Customization & Extension
- Change allowed file types/size in `config/media.php`
- To add new file types, update the `allowed_types` array
- To add new features (e.g., folders, search), extend the controller and Blade view
- For API access, add API routes and controller methods as needed

## Best Practices
- Keep all media logic in the MediaController and related views
- Use AJAX for uploads for a modern UX
- Use config files for settings, not hardcoded values
- Document any new features or changes in this file

---

For questions or contributions, see the main `docs/README.md` or contact the project maintainer.
