# Student Management System - Setup Guide

## Prerequisites

- PHP 8.2 or higher
- MySQL 8.0 or higher
- Composer
- Node.js & npm
- Git

## Installation Steps

### Step 1: Clone Repository

```bash
git clone https://github.com/Moses123-png/student-management-system.git
cd student-management-system
```

### Step 2: Install PHP Dependencies

```bash
composer install
```

### Step 3: Install Node Dependencies

```bash
npm install
```

### Step 4: Environment Configuration

```bash
cp .env.example .env
php artisan key:generate
```

Edit `.env` file and update:
```
APP_NAME="Student Management System"
APP_URL=http://localhost:8000

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=sms_db
DB_USERNAME=root
DB_PASSWORD=
```

### Step 5: Database Setup

#### Option A: Using MySQL CLI

```bash
mysql -u root -p

CREATE DATABASE sms_db;
CREATE USER 'sms_user'@'localhost' IDENTIFIED BY 'sms_password';
GRANT ALL PRIVILEGES ON sms_db.* TO 'sms_user'@'localhost';
FLUSH PRIVILEGES;
EXIT;
```

Update `.env`:
```
DB_USERNAME=sms_user
DB_PASSWORD=sms_password
DB_DATABASE=sms_db
```

#### Option B: Using phpMyAdmin

1. Open phpMyAdmin (usually http://localhost/phpmyadmin)
2. Create new database: `sms_db`
3. Create user `sms_user` with password `sms_password`
4. Grant all privileges to user for `sms_db`

### Step 6: Run Migrations

```bash
php artisan migrate
```

### Step 7: Seed Demo Data

```bash
php artisan db:seed
```

This will create:
- 1 Admin user
- 2 Teacher users
- 50 Sample students
- Sample classes (P.1 - P.7)
- Sample marks
- Sample scholarships

### Step 8: Create Storage Link

```bash
php artisan storage:link
```

This creates a symbolic link from `public/storage` to `storage/app/public` for file uploads.

### Step 9: Build Frontend Assets

```bash
npm run build
```

For development (with hot reload):
```bash
npm run dev
```

### Step 10: Start Development Server

```bash
php artisan serve
```

The application will be available at: **http://localhost:8000**

## Default Login Credentials

### Administrator Account
- **Email**: admin@sms.local
- **Password**: password123
- **Access**: Full system access

### Teacher Account 1
- **Email**: teacher1@sms.local
- **Password**: password123
- **Assigned Class**: P.5
- **Subjects**: Mathematics, English, Science, Social Studies

### Teacher Account 2
- **Email**: teacher2@sms.local
- **Password**: password123
- **Assigned Class**: P.6
- **Subjects**: English, Social Studies, Religious Education

## Project Structure

```
student-management-system/
├── app/                           # Application code
│   ├── Http/
│   │   ├── Controllers/          # Request handlers
│   │   ├── Middleware/           # Custom middleware
│   │   └── Requests/             # Form validation
│   ├── Models/                   # Eloquent models
│   ├── Services/                 # Business logic
│   └── Exports/                  # Excel exports
├── database/
│   ├── migrations/               # Database migrations
│   ├── seeders/                  # Database seeders
│   ├── factories/                # Model factories
│   └── schema.md                 # Schema documentation
├── resources/
│   ├── views/                    # Blade templates
│   │   ├── admin/               # Admin pages
│   │   ├── teacher/             # Teacher pages
│   │   ├── auth/                # Authentication pages
│   │   └── layouts/             # Layout templates
│   ├── css/                      # Stylesheets
│   └── js/                       # JavaScript files
├── routes/
│   ├── api.php                   # API routes
│   ├── web.php                   # Web routes
│   └── admin.php                 # Admin routes
├── storage/                       # Uploaded files
├── tests/                         # PHPUnit tests
├── public/                        # Publicly accessible files
├── config/                        # Configuration files
├── .env.example                   # Environment template
├── composer.json                  # PHP dependencies
├── package.json                   # Node.js dependencies
└── README.md                      # Project README
```

## Common Commands

### Artisan Commands

```bash
# Clear all caches
php artisan cache:clear
php artisan config:clear
php artisan view:clear
php artisan route:clear

# Database
php artisan migrate              # Run migrations
php artisan migrate:rollback     # Rollback migrations
php artisan migrate:reset        # Reset all migrations
php artisan db:seed              # Seed database

# Storage
php artisan storage:link         # Create storage link

# User Management
php artisan tinker               # Interactive shell

# Development
php artisan serve                # Start development server
php artisan serve --port=8001    # Custom port
```

### NPM Commands

```bash
npm run dev      # Development mode with hot reload
npm run build    # Production build
npm run preview  # Preview production build
```

## Troubleshooting

### 1. Permission Issues

```bash
# Linux/Mac
chmod -R 775 storage bootstrap/cache
chmod -R 775 storage/logs

# Windows (in PowerShell as Administrator)
icacls "storage" /grant Users:F /T
icacls "bootstrap/cache" /grant Users:F /T
```

### 2. Database Connection Error

```bash
# Check database credentials in .env
# Verify MySQL is running
# Clear cached config
php artisan config:clear
php artisan cache:clear
```

### 3. Clear All Cache

```bash
php artisan cache:clear
php artisan config:clear
php artisan view:clear
php artisan route:clear
composer dump-autoload
```

### 4. Reset Database

```bash
# Be careful - this will delete all data
php artisan migrate:reset
php artisan migrate
php artisan db:seed
```

### 5. Storage Link Issue

```bash
# Remove old link if exists
rm public/storage

# Create new link
php artisan storage:link
```

### 6. Key Generation Error

```bash
php artisan key:generate
```

### 7. Node Modules Issues

```bash
# Remove node_modules and reinstall
rm -rf node_modules
npm install
```

## Production Deployment

### Before Going Live

1. **Set APP_DEBUG to false in .env**
   ```
   APP_DEBUG=false
   APP_ENV=production
   ```

2. **Run optimizations**
   ```bash
   php artisan config:cache
   php artisan route:cache
   php artisan view:cache
   composer dump-autoload --optimize
   ```

3. **Build production assets**
   ```bash
   npm run build
   ```

4. **Set correct permissions**
   ```bash
   chmod -R 755 storage bootstrap/cache
   chmod -R 755 public/storage
   ```

5. **Database backups**
   ```bash
   mysqldump -u root -p sms_db > backup.sql
   ```

## Support & Documentation

- [Database Schema Documentation](database/schema.md)
- [Laravel Documentation](https://laravel.com/docs)
- [Bootstrap Documentation](https://getbootstrap.com/docs)

## Contact

For technical support: moses@example.com

---

**Last Updated**: July 14, 2026
