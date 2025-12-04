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
                                <p class="text-4xl font-bold text-indigo-600">${{ number_format($product->selling_price, 2) }}</p>
                            </div>

                            <div class="mb-6">
                                <h3 class="text-lg font-semibold text-gray-900 mb-2">Description</h3>
                                <p class="text-gray-700">{{ $product->description }}</p>
                            </div>

                            <div class="mb-6">
                                <div class="flex items-center">
                                    <span class="text-sm text-gray-500 mr-2">Stock:</span>
                                    <span class="px-3 py-1 rounded-full text-sm font-semibold {{ $product->stock_quantity > 10 ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                        {{ $product->stock_quantity }} units available
                                    </span>
                                </div>
                            </div>

                            @if($product->factory)
                                <div class="mb-6 p-4 bg-gray-50 rounded-lg">
                                    <h3 class="text-sm font-semibold text-gray-700 mb-2">Supplied by</h3>
                                    <p class="text-gray-900 font-medium">{{ $product->factory->company_name }}</p>
                                </div>
                            @endif

                            <div class="flex gap-4">
                                <form action="{{ route('cart.add') }}" method="POST" class="flex-1">
                                    @csrf
                                    <input type="hidden" name="product_id" value="{{ $product->id }}">
                                    <div class="flex gap-2 mb-4">
                                        <input type="number" name="quantity" value="1" min="1" max="{{ $product->stock_quantity }}" class="w-24 rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                        <button type="submit" class="flex-1 bg-indigo-600 text-white px-6 py-3 rounded-md hover:bg-indigo-700 font-semibold">
                                            Add to Cart
                                        </button>
                                    </div>
                                </form>
                            </div>

                            <a href="{{ route('retailer.products.index') }}" class="text-indigo-600 hover:text-indigo-800">
                                ← Back to Products
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
