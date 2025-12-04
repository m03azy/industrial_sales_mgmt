<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Role;
use App\Models\Supplier;
use App\Models\Customer;

class DashboardAccessTest extends TestCase
{
    use RefreshDatabase;

    public function test_supplier_user_can_access_supplier_dashboard()
    {
        $role = Role::create(['name' => 'supplier', 'label' => 'Supplier']);
        $supplier = Supplier::create(['company_name' => 'S1', 'contact_person' => 'P', 'email' => 's1@example.com']);
        $user = User::create(['name' => 'SupplierUser', 'email' => 'sup@example.com', 'password' => bcrypt('password'), 'role_id' => $role->id, 'supplier_id' => $supplier->id]);

        $this->actingAs($user)
            ->get(route('dashboard.supplier'))
            ->assertStatus(200)
            ->assertSee('Supplier Dashboard');
    }

    public function test_customer_user_can_access_customer_dashboard()
    {
        $role = Role::create(['name' => 'customer', 'label' => 'Customer']);
        $user = User::create(['name' => 'CustUser', 'email' => 'cust@example.com', 'password' => bcrypt('password'), 'role_id' => $role->id]);
        $customer = Customer::create(['company_name' => 'C1', 'contact_person' => 'P', 'email' => 'c1@example.com', 'user_id' => $user->id]);

        $this->actingAs($user)
            ->get(route('dashboard.customer'))
            ->assertStatus(200)
            ->assertSee('Customer Dashboard');
    }

    public function test_unauthorized_user_cannot_access_supplier_or_customer_dashboards()
    {
        $user = User::create(['name' => 'Plain', 'email' => 'plain@example.com', 'password' => bcrypt('password')]);

        $this->actingAs($user)
            ->get(route('dashboard.supplier'))
            ->assertStatus(403);

        $this->actingAs($user)
            ->get(route('dashboard.customer'))
            ->assertStatus(403);
    }
}
