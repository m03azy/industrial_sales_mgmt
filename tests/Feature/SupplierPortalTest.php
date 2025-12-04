<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Supplier;
use App\Models\Product;
use App\Models\PurchaseOrder;
use App\Models\PurchaseOrderItem;

class SupplierPortalTest extends TestCase
{
    use RefreshDatabase;

    public function test_supplier_orders_index_and_show_and_actions()
    {
        // create supplier and user linked to it
        $supplier = Supplier::create([
            'company_name' => 'Test Supplier',
            'contact_person' => 'Jane',
            'email' => 'supplier@example.com',
        ]);

        $user = User::create([
            'name' => 'Supplier User',
            'email' => 'supplieruser@example.com',
            'password' => bcrypt('password'),
            'supplier_id' => $supplier->id,
        ]);

        // create product and PO
        $product = Product::create(['sku' => 'TST-001', 'name' => 'Thing', 'cost_price' => 5, 'selling_price' => 10, 'stock_quantity' => 0]);

        $po = PurchaseOrder::create([
            'supplier_id' => $supplier->id,
            'order_number' => 'PO-TEST-001',
            'order_date' => now()->toDateString(),
            'status' => 'ordered',
            'total_amount' => 5,
        ]);

        PurchaseOrderItem::create([
            'purchase_order_id' => $po->id,
            'product_id' => $product->id,
            'quantity' => 2,
            'unit_price' => 5,
            'total_price' => 10,
        ]);

        // index
        $this->actingAs($user)
            ->get(route('supplier.orders.index'))
            ->assertStatus(200)
            ->assertSee('Your Purchase Orders')
            ->assertSee('PO-TEST-001');

        // show
        $this->actingAs($user)
            ->get(route('supplier.orders.show', $po))
            ->assertStatus(200)
            ->assertSee('Purchase Order')
            ->assertSee('PO-TEST-001');

        // mark shipped
        $this->actingAs($user)
            ->post(route('supplier.orders.ship', $po))
            ->assertRedirect();

        $this->assertDatabaseHas('purchase_orders', ['id' => $po->id, 'status' => 'shipped']);

        // mark delivered (should increment stock)
        $this->actingAs($user)
            ->post(route('supplier.orders.deliver', $po))
            ->assertRedirect();

        $this->assertDatabaseHas('purchase_orders', ['id' => $po->id, 'status' => 'received']);
        $this->assertDatabaseHas('products', ['id' => $product->id, 'stock_quantity' => 2]);
    }
}
