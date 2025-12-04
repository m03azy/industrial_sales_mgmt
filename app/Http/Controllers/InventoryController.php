<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class InventoryController extends Controller
{
    public function adjust(Product $product)
    {
        // Customers shouldn't be able to access inventory adjustments
        if (auth()->check() && auth()->user()->hasRole('customer')) {
            abort(403, 'Access denied');
        }

        return view('inventory.adjust', compact('product'));
    }

    public function storeAdjustment(Request $request, Product $product)
    {
        if (auth()->check() && auth()->user()->hasRole('customer')) {
            abort(403, 'Access denied');
        }

        $validated = $request->validate([
            'type' => 'required|in:in,out',
            'quantity' => 'required|integer|min:1',
            'reference' => 'nullable|string',
            'notes' => 'nullable|string',
        ]);

        $product->inventoryTransactions()->create($validated);

        if ($validated['type'] === 'in') {
            $product->increment('stock_quantity', $validated['quantity']);
        } else {
            $product->decrement('stock_quantity', $validated['quantity']);
        }

        return redirect()->route('products.show', $product)->with('success', 'Stock updated.');
    }
}
