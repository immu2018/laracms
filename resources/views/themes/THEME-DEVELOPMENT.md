# Laravel CMS Theme Development Guide

## Theme Folder Structure (Example: "nova")

```
resources/views/themes/nova/
│
├── assets/
│   ├── css/
│   │   └── style.css           // Main theme stylesheet
│   ├── js/
│   │   └── theme.js            // Theme-specific JS
│   └── images/
│       └── logo.png            // Theme images
│
├── partials/
│   ├── header.blade.php         // Site header (logo, nav, etc.)
│   ├── footer.blade.php         // Site footer
│   ├── menu.blade.php           // Navigation/menu partial
│   └── (other reusable partials)
│
├── templates/
│   ├── frontpage.blade.php      // Custom front page (optional)
│   ├── single-post.blade.php    // Single post view
│   ├── archive.blade.php        // Archive/listing view (blog, category, etc.)
│   ├── index.blade.php          // Fallback for any listing/homepage
│   ├── page.blade.php           // Static page view (optional)
│   ├── 404.blade.php            // Not found page (optional)
│   └── (other custom templates, e.g., archive-news.blade.php)
│
└── (theme.json or config file, if needed)
```

## Minimum Required Files
- `partials/header.blade.php`
- `partials/footer.blade.php`
- `partials/menu.blade.php`
- `templates/single-post.blade.php`
- `templates/archive.blade.php`
- `templates/index.blade.php`

## Optional (Recommended) Files
- `templates/frontpage.blade.php`
- `templates/page.blade.php`
- `templates/404.blade.php`

## Theme Assets (CSS, JS, Images)
- Place all theme-specific CSS, JS, and images in the `assets/` folder inside your theme.
- On build/deploy, copy or publish these to `public/themes/{theme}/`.
- Reference assets in Blade using the `asset()` helper:

```blade
<link rel="stylesheet" href="{{ asset('themes/nova/css/style.css') }}">
<script src="{{ asset('themes/nova/js/theme.js') }}"></script>
<img src="{{ asset('themes/nova/images/logo.png') }}" alt="Logo">
```

## Template Hierarchy
The system will automatically use the most specific template available for each page type. For example, for a single post:
1. `single-post.blade.php`
2. `single.blade.php`
3. `default.blade.php`

For archives:
1. `archive-{type}.blade.php`
2. `archive.blade.php`
3. `index.blade.php`

## How to Build a Theme
1. Copy the `nova` folder and rename it for your theme.
2. Edit the partials and templates to match your design.
3. Use Blade syntax and Laravel helpers as needed.
4. Add new templates or partials as required by your layout.

## Example: Including Partials
```blade
@include('themes.nova.partials.header')
@include('themes.nova.partials.menu')
@include('themes.nova.partials.footer')
```

## Example: Template Candidates in Controller
```php
$candidates = [
    "themes.$active.templates.archive-news",
    "themes.$active.templates.archive",
    "themes.$active.templates.index",
];
return TemplateHelper::resolve($candidates, compact('posts'));
```

---

For questions or advanced customization, see the `TemplateHelper` class or contact the CMS maintainer.
