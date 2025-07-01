# Theme Development Guide

This document explains how to create, structure, and register a new theme for the CMS. Follow these instructions to ensure your theme is compatible and easily manageable from the admin panel.

---

## Theme Directory Structure

Each theme should be placed in its own directory under:

    resources/views/themes/{theme_name}/

**Example:**

    resources/views/themes/neotemplate/

### Required Files
- `frontpage.blade.php` — The main front page template for the theme.
- `theme.json` — Metadata file describing the theme (name, description, screenshot, etc.).

### Optional Files
- `screenshot.png` — Preview image shown in the admin theme manager.
- Additional Blade templates (e.g., `single-post.blade.php`, `partials/`, etc.)

---

## theme.json Format

```
{
  "name": "Theme Name",
  "description": "A short description of the theme.",
  "screenshot": "screenshot.png"
}
```
- `name`: Display name for the theme.
- `description`: Short summary for admin UI.
- `screenshot`: Relative path to a preview image (optional).

---

## Creating a New Theme: Step-by-Step

1. **Create a directory:**
   - `resources/views/themes/{your_theme}/`
2. **Add a `theme.json` file** with the required metadata.
3. **Add a `frontpage.blade.php`** file as the main entry point.
4. *(Optional)* Add a `screenshot.png` for admin preview.
5. *(Optional)* Add more templates or partials as needed.
6. **Activate your theme** from the admin panel (Themes menu).

---

## Adding Custom Templates

You can add custom Blade templates to your theme for different page types (e.g., blog posts, landing pages, etc.).

**Example structure:**

    resources/views/themes/mytheme/
    ├── frontpage.blade.php
    ├── single-post.blade.php
    ├── landing.blade.php
    ├── partials/
    │   └── header.blade.php
    └── theme.json

### How to Use Custom Templates
- In your controllers, render a custom template by using:
  ```php
  return view('themes.{active_theme}.{template_name}', $data);
  ```
  Replace `{active_theme}` with the current theme key and `{template_name}` with the template file name (without `.blade.php`).
- For example, to render a blog post:
  ```php
  $active = Setting::get('active_theme', 'modern');
  return view("themes.$active.single-post", compact('post'));
  ```
- You can organize reusable sections in a `partials/` folder and include them in your templates with `@include('themes.{active_theme}.partials.header')`.

---

## Best Practices
- Use clear, unique directory names for each theme.
- Keep all theme-specific assets (images, partials) inside the theme folder.
- Document any custom Blade components or sections in a README inside your theme folder.
- Test theme switching to ensure all templates render as expected.

---

## Example: Minimal Theme

    resources/views/themes/minimal/
    ├── frontpage.blade.php
    ├── theme.json
    └── screenshot.png

---

For advanced theme features, see the main project documentation or contact the core development team.
