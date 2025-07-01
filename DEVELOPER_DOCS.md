# Laravel Modular CMS: Developer Documentation

This document lists all custom global helper functions for theme and module developers. See the index below for quick reference.

---

## Index

- Post & Page Helpers
  - get_post($id): Fetch a post or page by ID
  - get_post_meta($postId, $key, $default = null): Get a custom field/meta value for a post
  - get_posts($args = []): Fetch multiple posts with filters

- Template & Theme Helpers
  - get_theme_template($template, $context = 'theme'): Resolve a template path for the current theme or admin
  - get_available_templates($context = 'theme'): List all available templates for a context

- Menu Helpers
  - get_menu_items($location): Get menu items for a given location (nested)
  - buildMenuNestable($items): Render menu items as a nested <ol> for Nestable2 (admin only)
  
- Miscellaneous
  - get_excerpt($content, $length = 30): Generate an excerpt from content

- Category & Tag Helpers
  - get_category($id): Fetch a category by ID
  - get_categories($args = []): Get all categories, optionally filtered
  - get_tag($id): Fetch a tag by ID
  - get_tags($args = []): Get all tags, optionally filtered
- User Helpers
  - get_user($id): Fetch a user by ID
- Menu Helpers (extended)
  - get_menu($location): Get the menu object for a given location
  - render_menu($location, $view = null): Render a menu for a location, optionally with a custom view
- Theme Option Helpers
  - get_theme_option($key, $default = null): Fetch a theme option (for future theme customizer support)

---

## 1. Post & Page Helpers

### get_post($id)
- **Description:** Fetch a post or page by ID.
- **Usage:**
```php
$post = get_post(1);
echo $post->title;
```

### get_post_meta($postId, $key, $default = null)
- **Description:** Get a custom field/meta value for a post.
- **Usage:**
```php
$subtitle = get_post_meta($post->id, 'subtitle');
```

### get_posts($args = [])
- **Description:** Fetch multiple posts with filters (type, status, etc).
- **Usage:**
```php
$posts = get_posts(['type' => 'post', 'status' => 'published', 'limit' => 5]);
```

---

## 2. Template & Theme Helpers

### get_theme_template($template, $context = 'theme')
- **Description:** Resolve a template path for the current theme or admin.
- **Usage:**
```php
$template = get_theme_template('single-post');
return view($template, ['post' => $post]);
```

### get_available_templates($context = 'theme')
- **Description:** List all available templates for a context (theme/admin).
- **Usage:**
```php
$templates = get_available_templates('theme');
```

---

## 3. Menu Helpers

### get_menu_items($location)
- **Description:** Get menu items for a given location (e.g., 'header', 'footer'). Returns a nested collection.
- **Usage:**
```php
$menu = get_menu_items('header');
@foreach($menu as $item)
    <a href="{{ $item->url }}">{{ $item->title }}</a>
@endforeach
```

### buildMenuNestable($items)
- **Description:** Render menu items as a nested <ol> for use with Nestable2 (admin only).
- **Usage:**
```php
{!! buildMenuNestable($menu->items) !!}
```

---

## 4. Miscellaneous

### get_excerpt($content, $length = 30)
- **Description:** Generate an excerpt from content.
- **Usage:**
```php
echo get_excerpt($post->content, 20);
```

---

## 5. Category & Tag Helpers

### get_category($id)
- **Description:** Fetch a category by ID.
- **Usage:**
```php
$cat = get_category(1);
echo $cat->name;
```

### get_categories($args = [])
- **Description:** Get all categories, optionally filtered (e.g., by parent).
- **Usage:**
```php
$cats = get_categories();
foreach ($cats as $cat) echo $cat->name;
```

### get_tag($id)
- **Description:** Fetch a tag by ID.
- **Usage:**
```php
$tag = get_tag(1);
echo $tag->name;
```

### get_tags($args = [])
- **Description:** Get all tags, optionally filtered.
- **Usage:**
```php
$tags = get_tags();
foreach ($tags as $tag) echo $tag->name;
```

## 6. User Helpers

### get_user($id)
- **Description:** Fetch a user by ID.
- **Usage:**
```php
$user = get_user(1);
echo $user->name;
```

## 7. Menu Helpers (Extended)

### get_menu($location)
- **Description:** Get the menu object for a given location.
- **Usage:**
```php
$menu = get_menu('header');
echo $menu->name;
```

### render_menu($location, $view = null)
- **Description:** Render a menu for a location, optionally with a custom Blade view.
- **Usage:**
```php
echo render_menu('header');
// Or with a custom view:
echo render_menu('header', 'themes.modern.partials.menu');
```

## 8. Theme Option Helpers

### get_theme_option($key, $default = null)
- **Description:** Fetch a theme option (for future theme customizer support).
- **Usage:**
```php
$color = get_theme_option('primary_color', '#000');
```

---

## 5. Usage in Blade Templates
- All helpers are globally available in Blade and PHP files.
- Use them to fetch posts, meta, menus, and templates in your custom themes and modules.

---

## 6. Extending
- You can add new helpers in `app/Helpers/` and they will be autoloaded.
- Follow the existing function style for consistency.

---

For more advanced usage, see the codebase or contact the core team
