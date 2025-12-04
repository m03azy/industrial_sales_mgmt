<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Factory;
use App\Models\FactoryProfile;
use App\Models\Retailer;
use App\Models\RetailerProfile;
use App\Models\Product;
use App\Models\SalesOrder;
use App\Models\OrderItem;
use App\Models\Driver;
use App\Models\Delivery;
use App\Models\Category;
use App\Models\Banner;
use App\Models\Faq;
use App\Models\Dispute;
use Illuminate\Support\Facades\Hash;

class SmartSupplySeeder extends Seeder
{
    public function run(): void
    {
        // 1. Create Admin User
        $admin = User::create([
            'name' => 'Admin User',
            'email' => 'admin@smartsupply.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'status' => 'approved',
            'approved_at' => now(),
        ]);

        // 2. Create Factory Users with Profiles
        $factory1User = User::create([
            'name' => 'TechManufacturing Ltd',
            'email' => 'factory1@smartsupply.com',
            'password' => Hash::make('password'),
            'role' => 'factory',
            'status' => 'approved',
            'approved_at' => now(),
            'approved_by' => $admin->id,
        ]);

        $factory1 = Factory::create([
            'company_name' => 'TechManufacturing Ltd',
            'contact_person' => 'John Smith',
            'email' => 'factory1@smartsupply.com',
            'phone' => '+254712345001',
            'address' => 'Industrial Area, Nairobi',
            'user_id' => $factory1User->id,
        ]);

        FactoryProfile::create([
            'factory_id' => $factory1->id,
            'logo' => null,
            'description' => 'Leading manufacturer of industrial equipment and machinery',
            'operating_hours' => '8:00 AM - 6:00 PM, Mon-Sat',
            'brand_color' => '#1E40AF',
        ]);

        $factory2User = User::create([
            'name' => 'AgriTools Industries',
            'email' => 'factory2@smartsupply.com',
            'password' => Hash::make('password'),
            'role' => 'factory',
            'status' => 'approved',
            'approved_at' => now(),
            'approved_by' => $admin->id,
        ]);

        $factory2 = Factory::create([
            'company_name' => 'AgriTools Industries',
            'contact_person' => 'Mary Johnson',
            'email' => 'factory2@smartsupply.com',
            'phone' => '+254712345002',
            'address' => 'Mombasa Road, Nairobi',
            'user_id' => $factory2User->id,
        ]);

        FactoryProfile::create([
            'factory_id' => $factory2->id,
            'description' => 'Specialized in agricultural equipment and tools',
            'operating_hours' => '7:00 AM - 5:00 PM, Mon-Fri',
            'brand_color' => '#059669',
        ]);

        // 3. Create Retailer Users with Profiles
        $retailer1User = User::create([
            'name' => 'Nairobi Hardware Store',
            'email' => 'retailer1@smartsupply.com',
            'password' => Hash::make('password'),
            'role' => 'retailer',
            'status' => 'approved',
            'approved_at' => now(),
            'approved_by' => $admin->id,
        ]);

        $retailer1 = Retailer::create([
            'company_name' => 'Nairobi Hardware Store',
            'contact_person' => 'Peter Kamau',
            'email' => 'retailer1@smartsupply.com',
            'phone' => '+254712345003',
            'address' => 'Tom Mboya Street, Nairobi',
            'user_id' => $retailer1User->id,
        ]);

        RetailerProfile::create([
            'retailer_id' => $retailer1->id,
            'business_type' => 'Hardware Store',
            'preferred_delivery_method' => 'delivery',
            'delivery_address' => 'Tom Mboya Street, Nairobi CBD',
        ]);

        $retailer2User = User::create([
            'name' => 'Mombasa Supplies Co',
            'email' => 'retailer2@smartsupply.com',
            'password' => Hash::make('password'),
            'role' => 'retailer',
            'status' => 'approved',
            'approved_at' => now(),
            'approved_by' => $admin->id,
        ]);

        $retailer2 = Retailer::create([
            'company_name' => 'Mombasa Supplies Co',
            'contact_person' => 'Sarah Ochieng',
            'email' => 'retailer2@smartsupply.com',
            'phone' => '+254712345004',
            'address' => 'Moi Avenue, Mombasa',
            'user_id' => $retailer2User->id,
        ]);

        RetailerProfile::create([
            'retailer_id' => $retailer2->id,
            'business_type' => 'Wholesale Distributor',
            'preferred_delivery_method' => 'both',
            'delivery_address' => 'Moi Avenue, Mombasa',
        ]);

        // 4. Create Driver Users
        $driver1User = User::create([
            'name' => 'James Mwangi',
            'email' => 'driver1@smartsupply.com',
            'password' => Hash::make('password'),
            'role' => 'driver',
            'status' => 'approved',
            'approved_at' => now(),
            'approved_by' => $admin->id,
        ]);

        $driver1 = Driver::create([
            'user_id' => $driver1User->id,
            'vehicle_type' => 'Truck',
            'license_number' => 'DL-12345-KE',
            'is_available' => true,
            'current_location' => 'Nairobi CBD',
        ]);

        $driver2User = User::create([
            'name' => 'David Otieno',
            'email' => 'driver2@smartsupply.com',
            'password' => Hash::make('password'),
            'role' => 'driver',
            'status' => 'approved',
            'approved_at' => now(),
            'approved_by' => $admin->id,
        ]);

        $driver2 = Driver::create([
            'user_id' => $driver2User->id,
            'vehicle_type' => 'Van',
            'license_number' => 'DL-67890-KE',
            'is_available' => true,
            'current_location' => 'Industrial Area',
        ]);

        // 5. Create Categories
        $categories = [
            ['name' => 'Heavy Machinery', 'slug' => 'heavy-machinery', 'description' => 'Industrial heavy machinery and equipment'],
            ['name' => 'Hand Tools', 'slug' => 'hand-tools', 'description' => 'Manual tools for various applications'],
            ['name' => 'Power Tools', 'slug' => 'power-tools', 'description' => 'Electric and battery-powered tools'],
            ['name' => 'Agricultural Equipment', 'slug' => 'agricultural-equipment', 'description' => 'Farming and agricultural tools'],
            ['name' => 'Safety Equipment', 'slug' => 'safety-equipment', 'description' => 'Personal protective equipment'],
        ];

        foreach ($categories as $cat) {
            Category::create($cat + ['is_active' => true]);
        }

        // 6. Create Products
        $products = [
            ['name' => 'Industrial Drill Press', 'description' => 'Heavy-duty drill press for industrial use', 'price' => 45000, 'stock' => 15, 'factory_id' => $factory1->id],
            ['name' => 'Welding Machine 200A', 'description' => 'Professional welding equipment', 'price' => 35000, 'stock' => 20, 'factory_id' => $factory1->id],
            ['name' => 'Angle Grinder Set', 'description' => 'Complete angle grinder with accessories', 'price' => 8500, 'stock' => 50, 'factory_id' => $factory1->id],
            ['name' => 'Hydraulic Jack 5 Ton', 'description' => 'Heavy-duty hydraulic jack', 'price' => 12000, 'stock' => 30, 'factory_id' => $factory1->id],
            ['name' => 'Tractor Plough', 'description' => 'Agricultural plough for tractors', 'price' => 55000, 'stock' => 10, 'factory_id' => $factory2->id],
            ['name' => 'Irrigation Pump', 'description' => 'High-capacity water pump', 'price' => 28000, 'stock' => 25, 'factory_id' => $factory2->id],
            ['name' => 'Seed Planter', 'description' => 'Mechanical seed planting equipment', 'price' => 18000, 'stock' => 18, 'factory_id' => $factory2->id],
            ['name' => 'Harvesting Tools Set', 'description' => 'Complete set of harvesting tools', 'price' => 6500, 'stock' => 40, 'factory_id' => $factory2->id],
        ];

        foreach ($products as $prod) {
            Product::create([
                'name' => $prod['name'],
                'description' => $prod['description'],
                'sku' => 'SKU-' . strtoupper(substr(md5($prod['name']), 0, 8)),
                'category' => 'General',
                'cost_price' => $prod['price'] * 0.7,
                'selling_price' => $prod['price'],
                'stock_quantity' => $prod['stock'],
                'low_stock_threshold' => 10,
            ]);
        }

        // 7. Create Orders
        $order1 = SalesOrder::create([
            'customer_id' => $retailer1->id,
            'order_number' => 'ORD-' . strtoupper(uniqid()),
            'order_date' => now()->subDays(5),
            'status' => 'confirmed',
            'sales_agent_id' => $retailer1User->id,
            'total_amount' => 0,
        ]);

        OrderItem::create([
            'sales_order_id' => $order1->id,
            'product_id' => 1,
            'quantity' => 2,
            'unit_price' => 45000,
            'total_price' => 90000,
        ]);

        OrderItem::create([
            'sales_order_id' => $order1->id,
            'product_id' => 3,
            'quantity' => 5,
            'unit_price' => 8500,
            'total_price' => 42500,
        ]);

        $order1->update(['total_amount' => 132500]);

        $order2 = SalesOrder::create([
            'customer_id' => $retailer2->id,
            'order_number' => 'ORD-' . strtoupper(uniqid()),
            'order_date' => now()->subDays(3),
            'status' => 'shipped',
            'sales_agent_id' => $retailer2User->id,
            'total_amount' => 0,
        ]);

        OrderItem::create([
            'sales_order_id' => $order2->id,
            'product_id' => 5,
            'quantity' => 1,
            'unit_price' => 55000,
            'total_price' => 55000,
        ]);

        OrderItem::create([
            'sales_order_id' => $order2->id,
            'product_id' => 6,
            'quantity' => 2,
            'unit_price' => 28000,
            'total_price' => 56000,
        ]);

        $order2->update(['total_amount' => 111000]);

        // 8. Create Deliveries
        Delivery::create([
            'order_id' => $order1->id,
            'driver_id' => $driver1->id,
            'status' => 'in_transit',
            'price' => 2500,
            'pickup_time' => now()->subDays(1),
            'delivery_time' => now()->addDays(1),
        ]);

        Delivery::create([
            'order_id' => $order2->id,
            'driver_id' => $driver2->id,
            'status' => 'delivered',
            'price' => 3500,
            'pickup_time' => now()->subDays(2),
            'delivery_time' => now()->subDays(1),
        ]);

        // 9. Create Banners
        Banner::create([
            'title' => 'Welcome to SmartSupply',
            'image' => 'banners/welcome.jpg',
            'link' => '/retailer/products',
            'order' => 1,
            'is_active' => true,
        ]);

        Banner::create([
            'title' => 'New Agricultural Equipment',
            'image' => 'banners/agri-promo.jpg',
            'link' => '/retailer/products?category=agricultural',
            'order' => 2,
            'is_active' => true,
        ]);

        // 10. Create FAQs
        $faqs = [
            ['question' => 'How do I place an order?', 'answer' => 'Browse products, add items to cart, and proceed to checkout. You can track your order status in the Orders section.', 'order' => 1],
            ['question' => 'What payment methods are accepted?', 'answer' => 'We accept bank transfers, M-Pesa, and cash on delivery for approved accounts.', 'order' => 2],
            ['question' => 'How long does delivery take?', 'answer' => 'Standard delivery takes 3-5 business days. Express delivery is available for urgent orders.', 'order' => 3],
            ['question' => 'Can I return a product?', 'answer' => 'Yes, products can be returned within 14 days if unused and in original packaging. Contact support to initiate a return.', 'order' => 4],
            ['question' => 'How do I become a verified factory?', 'answer' => 'Register as a factory user and submit your business documents. Admin will review and approve your account.', 'order' => 5],
        ];

        foreach ($faqs as $faq) {
            Faq::create($faq + ['is_active' => true]);
        }

        // 11. Create a Sample Dispute
        Dispute::create([
            'order_id' => $order1->id,
            'retailer_id' => $retailer1User->id,
            'factory_id' => $factory1->id,
            'reason' => 'Product Quality Issue',
            'description' => 'One of the drill presses received has a manufacturing defect. The motor makes unusual noise.',
            'status' => 'open',
        ]);

        echo "✅ SmartSupply sample data created successfully!\n\n";
        echo "Login Credentials:\n";
        echo "==================\n";
        echo "Admin: admin@smartsupply.com / password\n";
        echo "Factory 1: factory1@smartsupply.com / password\n";
        echo "Factory 2: factory2@smartsupply.com / password\n";
        echo "Retailer 1: retailer1@smartsupply.com / password\n";
        echo "Retailer 2: retailer2@smartsupply.com / password\n";
        echo "Driver 1: driver1@smartsupply.com / password\n";
        echo "Driver 2: driver2@smartsupply.com / password\n";
    }
}
