<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Banner;
use App\Models\Faq;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ContentController extends Controller
{
    public function index()
    {
        return view('admin.content.index');
    }

    // Categories
    public function categories()
    {
        $categories = Category::latest()->paginate(10);
        return view('admin.content.categories', compact('categories'));
    }

    public function storeCategory(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'image' => 'nullable|image|max:2048',
        ]);

        $path = $request->file('image') ? $request->file('image')->store('categories', 'public') : null;

        Category::create([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'description' => $request->description,
            'image' => $path,
            'is_active' => $request->has('is_active'),
        ]);

        return back()->with('success', 'Category created successfully.');
    }

    // Banners
    public function banners()
    {
        $banners = Banner::orderBy('order')->paginate(10);
        return view('admin.content.banners', compact('banners'));
    }

    public function storeBanner(Request $request)
    {
        $request->validate([
            'image' => 'required|image|max:2048',
            'link' => 'nullable|url',
        ]);

        $path = $request->file('image')->store('banners', 'public');

        Banner::create([
            'title' => $request->title,
            'image' => $path,
            'link' => $request->link,
            'order' => $request->order ?? 0,
            'is_active' => $request->has('is_active'),
        ]);

        return back()->with('success', 'Banner created successfully.');
    }

    // FAQs
    public function faqs()
    {
        $faqs = Faq::orderBy('order')->paginate(10);
        return view('admin.content.faqs', compact('faqs'));
    }

    public function storeFaq(Request $request)
    {
        $request->validate([
            'question' => 'required|string',
            'answer' => 'required|string',
        ]);

        Faq::create([
            'question' => $request->question,
            'answer' => $request->answer,
            'order' => $request->order ?? 0,
            'is_active' => $request->has('is_active'),
        ]);

        return back()->with('success', 'FAQ created successfully.');
    }
}
