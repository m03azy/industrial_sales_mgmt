<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Supplier;
use App\Models\Product;
use App\Models\PurchaseOrder;

class PurchaseOrderTest extends TestCase
{
    use RefreshDatabase;

    public function test_purchase_orders_index_shows()
    {
    $supplier = Supplier::create(['company_name' => 'Test Supplier']);
    PurchaseOrder::create(['supplier_id' => $supplier->id, 'order_number' => 'PO-TEST-1']);

    $this->actingAs($this->createAdminUser())
            ->get(route('purchase-orders.index'))
            ->assertStatus(200)
            ->assertSee('Purchase Orders');
    }

    public function test_can_create_purchase_order()
    {
        $this->withoutExceptionHandling();
    $supplier = Supplier::create(['company_name' => 'Test Supplier']);
    $product = Product::create(['sku' => 'WGT-001', 'name' => 'Widget', 'cost_price' => 10, 'selling_price' => 15, 'stock_quantity' => 0]);

        $response = $this->actingAs($this->createAdminUser())->post(route('purchase-orders.store'), [
            'supplier_id' => $supplier->id,
            'order_date' => now()->toDateString(),
            'items' => [
                ['product_id' => $product->id, 'quantity' => 2],
            ],
        ]);

        $response->assertRedirect(route('purchase-orders.index'));
        $this->assertDatabaseHas('purchase_orders', ['supplier_id' => $supplier->id]);
        $this->assertDatabaseHas('purchase_order_items', ['product_id' => $product->id]);
    }

    public function test_show_purchase_order()
    {
    $supplier = Supplier::create(['company_name' => 'Test Supplier']);
    $po = PurchaseOrder::create(['supplier_id' => $supplier->id, 'order_number' => 'PO-TEST-2']);

        $this->actingAs($this->createAdminUser())
            ->get(route('purchase-orders.show', $po))
            ->assertStatus(200)
            ->assertSee($po->order_number);
    }

    // Helper to create an admin user quickly
    protected function createAdminUser()
    {
        $user = \App\Models\User::factory()->create();
        // give role if you have roles; otherwise return user
        return $user;
    }
}
