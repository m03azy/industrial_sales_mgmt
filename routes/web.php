<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\SalesOrderController;
use App\Http\Controllers\InventoryController;
use App\Http\Controllers\ExportController;
use App\Http\Controllers\InvoiceController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
})->name('welcome');

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

// Role-specific dashboards
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard/factory', [DashboardController::class, 'factory'])->name('dashboard.factory');
    Route::get('/dashboard/retailer', [DashboardController::class, 'retailer'])->name('dashboard.retailer');
    Route::get('/dashboard/driver', [DashboardController::class, 'driver'])->name('dashboard.driver');
});



Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Chat Routes
    Route::get('/chat', [App\Http\Controllers\ChatController::class, 'index'])->name('chat.index');
    Route::get('/chat/users', [App\Http\Controllers\ChatController::class, 'users'])->name('chat.users');
    Route::get('/chat/{conversation}', [App\Http\Controllers\ChatController::class, 'show'])->name('chat.show');
    Route::post('/chat/{conversation}', [App\Http\Controllers\ChatController::class, 'store'])->name('chat.store');
    Route::get('/chat/start/{user}', [App\Http\Controllers\ChatController::class, 'create'])->name('chat.start');

    // Notification Routes
    Route::get('/notifications', [App\Http\Controllers\NotificationController::class, 'index'])->name('notifications.index');
    Route::any('/notifications/{id}/read', [App\Http\Controllers\NotificationController::class, 'markAsRead'])->name('notifications.read');
    Route::post('/notifications/mark-all-read', [App\Http\Controllers\NotificationController::class, 'markAllAsRead'])->name('notifications.markAllAsRead');
    
    // Product resource routes (index, create, store, show, edit, update, destroy)
    Route::resource('products', ProductController::class);
    // Customer resource routes
    Route::resource('customers', CustomerController::class);
    // Suppliers resource routes (supply chain)
    Route::resource('suppliers', \App\Http\Controllers\SupplierController::class);
    // Purchase Orders (supply/procurement)
    Route::resource('purchase-orders', \App\Http\Controllers\PurchaseOrderController::class);
    Route::post('purchase-orders/{purchase_order}/receive', [\App\Http\Controllers\PurchaseOrderController::class, 'receive'])->name('purchase-orders.receive');
    
    // Sales Orders (general access for viewing)
    Route::resource('orders', \App\Http\Controllers\SalesOrderController::class);
    
    // Factory Portal - protected by EnsureFactory middleware
    Route::middleware([\App\Http\Middleware\EnsureFactory::class])->prefix('factory')->name('factory.')->group(function () {
        Route::get('dashboard', [\App\Http\Controllers\Factory\DashboardController::class, 'index'])->name('dashboard');
        Route::resource('products', \App\Http\Controllers\Factory\ProductController::class);
        Route::get('orders', [\App\Http\Controllers\Factory\OrderController::class, 'index'])->name('orders.index');
        Route::get('orders/{order}', [\App\Http\Controllers\Factory\OrderController::class, 'show'])->name('orders.show');
        Route::post('orders/{order}/update-status', [\App\Http\Controllers\Factory\OrderController::class, 'updateStatus'])->name('orders.update-status');
        Route::get('analytics', [\App\Http\Controllers\Factory\AnalyticsController::class, 'index'])->name('analytics.index');
        Route::get('profile/edit', [\App\Http\Controllers\Factory\ProfileController::class, 'edit'])->name('profile.edit');
        Route::post('profile/update', [\App\Http\Controllers\Factory\ProfileController::class, 'update'])->name('profile.update');
    });
    
    // Retailer Portal - protected by EnsureRetailer middleware
    Route::middleware([\App\Http\Middleware\EnsureRetailer::class])->prefix('retailer')->name('retailer.')->group(function () {
        Route::get('dashboard', [\App\Http\Controllers\Retailer\DashboardController::class, 'index'])->name('dashboard');
        Route::get('products', [\App\Http\Controllers\Retailer\ProductController::class, 'index'])->name('products.index');
        Route::get('products/{product}', [\App\Http\Controllers\Retailer\ProductController::class, 'show'])->name('products.show');
        Route::get('orders', [\App\Http\Controllers\Retailer\OrderController::class, 'index'])->name('orders.index');
        Route::get('orders/{order}', [\App\Http\Controllers\Retailer\OrderController::class, 'show'])->name('orders.show');
        Route::get('profile/edit', [\App\Http\Controllers\Retailer\ProfileController::class, 'edit'])->name('profile.edit');
        Route::post('profile/update', [\App\Http\Controllers\Retailer\ProfileController::class, 'update'])->name('profile.update');
    });
    
    // Supplier portal (for users linked to a supplier) - protected by auth + EnsureSupplier middleware
    Route::middleware([\App\Http\Middleware\EnsureSupplier::class])->prefix('supplier')->name('supplier.')->group(function () {
        Route::get('orders', [\App\Http\Controllers\SupplierPortalController::class, 'index'])->name('orders.index');
        Route::get('orders/{purchaseOrder}', [\App\Http\Controllers\SupplierPortalController::class, 'show'])->name('orders.show');
        Route::post('orders/{purchaseOrder}/ship', [\App\Http\Controllers\SupplierPortalController::class, 'markShipped'])->name('orders.ship');
        Route::post('orders/{purchaseOrder}/deliver', [\App\Http\Controllers\SupplierPortalController::class, 'markDelivered'])->name('orders.deliver');
    });
    // Delivery management routes (admin only)
    Route::middleware(['auth', \App\Http\Middleware\EnsureAdmin::class])->prefix('admin')->name('admin.')->group(function () {
        Route::resource('deliveries', \App\Http\Controllers\DeliveryController::class);
        Route::resource('drivers', \App\Http\Controllers\Admin\DriverController::class);
        
        // Analytics
        Route::get('analytics', [\App\Http\Controllers\Admin\AnalyticsController::class, 'index'])->name('analytics.index');
        
        // Content Management
        Route::get('content', [\App\Http\Controllers\Admin\ContentController::class, 'index'])->name('content.index');
        Route::get('content/categories', [\App\Http\Controllers\Admin\ContentController::class, 'categories'])->name('content.categories');
        Route::post('content/categories', [\App\Http\Controllers\Admin\ContentController::class, 'storeCategory'])->name('content.categories.store');
        Route::get('content/banners', [\App\Http\Controllers\Admin\ContentController::class, 'banners'])->name('content.banners');
        Route::post('content/banners', [\App\Http\Controllers\Admin\ContentController::class, 'storeBanner'])->name('content.banners.store');
        Route::get('content/faqs', [\App\Http\Controllers\Admin\ContentController::class, 'faqs'])->name('content.faqs');
        Route::post('content/faqs', [\App\Http\Controllers\Admin\ContentController::class, 'storeFaq'])->name('content.faqs.store');
        
        // Dispute Center
        Route::get('disputes', [\App\Http\Controllers\Admin\DisputeController::class, 'index'])->name('disputes.index');
        Route::get('disputes/{dispute}', [\App\Http\Controllers\Admin\DisputeController::class, 'show'])->name('disputes.show');
        Route::post('disputes/{dispute}/resolve', [\App\Http\Controllers\Admin\DisputeController::class, 'resolve'])->name('disputes.resolve');

        // Order Management
        Route::resource('orders', \App\Http\Controllers\SalesOrderController::class);

        // User Management
        Route::resource('users', \App\Http\Controllers\Admin\UserManagementController::class);
        Route::post('users/{user}/approve', [\App\Http\Controllers\Admin\UserManagementController::class, 'approve'])->name('users.approve');
        Route::post('users/{user}/suspend', [\App\Http\Controllers\Admin\UserManagementController::class, 'suspend'])->name('users.suspend');

        // POS System
        Route::get('pos', [\App\Http\Controllers\Admin\PosController::class, 'index'])->name('pos.index');
        Route::get('pos/search', [\App\Http\Controllers\Admin\PosController::class, 'search'])->name('pos.search');
        Route::post('pos/checkout', [\App\Http\Controllers\Admin\PosController::class, 'checkout'])->name('pos.checkout');

    });
    
    // Cart routes for retailers
    Route::middleware(['auth'])->group(function () {
        Route::get('cart', [\App\Http\Controllers\CartController::class, 'index'])->name('cart.index');
        Route::post('cart/add', [\App\Http\Controllers\CartController::class, 'add'])->name('cart.add');
        Route::post('cart/remove', [\App\Http\Controllers\CartController::class, 'remove'])->name('cart.remove');
        Route::post('cart/update', [\App\Http\Controllers\CartController::class, 'update'])->name('cart.update');
        Route::post('cart/clear', [\App\Http\Controllers\CartController::class, 'clear'])->name('cart.clear');
        Route::get('cart/checkout', [\App\Http\Controllers\CartController::class, 'checkout'])->name('cart.checkout');
        Route::post('cart/process-checkout', [\App\Http\Controllers\CartController::class, 'processCheckout'])->name('cart.process-checkout');
        
        // Invoice routes
        Route::get('invoice/{order}/download', [\App\Http\Controllers\InvoiceController::class, 'download'])->name('invoice.download');
        Route::get('invoice/{order}/view', [\App\Http\Controllers\InvoiceController::class, 'view'])->name('invoice.view');
        Route::post('invoice/{order}/email', [\App\Http\Controllers\InvoiceController::class, 'email'])->name('invoice.email');
    });
    
    // Inventory adjustment routes
    Route::get('inventory/{product}/adjust', [InventoryController::class, 'adjust'])->name('inventory.adjust');
    Route::post('inventory/{product}/adjust', [InventoryController::class, 'storeAdjustment'])->name('inventory.store');
    // Export data
    Route::get('export/{type}', [ExportController::class, 'export'])->name('export.data');

    // Driver Portal
    Route::middleware(['auth'])->prefix('driver')->name('driver.')->group(function () {
        Route::get('deliveries', [\App\Http\Controllers\Driver\DriverDeliveryController::class, 'index'])->name('deliveries.index');
        Route::get('deliveries/{delivery}', [\App\Http\Controllers\Driver\DriverDeliveryController::class, 'show'])->name('deliveries.show');
        Route::patch('deliveries/{delivery}/status', [\App\Http\Controllers\Driver\DriverDeliveryController::class, 'updateStatus'])->name('deliveries.update-status');
    });
});

require __DIR__.'/auth.php';
