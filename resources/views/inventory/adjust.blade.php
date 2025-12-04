<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Adjust Stock: {{ $product->name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="mb-6 p-4 bg-gray-50 rounded-lg">
                        <h3 class="text-lg font-semibold text-gray-900 mb-2">Current Stock Information</h3>
                        <dl class="grid grid-cols-2 gap-4">
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Product</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $product->name }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">SKU</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $product->sku }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Current Stock</dt>
                                <dd class="mt-1 text-lg font-bold text-gray-900">{{ $product->stock_quantity }} units
                                </dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Low Stock Threshold</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $product->low_stock_threshold }} units</dd>
                            </div>
                        </dl>
                    </div>

                    <form action="{{ route('inventory.store', $product) }}" method="POST">
                        @csrf

                        <div class="mb-4">
                            <label for="type" class="block text-sm font-medium text-gray-700">Transaction Type *</label>
                            <select name="type" id="type" required
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                <option value="">Select type...</option>
                                <option value="in" {{ old('type') === 'in' ? 'selected' : '' }}>Stock In (Add)</option>
                                <option value="out" {{ old('type') === 'out' ? 'selected' : '' }}>Stock Out (Remove)
                                </option>
                            </select>
                            @error('type')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="quantity" class="block text-sm font-medium text-gray-700">Quantity *</label>
                            <input type="number" name="quantity" id="quantity" value="{{ old('quantity') }}" min="1"
                                required
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            @error('quantity')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="reference" class="block text-sm font-medium text-gray-700">Reference</label>
                            <input type="text" name="reference" id="reference" value="{{ old('reference') }}"
                                placeholder="e.g., PO-12345, Invoice-789"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            @error('reference')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-6">
                            <label for="notes" class="block text-sm font-medium text-gray-700">Notes</label>
                            <textarea name="notes" id="notes" rows="3"
                                placeholder="Additional information about this transaction..."
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">{{ old('notes') }}</textarea>
                            @error('notes')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="flex items-center justify-end gap-4">
                            <a href="{{ route('products.show', $product) }}"
                                class="text-gray-600 hover:text-gray-900">Cancel</a>
                            <button type="submit"
                                class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                                Adjust Stock
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>