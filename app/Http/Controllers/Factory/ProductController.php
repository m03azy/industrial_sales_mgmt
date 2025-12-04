<?php

namespace App\Http\Controllers\Factory;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function index()
    {
        $factory = auth()->user()->factory;
        
        if (!$factory) {
            return redirect()->route('dashboard')->with('error', 'No factory profile found.');
        }

        $products = Product::where('factory_id', $factory->id)
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('factory.products.index', compact('products'));
    }

    public function create()
    {
        return view('factory.products.create');
    }

    public function store(Request $request)
    {
        $factory = auth()->user()->factory;
        
        if (!$factory) {
            return redirect()->route('dashboard')->with('error', 'No factory profile found.');
        }

        $validated = $request->validate([
            'sku' => 'required|unique:products',
            'name' => 'required',
            'description' => 'nullable',
            'cost_price' => 'required|numeric|min:0',
            'selling_price' => 'required|numeric|min:0',
            'stock_quantity' => 'required|integer|min:0',
            'low_stock_threshold' => 'required|integer|min:0',
            'category' => 'nullable',
            'image' => 'nullable|image|max:2048',
        ]);

        $validated['factory_id'] = $factory->id;

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $filename = time() . '_' . $image->getClientOriginalName();
            $path = $image->storeAs('products', $filename, 'public');
            $validated['image'] = $path;
        }

        Product::create($validated);

        return redirect()->route('factory.products.index')
            ->with('success', 'Product created successfully.');
    }

    public function show(Product $product)
    {
        $factory = auth()->user()->factory;
        
        if ($product->factory_id !== $factory->id) {
            abort(403, 'Unauthorized access to this product.');
        }

        return view('factory.products.show', compact('product'));
    }

    public function edit(Product $product)
    {
        $factory = auth()->user()->factory;
        
        if ($product->factory_id !== $factory->id) {
            abort(403, 'Unauthorized access to this product.');
        }

        return view('factory.products.edit', compact('product'));
    }

    public function update(Request $request, Product $product)
    {
        $factory = auth()->user()->factory;
        
        if ($product->factory_id !== $factory->id) {
            abort(403, 'Unauthorized access to this product.');
        }

        $validated = $request->validate([
            'sku' => 'required|unique:products,sku,' . $product->id,
            'name' => 'required',
            'description' => 'nullable',
            'cost_price' => 'required|numeric|min:0',
            'selling_price' => 'required|numeric|min:0',
            'stock_quantity' => 'required|integer|min:0',
            'low_stock_threshold' => 'required|integer|min:0',
            'category' => 'nullable',
            'image' => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('image')) {
            // Delete old image
            if ($product->image) {
                Storage::disk('public')->delete($product->image);
            }
            
            $image = $request->file('image');
            $filename = time() . '_' . $image->getClientOriginalName();
            $path = $image->storeAs('products', $filename, 'public');
            $validated['image'] = $path;
        }

        $product->update($validated);

        return redirect()->route('factory.products.index')
            ->with('success', 'Product updated successfully.');
    }

    public function destroy(Product $product)
    {
        $factory = auth()->user()->factory;
        
        if ($product->factory_id !== $factory->id) {
            abort(403, 'Unauthorized access to this product.');
        }

        if ($product->image) {
            Storage::disk('public')->delete($product->image);
        }

        $product->delete();

        return redirect()->route('factory.products.index')
            ->with('success', 'Product deleted successfully.');
    }
}
