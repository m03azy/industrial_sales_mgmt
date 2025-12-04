# Industrial Product & Sales Management System

A comprehensive Laravel-based application for managing products, inventory, customers, and sales orders in an industrial setting.

## Features

### User Roles & Permissions
- **Super Admin**: Full system access
- **Sales Manager**: Manage sales team and view reports
- **Sales Agent**: Create orders, view products
- **Warehouse Manager**: Manage inventory and stock adjustments
- **Customer**: View own orders and download invoices (Customer Portal)

### Core Functionality
- **Product Management**: CRUD operations for products with SKU, pricing, and categorization
- **Inventory Tracking**: Real-time stock monitoring with low stock alerts
- **Customer Management**: Maintain customer database with company details
- **Sales Orders**: Create and manage orders with line items
- **Order Status Tracking**: Draft → Confirmed → Shipped → Paid workflow
- **Stock Deduction**: Automatic inventory adjustment on order confirmation
- **Dashboard**: Statistics and insights for quick overview

## Installation

### Prerequisites
- PHP 8.1 or higher
- Composer
- MySQL or SQLite
- Node.js & NPM (for frontend assets)

### Setup Steps

1. **Clone the repository** (or navigate to project directory)
   ```bash
   cd industrial_sales_mgmt
   ```

2. **Install PHP dependencies**
   ```bash
   composer install
   ```

3. **Configure environment**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

4. **Configure database** in `.env` file:
   ```
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=industrial_sales
   DB_USERNAME=root
   DB_PASSWORD=
   ```

5. **Run migrations and seeders**
   ```bash
   php artisan migrate
   php artisan db:seed
   ```

6. **Install frontend dependencies** (optional)
   ```bash
   npm install
   npm run dev
   ```

7. **Start the development server**
   ```bash
   php artisan serve
   ```

8. **Access the application**
   - URL: `http://localhost:8000`
   - Admin Login: `admin@example.com` / `password`
   - Sales Agent Login: `sales@example.com` / `password`

## Database Schema

### Tables
- `users` - System users with role assignments
- `roles` - User roles (super_admin, sales_manager, sales_agent, warehouse_manager, customer)
- `products` - Product catalog with pricing and stock information
- `customers` - Customer database with optional user account linking
- `sales_orders` - Sales orders with status tracking
- `order_items` - Line items for each order
- `inventory_transactions` - Stock movement history (in/out/adjustment)

## API Routes

All routes require authentication via `auth` middleware:

- **Dashboard**: `GET /dashboard`
- **Products**: `GET|POST|PUT|DELETE /products`
- **Inventory**: `GET|POST /products/{product}/adjust`
- **Customers**: `GET|POST|PUT|DELETE /customers`
- **Orders**: `GET|POST|PUT|DELETE /orders`

## Usage Examples

### Creating a Product
1. Navigate to `/products/create`
2. Fill in SKU, name, cost price, selling price
3. Set initial stock quantity and low stock threshold
4. Submit to create

### Creating a Sales Order
1. Navigate to `/orders/create`
2. Select customer and order date
3. Add product line items with quantities
4. Submit to create order in "draft" status
5. Update status to "confirmed" to deduct stock

### Adjusting Inventory
1. Navigate to product details
2. Click "Adjust Stock"
3. Select type (In/Out) and quantity
4. Add reference and notes
5. Submit to update stock

### Customer Portal
- Customers with linked user accounts can login
- View their order history
- Download invoices (when implemented)

## Development Notes

### Known Limitations
- SQLite driver may not be installed in WSL environment (use MySQL instead)
- Views are not yet implemented (controllers are ready)
- Authentication scaffolding needs to be added (use Laravel Breeze or Jetstream)

### Next Steps
- Implement Blade views for all CRUD operations
- Add authentication UI (Laravel Breeze recommended)
- Implement invoice generation (PDF)
- Add reporting and analytics
- Implement email notifications
- Add API authentication for mobile apps

## License

This project is open-source software.
