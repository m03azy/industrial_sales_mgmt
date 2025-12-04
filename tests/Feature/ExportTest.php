<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ExportTest extends TestCase
{
    use RefreshDatabase;

    public function test_products_export_streams_csv()
    {
        // create an authenticated user
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get('/export/products');

        $response->assertStatus(200);
        $this->assertStringContainsString('text/csv', $response->headers->get('content-type'));
    }

    public function test_customers_export_streams_csv()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get('/export/customers');

        $response->assertStatus(200);
        $this->assertStringContainsString('text/csv', $response->headers->get('content-type'));
    }
}
