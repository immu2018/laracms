# Laravel CMS - Setup & Development Guide

## 🚀 Overview
Laravel CMS is a modern, feature-rich content management system built with Laravel 9+. It includes advanced admin features, theme system, user management, and site-wide security options.

## 🔧 Prerequisites & Environment Setup

### Required Software

#### PHP & Web Server
- **PHP 8.0+** with required extensions:
  - `mbstring`
  - `openssl`
  - `pdo`
  - `tokenizer`
  - `xml`
  - `ctype`
  - `json`
  - `bcmath`
  - `fileinfo`
  - `gd` (for image processing)
- **Apache/Nginx** or **XAMPP/WAMP/MAMP**
- **MySQL 5.7+** or **MariaDB 10.3+**

#### Development Tools
- **Composer** (PHP dependency manager) - [Download](https://getcomposer.org/)
- **Node.js 16+** and **npm** - [Download](https://nodejs.org/)
- **Git** - [Download](https://git-scm.com/)

#### Optional but Recommended
- **PHP Storm** or **VS Code** (IDE)
- **Postman** (API testing)
- **MySQL Workbench** (database management)

---

## 📋 Installation Instructions

### Step 1: Clone the Repository
```bash
git clone https://github.com/immu2018/laracms.git
cd laracms
```

### Step 2: Install Dependencies
```bash
# Install PHP dependencies
composer install

# Install Node.js dependencies
npm install
```

### Step 3: Environment Configuration
```bash
# Copy environment file
cp .env.example .env

# Generate application key
php artisan key:generate
```

### Step 4: Database Setup

#### Create Database
Create a new MySQL database named `laracms` (or any name you prefer).

#### Configure Environment
Edit `.env` file with your database credentials:
```env
APP_NAME="Laravel CMS"
APP_ENV=local
APP_KEY=base64:YOUR_GENERATED_KEY
APP_DEBUG=true
APP_URL=http://localhost:8000

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=laracms
DB_USERNAME=root
DB_PASSWORD=your_password
```

#### Run Migrations
```bash
# Create database tables
php artisan migrate

# Seed default data (roles, users, categories, etc.)
php artisan db:seed
```

### Step 5: Storage & Permissions
```bash
# Create symbolic link for storage
php artisan storage:link

# Set proper permissions (Linux/Mac only)
chmod -R 775 storage bootstrap/cache

# Windows users: Ensure storage and bootstrap/cache folders are writable
```

### Step 6: Build Frontend Assets
```bash
# For development
npm run dev

# For production
npm run build

# For watching changes during development
npm run watch
```

### Step 7: Start Development Server
```bash
php artisan serve
```

The application will be available at: `http://localhost:8000`

---

## 🎯 Default Access Credentials

### Admin Panel
- **URL**: `http://localhost:8000/admin`
- **Email**: `admin@admin.com`
- **Password**: `password`

### User Roles Created by Default
- **Administrator**: Full access to all features
- **Editor**: Can manage posts, pages, categories, tags, media
- **Author**: Can create and edit own posts

---

## 🚀 Key Features

### Admin Dashboard Features
✅ **Posts Management**
- Create, edit, delete posts
- Bulk actions (publish, draft, delete)
- Featured images via media library
- Categories and tags
- Post scheduling
- Password protection per post
- Featured post marking

✅ **Pages Management**
- Separate from posts
- Custom templates
- Hierarchical structure support

✅ **User Management**
- Role-based access control
- User creation and editing
- Bulk user operations

✅ **Media Library**
- File upload and management
- Image thumbnails
- Featured image selection
- Organize by folders

✅ **Category & Tag Management**
- Nested categories support
- Bulk operations
- Usage statistics

✅ **Theme System**
- Multiple theme support
- Custom templates per post/page
- Theme switching from admin

✅ **Site Settings**
- Site-wide password protection
- Global configuration options
- Theme selection

### Frontend Features
✅ **Modern Responsive Theme**
✅ **Password Protection** (site-wide and per post)
✅ **Featured Posts Display**
✅ **Dynamic Navigation Menus**
✅ **SEO-friendly URLs**
✅ **Search Functionality**

---

## ⚙️ Configuration Options

### Mail Configuration (Optional)
For email notifications and password resets:
```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your-email@gmail.com
MAIL_PASSWORD=your-app-password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=your-email@gmail.com
MAIL_FROM_NAME="${APP_NAME}"
```

### Site Password Protection
1. Login to admin panel
2. Go to **Settings** (in sidebar)
3. Enable "Site Password Protection"
4. Set your desired password
5. Customize the protection message

### Theme Management
1. Go to **Admin → Themes**
2. Select active theme
3. Upload new themes to `resources/views/themes/`

---

## 🏗️ Development Guidelines

### Project Structure
```
laracms/
├── app/
│   ├── Http/Controllers/         # Application controllers
│   ├── Models/                   # Eloquent models
│   ├── Helpers/                  # Helper functions
│   └── Observers/                # Model observers
├── database/
│   ├── migrations/               # Database migrations
│   └── seeders/                 # Database seeders
├── resources/views/
│   ├── admin/                   # Admin panel views
│   ├── themes/                  # Frontend themes
│   └── layouts/                 # Base layouts
├── routes/
│   └── web.php                  # Web routes
└── public/
    └── storage/                 # Symbolic link to storage
```

### Adding New Features

#### 1. Create a Migration
```bash
php artisan make:migration create_your_table_name
```

#### 2. Create a Model
```bash
php artisan make:model YourModel
```

#### 3. Create a Controller
```bash
php artisan make:controller Admin/YourController --resource
```

#### 4. Add Routes
Edit `routes/web.php`:
```php
Route::middleware(['auth', 'role:administrator'])->prefix('admin')->name('admin.')->group(function () {
    Route::resource('your-resource', YourController::class);
});
```

#### 5. Create Views
Create views in `resources/views/admin/your-feature/`

### Theme Development
1. Create new theme folder: `resources/views/themes/your-theme/`
2. Required files:
   - `frontpage.blade.php` (homepage)
   - `single-post.blade.php` (post detail)
   - `archive.blade.php` (post listing)
   - `page.blade.php` (page template)
3. Include partials in `partials/` subfolder
4. Activate theme from admin panel

---

## 🐛 Troubleshooting

### Common Issues

#### Permission Errors
```bash
# Linux/Mac
sudo chown -R www-data:www-data storage bootstrap/cache
chmod -R 775 storage bootstrap/cache

# Windows
# Ensure storage and bootstrap/cache folders are writable through folder properties
```

#### Database Connection Issues
1. Check MySQL service is running
2. Verify database credentials in `.env`
3. Ensure database exists
4. Test connection: `php artisan tinker` then `DB::connection()->getPdo()`

#### Composer Issues
```bash
composer clear-cache
composer install --no-cache --prefer-dist
```

#### NPM Issues
```bash
npm cache clean --force
rm -rf node_modules package-lock.json
npm install
```

#### Storage Link Issues
```bash
# Remove existing link and recreate
rm public/storage
php artisan storage:link
```

### Error Logs
- **Laravel Logs**: `storage/logs/laravel.log`
- **Web Server Logs**: Check Apache/Nginx error logs
- **PHP Errors**: Enable `APP_DEBUG=true` in `.env` for development

---

## 📚 Additional Resources

### Laravel Documentation
- [Laravel 9.x Documentation](https://laravel.com/docs/9.x)
- [Eloquent ORM](https://laravel.com/docs/9.x/eloquent)
- [Blade Templates](https://laravel.com/docs/9.x/blade)

### Packages Used
- **Spatie Laravel Permission** - Role and permission management
- **Laravel Breeze** - Authentication scaffolding
- **Tabler** - Admin dashboard UI framework

### API Documentation
The CMS includes API endpoints for:
- Posts management
- User authentication
- Media uploads

API documentation available at: `/api/documentation` (if enabled)

---

## 🤝 Contributing

### Development Workflow
1. Fork the repository
2. Create a feature branch: `git checkout -b feature/your-feature`
3. Commit changes: `git commit -m "Add your feature"`
4. Push to branch: `git push origin feature/your-feature`
5. Create a Pull Request

### Coding Standards
- Follow PSR-12 coding standards
- Use meaningful variable and function names
- Add comments for complex logic
- Write tests for new features

### Testing
```bash
# Run tests
php artisan test

# Run specific test
php artisan test --filter YourTestName
```

---

## 📄 License

This project is open-sourced software licensed under the [MIT license](LICENSE).

---

## 🆘 Support

For support and questions:
- **GitHub Issues**: [Create an issue](https://github.com/immu2018/laracms/issues)
- **Documentation**: This README file
- **Laravel Community**: [Laravel.io](https://laravel.io)

---

## 🎉 Quick Start Checklist

- [ ] PHP 8.0+ installed
- [ ] Composer installed
- [ ] Node.js & npm installed
- [ ] MySQL database created
- [ ] Repository cloned
- [ ] Dependencies installed (`composer install` & `npm install`)
- [ ] Environment configured (`.env` file)
- [ ] Database migrated (`php artisan migrate`)
- [ ] Data seeded (`php artisan db:seed`)
- [ ] Storage linked (`php artisan storage:link`)
- [ ] Assets compiled (`npm run dev`)
- [ ] Development server started (`php artisan serve`)
- [ ] Admin access verified (`http://localhost:8000/admin`)

**🎯 You're ready to start developing with Laravel CMS!**
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
