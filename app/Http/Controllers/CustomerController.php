<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function index(Request $request)
    {
        $query = Customer::query();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where('company_name', 'like', "%{$search}%")
                  ->orWhere('contact_person', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
        }

        $customers = $query->paginate(10)->withQueryString();
        return view('customers.index', compact('customers'));
    }

    public function create()
    {
        // Prevent customers themselves from creating other customers
        if (auth()->check() && auth()->user()->hasRole('customer')) {
            abort(403, 'Access denied');
        }

        return view('customers.create');
    }

    public function store(Request $request)
    {
        if (auth()->check() && auth()->user()->hasRole('customer')) {
            abort(403, 'Access denied');
        }

        $validated = $request->validate([
            'company_name' => 'required',
            'contact_person' => 'required',
            'email' => 'required|email|unique:customers',
            'phone' => 'nullable',
            'address' => 'nullable',
        ]);

        Customer::create($validated);

        return redirect()->route('customers.index')->with('success', 'Customer created successfully.');
    }

    public function show(Customer $customer)
    {
        return view('customers.show', compact('customer'));
    }

    public function edit(Customer $customer)
    {
        if (auth()->check() && auth()->user()->hasRole('customer')) {
            abort(403, 'Access denied');
        }

        return view('customers.edit', compact('customer'));
    }

    public function update(Request $request, Customer $customer)
    {
        if (auth()->check() && auth()->user()->hasRole('customer')) {
            abort(403, 'Access denied');
        }

        $validated = $request->validate([
            'company_name' => 'required',
            'contact_person' => 'required',
            'email' => 'required|email|unique:customers,email,' . $customer->id,
            'phone' => 'nullable',
            'address' => 'nullable',
        ]);

        $customer->update($validated);

        return redirect()->route('customers.index')->with('success', 'Customer updated successfully.');
    }

    public function destroy(Customer $customer)
    {
        if (auth()->check() && auth()->user()->hasRole('customer')) {
            abort(403, 'Access denied');
        }

        $customer->delete();
        return redirect()->route('customers.index')->with('success', 'Customer deleted successfully.');
    }
}
