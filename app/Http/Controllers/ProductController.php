<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Product::query();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where('name', 'like', "%{$search}%")
                  ->orWhere('sku', 'like', "%{$search}%")
                  ->orWhere('category', 'like', "%{$search}%");
        }

        $products = $query->paginate(10)->withQueryString();
        return view('products.index', compact('products'));
    }

    public function create()
    {
        // Customers should not be able to create products
        if (auth()->check() && auth()->user()->hasRole('customer')) {
            abort(403, 'Access denied');
        }

        return view('products.create');
    }

    public function store(Request $request)
    {
        if (auth()->check() && auth()->user()->hasRole('customer')) {
            abort(403, 'Access denied');
        }

        $validated = $request->validate([
            'sku' => 'required|unique:products',
            'name' => 'required',
            'cost_price' => 'required|numeric',
            'selling_price' => 'required|numeric',
            'stock_quantity' => 'integer',
            'image' => 'nullable|image|max:2048',
        ]);

        // handle image upload if present
        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('products', 'public');
            $validated['image'] = $path;
        }

        Product::create($validated);

        // generate a thumbnail (if GD available)
        if (isset($validated['image'])) {
            $full = storage_path('app/public/'.$validated['image']);
            $thumbDir = storage_path('app/public/products/thumbs');
            if (function_exists('imagecreatefromstring')) {
                if (!is_dir($thumbDir)) @mkdir($thumbDir, 0755, true);
                $info = pathinfo($full);
                $thumbPath = $thumbDir.DIRECTORY_SEPARATOR.$info['filename'].'_thumb.'.$info['extension'];
                try {
                    $img = @imagecreatefromstring(file_get_contents($full));
                    if ($img) {
                        $w = imagesx($img);
                        $h = imagesy($img);
                        $tw = 300; $th = (int) (($tw/$w) * $h);
                        $thumb = imagecreatetruecolor($tw, $th);
                        imagecopyresampled($thumb, $img, 0,0,0,0, $tw, $th, $w, $h);
                        imagejpeg($thumb, $thumbPath, 85);
                        imagedestroy($thumb);
                        imagedestroy($img);
                    }
                } catch (\Exception $e) {
                    // ignore thumbnail errors
                }
            }
        }

        return redirect()->route('products.index')->with('success', 'Product created successfully.');
    }

    public function show(Product $product)
    {
        return view('products.show', compact('product'));
    }

    public function edit(Product $product)
    {
        if (auth()->check() && auth()->user()->hasRole('customer')) {
            abort(403, 'Access denied');
        }

        return view('products.edit', compact('product'));
    }

    public function update(Request $request, Product $product)
    {
        if (auth()->check() && auth()->user()->hasRole('customer')) {
            abort(403, 'Access denied');
        }

        $validated = $request->validate([
            'sku' => 'required|unique:products,sku,' . $product->id,
            'name' => 'required',
            'cost_price' => 'required|numeric',
            'selling_price' => 'required|numeric',
            'image' => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('products', 'public');
            $validated['image'] = $path;
        }

        $product->update($validated);

        // generate thumbnail for updated image as well
        if (isset($validated['image'])) {
            $full = storage_path('app/public/'.$validated['image']);
            $thumbDir = storage_path('app/public/products/thumbs');
            if (function_exists('imagecreatefromstring')) {
                if (!is_dir($thumbDir)) @mkdir($thumbDir, 0755, true);
                $info = pathinfo($full);
                $thumbPath = $thumbDir.DIRECTORY_SEPARATOR.$info['filename'].'_thumb.'.$info['extension'];
                try {
                    $img = @imagecreatefromstring(file_get_contents($full));
                    if ($img) {
                        $w = imagesx($img);
                        $h = imagesy($img);
                        $tw = 300; $th = (int) (($tw/$w) * $h);
                        $thumb = imagecreatetruecolor($tw, $th);
                        imagecopyresampled($thumb, $img, 0,0,0,0, $tw, $th, $w, $h);
                        imagejpeg($thumb, $thumbPath, 85);
                        imagedestroy($thumb);
                        imagedestroy($img);
                    }
                } catch (\Exception $e) {
                }
            }
        }

        return redirect()->route('products.index')->with('success', 'Product updated successfully.');
    }

    public function destroy(Product $product)
    {
        if (auth()->check() && auth()->user()->hasRole('customer')) {
            abort(403, 'Access denied');
        }

        $product->delete();
        return redirect()->route('products.index')->with('success', 'Product deleted successfully.');
    }
}
