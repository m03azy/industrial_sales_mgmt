<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Customer;
use App\Models\Product;
use App\Models\SalesOrder;
use App\Models\OrderItem;
use Illuminate\Foundation\Testing\RefreshDatabase;

class InvoiceTest extends TestCase
{
    use RefreshDatabase;

    public function test_invoice_page_renders()
    {
        $user = User::factory()->create();
        $customer = Customer::factory()->create();
        $product = Product::factory()->create();

        $order = SalesOrder::create([
            'customer_id' => $customer->id,
            'order_number' => 'TEST-001',
            'order_date' => now()->toDateString(),
            'status' => 'draft',
            'sales_agent_id' => $user->id,
            'total_amount' => 0,
        ]);

        OrderItem::create([
            'sales_order_id' => $order->id,
            'product_id' => $product->id,
            'quantity' => 2,
            'unit_price' => $product->selling_price ?? 10,
            'total_price' => ($product->selling_price ?? 10) * 2,
        ]);

        $order->update(['total_amount' => $order->orderItems->sum('total_price')]);

        $response = $this->actingAs($user)->get(route('orders.invoice', $order));

        $response->assertStatus(200);
        $response->assertSee('Invoice');
        $response->assertSee($order->order_number);
    }

    public function test_invoice_pdf_route_may_be_skipped_if_package_missing()
    {
        $user = User::factory()->create();
        $customer = Customer::factory()->create();
        $product = Product::factory()->create();

        $order = SalesOrder::create([
            'customer_id' => $customer->id,
            'order_number' => 'TEST-002',
            'order_date' => now()->toDateString(),
            'status' => 'draft',
            'sales_agent_id' => $user->id,
            'total_amount' => 0,
        ]);

        OrderItem::create([
            'sales_order_id' => $order->id,
            'product_id' => $product->id,
            'quantity' => 1,
            'unit_price' => $product->selling_price ?? 10,
            'total_price' => ($product->selling_price ?? 10) * 1,
        ]);

        $order->update(['total_amount' => $order->orderItems->sum('total_price')]);

        if (!class_exists(\Barryvdh\DomPDF\Facade\Pdf::class)) {
            $this->markTestSkipped('Dompdf not installed; skip PDF download test. Run composer require barryvdh/laravel-dompdf to enable.');
        }

        $response = $this->actingAs($user)->get(route('orders.invoice.pdf', $order));
        $response->assertStatus(200);
        $this->assertStringContainsString('application/pdf', $response->headers->get('content-type'));
    }
}
