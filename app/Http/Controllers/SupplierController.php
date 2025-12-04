<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use Illuminate\Http\Request;

class SupplierController extends Controller
{
    public function index()
    {
        // Customers should not be able to view suppliers list
        if (auth()->check() && auth()->user()->hasRole('customer')) {
            abort(403, 'Access denied');
        }

        $suppliers = Supplier::orderBy('company_name')->paginate(15);
        return view('suppliers.index', compact('suppliers'));
    }

    public function create()
    {
        if (auth()->check() && auth()->user()->hasRole('customer')) {
            abort(403, 'Access denied');
        }

        return view('suppliers.create');
    }

    public function store(Request $request)
    {
        if (auth()->check() && auth()->user()->hasRole('customer')) {
            abort(403, 'Access denied');
        }

        $data = $request->validate([
            'company_name' => 'required|string|max:255',
            'contact_person' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:50',
            'address' => 'nullable|string',
        ]);

        Supplier::create($data);

        return redirect()->route('suppliers.index')->with('success', 'Supplier created.');
    }

    public function show(Supplier $supplier)
    {
        if (auth()->check() && auth()->user()->hasRole('customer')) {
            abort(403, 'Access denied');
        }

        return view('suppliers.show', compact('supplier'));
    }

    public function edit(Supplier $supplier)
    {
        if (auth()->check() && auth()->user()->hasRole('customer')) {
            abort(403, 'Access denied');
        }

        return view('suppliers.edit', compact('supplier'));
    }

    public function update(Request $request, Supplier $supplier)
    {
        if (auth()->check() && auth()->user()->hasRole('customer')) {
            abort(403, 'Access denied');
        }

        $data = $request->validate([
            'company_name' => 'required|string|max:255',
            'contact_person' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:50',
            'address' => 'nullable|string',
        ]);

        $supplier->update($data);

        return redirect()->route('suppliers.show', $supplier)->with('success', 'Supplier updated.');
    }

    public function destroy(Supplier $supplier)
    {
        if (auth()->check() && auth()->user()->hasRole('customer')) {
            abort(403, 'Access denied');
        }

        $supplier->delete();
        return redirect()->route('suppliers.index')->with('success', 'Supplier removed.');
    }
}

