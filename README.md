# Laravel CMS - UniqLara

A modern, WordPress-like CMS built with Laravel featuring advanced content management, theme system, and business-ready functionality.

## 🚀 Features

### Content Management
- **Posts & Pages**: Full post and page management with custom post types
- **Categories & Tags**: Hierarchical categories with nested support
- **Media Library**: Advanced media management with file uploads
- **Featured Posts**: WordPress-style featured post system
- **Password Protection**: Post-level and site-wide password protection
- **Bulk Actions**: Bulk edit, delete, restore, and status updates

### Admin Dashboard
- **Modern UI**: Tabler-based responsive admin interface
- **User Management**: Role-based access control (Admin, Editor, Author)
- **Theme Management**: Easy theme switching and customization
- **Settings**: Site-wide configuration and security settings
- **Real-time Search**: AJAX-powered content search

### Developer Features
- **Theme System**: WordPress-like theme development with template hierarchy
- **Custom Templates**: Flexible template system for different layouts
- **Hooks & Observers**: Event-driven architecture for extensibility
- **Helper Functions**: WordPress-style helper functions for themes
- **API Ready**: Built with modern Laravel practices

### Security & Performance
- **Site-wide Password Protection**: Protect entire site during development
- **Role-based Permissions**: Granular access control using Spatie Permissions
- **Optimized Queries**: Efficient database queries with proper relationships
- **Asset Management**: Modern asset compilation with Vite

## 🛠️ Installation

1. **Clone the repository**
```bash
git clone https://github.com/yourusername/uniq-lara.git
cd uniq-lara
```

2. **Install dependencies**
```bash
composer install
npm install
```

3. **Environment setup**
```bash
cp .env.example .env
php artisan key:generate
```

4. **Database setup**
```bash
php artisan migrate
php artisan db:seed
```

5. **Build assets**
```bash
npm run build
```

6. **Start development server**
```bash
php artisan serve
```

## 📖 Usage

### Admin Access
- Visit `/admin` to access the admin dashboard

### Creating Content
1. **Posts**: Manage blog posts with categories, tags, and featured images
2. **Pages**: Create static pages with custom templates
3. **Media**: Upload and manage images, documents, and other files
4. **Themes**: Switch between themes and customize templates

### Site-wide Password Protection
1. Go to Admin > Settings
2. Enable "Site Password Protection"
3. Set your password
4. Visitors will need to enter the password to access the site

## 🎨 Theme Development

Create custom themes in `resources/views/themes/your-theme-name/`

## 📦 Tech Stack

- **Backend**: Laravel 9+
- **Frontend**: Tailwind CSS, Alpine.js
- **Database**: MySQL/PostgreSQL
- **Authentication**: Laravel Breeze
- **Permissions**: Spatie Laravel Permission
- **Assets**: Vite
- **UI Framework**: Tabler (Admin)

## 📄 License

This project is open-sourced software licensed under the [MIT license](LICENSE).

---

**UniqLara** - Making Laravel CMS development unique and powerful! 🎯

Laravel is a web application framework with expressive, elegant syntax. We believe development must be an enjoyable and creative experience to be truly fulfilling. Laravel takes the pain out of development by easing common tasks used in many web projects, such as:

- [Simple, fast routing engine](https://laravel.com/docs/routing).
- [Powerful dependency injection container](https://laravel.com/docs/container).
- Multiple back-ends for [session](https://laravel.com/docs/session) and [cache](https://laravel.com/docs/cache) storage.
- Expressive, intuitive [database ORM](https://laravel.com/docs/eloquent).
- Database agnostic [schema migrations](https://laravel.com/docs/migrations).
- [Robust background job processing](https://laravel.com/docs/queues).
- [Real-time event broadcasting](https://laravel.com/docs/broadcasting).

Laravel is accessible, powerful, and provides tools required for large, robust applications.

## Learning Laravel

Laravel has the most extensive and thorough [documentation](https://laravel.com/docs) and video tutorial library of all modern web application frameworks, making it a breeze to get started with the framework.

You may also try the [Laravel Bootcamp](https://bootcamp.laravel.com), where you will be guided through building a modern Laravel application from scratch.

If you don't feel like reading, [Laracasts](https://laracasts.com) can help. Laracasts contains over 2000 video tutorials on a range of topics including Laravel, modern PHP, unit testing, and JavaScript. Boost your skills by digging into our comprehensive video library.

## Laravel Sponsors

We would like to extend our thanks to the following sponsors for funding Laravel development. If you are interested in becoming a sponsor, please visit the Laravel [Patreon page](https://patreon.com/taylorotwell).

### Premium Partners

- **[Vehikl](https://vehikl.com/)**
- **[Tighten Co.](https://tighten.co)**
- **[Kirschbaum Development Group](https://kirschbaumdevelopment.com)**
- **[64 Robots](https://64robots.com)**
- **[Cubet Techno Labs](https://cubettech.com)**
- **[Cyber-Duck](https://cyber-duck.co.uk)**
- **[Many](https://www.many.co.uk)**
- **[Webdock, Fast VPS Hosting](https://www.webdock.io/en)**
- **[DevSquad](https://devsquad.com)**
- **[Curotec](https://www.curotec.com/services/technologies/laravel/)**
- **[OP.GG](https://op.gg)**
- **[WebReinvent](https://webreinvent.com/?utm_source=laravel&utm_medium=github&utm_campaign=patreon-sponsors)**
- **[Lendio](https://lendio.com)**

## Contributing

Thank you for considering contributing to the Laravel framework! The contribution guide can be found in the [Laravel documentation](https://laravel.com/docs/contributions).

## Code of Conduct

In order to ensure that the Laravel community is welcoming to all, please review and abide by the [Code of Conduct](https://laravel.com/docs/contributions#code-of-conduct).

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via [taylor@laravel.com](mailto:taylor@laravel.com). All security vulnerabilities will be promptly addressed.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
