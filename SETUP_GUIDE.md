# Quick Setup Guide

## Database Configuration

The SQLite driver is not available in your WSL environment. Please use MySQL instead.

### Option 1: Use MySQL (Recommended)

1. **Install MySQL** (if not already installed):
```bash
sudo apt update
sudo apt install mysql-server
sudo service mysql start
```

2. **Create Database**:
```bash
sudo mysql
CREATE DATABASE industrial_sales;
CREATE USER 'laravel'@'localhost' IDENTIFIED BY 'password';
GRANT ALL PRIVILEGES ON industrial_sales.* TO 'laravel'@'localhost';
FLUSH PRIVILEGES;
EXIT;
```

3. **Update `.env` file**:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=industrial_sales
DB_USERNAME=laravel
DB_PASSWORD=password
```

4. **Run Migrations**:
```bash
php artisan migrate:fresh --seed
```

### Option 2: Install SQLite Extension

```bash
sudo apt-get install php-sqlite3
sudo service apache2 restart  # or restart your PHP service
```

## Running the Application

1. **Start the development server**:
```bash
php artisan serve
```

2. **Access the application**:
- URL: http://localhost:8000
- Login: admin@example.com / password

## Test Credentials

- **Admin**: admin@example.com / password
- **Sales Agent**: sales@example.com / password

## Features Available

✅ **Dashboard** - Statistics, low stock alerts, recent orders
✅ **Products** - Full CRUD with inventory tracking
✅ **Customers** - Customer management
✅ **Orders** - Sales order management (views pending)
✅ **Inventory** - Stock adjustments

## Next Steps

1. Configure database (see above)
2. Run migrations: `php artisan migrate:fresh --seed`
3. Start server: `php artisan serve`
4. Login and test!

## Missing Views

The following views still need to be created:
- Customers (index, create, edit, show)
- Orders (index, create, show)

All controllers are fully functional and ready to use once views are created.
