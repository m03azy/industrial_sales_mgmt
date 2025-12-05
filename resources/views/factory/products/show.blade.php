<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ $product->name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <!-- Product Image -->
                        <div>
                            @if($product->image)
                                <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" class="w-full rounded-lg shadow-md">
                            @else
                                <div class="w-full h-96 bg-gray-200 rounded-lg flex items-center justify-center">
                                    <svg class="w-32 h-32 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                                    </svg>
                                </div>
                            @endif
                        </div>

                        <!-- Product Details -->
                        <div>
                            <h1 class="text-3xl font-bold text-gray-900 mb-4">{{ $product->name }}</h1>
                            
                            <div class="mb-4">
                                <span class="text-sm text-gray-500">SKU:</span>
                                <span class="text-sm font-medium text-gray-900">{{ $product->sku }}</span>
                            </div>

                            @if($product->category)
                                <div class="mb-4">
                                    <span class="inline-block bg-indigo-100 text-indigo-800 text-sm px-3 py-1 rounded-full">
                                        {{ $product->category }}
                                    </span>
                                </div>
                            @endif

                            <div class="mb-6">
                                <div class="grid grid-cols-2 gap-4">
                                    <div class="bg-gray-50 p-4 rounded-lg">
                                        <p class="text-sm text-gray-500 mb-1">Cost Price</p>
                                        <p class="text-2xl font-bold text-gray-900">${{ number_format($product->cost_price, 2) }}</p>
                                    </div>
                                    <div class="bg-indigo-50 p-4 rounded-lg">
                                        <p class="text-sm text-indigo-600 mb-1">Selling Price</p>
                                        <p class="text-2xl font-bold text-indigo-600">${{ number_format($product->selling_price, 2) }}</p>
                                    </div>
                                </div>
                            </div>

                            @if($product->description)
                                <div class="mb-6">
                                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Description</h3>
                                    <p class="text-gray-700">{{ $product->description }}</p>
                                </div>
                            @endif

                            <div class="mb-6">
                                <h3 class="text-lg font-semibold text-gray-900 mb-3">Stock Information</h3>
                                <div class="space-y-3">
                                    <div class="flex items-center justify-between">
                                        <span class="text-sm text-gray-500">Current Stock:</span>
                                        <span class="px-3 py-1 rounded-full text-sm font-semibold {{ $product->stock_quantity > $product->low_stock_threshold ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                            {{ $product->stock_quantity }} units
                                        </span>
                                    </div>
                                    <div class="flex items-center justify-between">
                                        <span class="text-sm text-gray-500">Low Stock Threshold:</span>
                                        <span class="text-sm font-medium text-gray-900">{{ $product->low_stock_threshold }} units</span>
                                    </div>
                                    @if($product->stock_quantity <= $product->low_stock_threshold)
                                        <div class="p-3 bg-red-50 border border-red-200 rounded-lg">
                                            <div class="flex items-center gap-2">
                                                <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                                                </svg>
                                                <span class="text-sm font-medium text-red-800">Low Stock Alert!</span>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>

                            <div class="mb-6 p-4 bg-gray-50 rounded-lg">
                                <div class="grid grid-cols-2 gap-4 text-sm">
                                    <div>
                                        <span class="text-gray-500">Created:</span>
                                        <span class="font-medium text-gray-900">{{ $product->created_at->format('M d, Y') }}</span>
                                    </div>
                                    <div>
                                        <span class="text-gray-500">Updated:</span>
                                        <span class="font-medium text-gray-900">{{ $product->updated_at->format('M d, Y') }}</span>
                                    </div>
                                </div>
                            </div>

                            <div class="flex gap-4">
                                <a href="{{ route('factory.products.edit', $product) }}" class="flex-1 bg-indigo-600 text-white px-6 py-3 rounded-md hover:bg-indigo-700 font-semibold text-center">
                                    Edit Product
                                </a>
                                <form action="{{ route('factory.products.destroy', $product) }}" method="POST" class="flex-1" onsubmit="return confirm('Are you sure you want to delete this product?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="w-full bg-red-600 text-white px-6 py-3 rounded-md hover:bg-red-700 font-semibold">
                                        Delete Product
                                    </button>
                                </form>
                            </div>

                            <a href="{{ route('factory.products.index') }}" class="block mt-4 text-indigo-600 hover:text-indigo-800 text-center">
                                ← Back to Products
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
