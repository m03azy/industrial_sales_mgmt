<x-app-layout>
    @php
        $routePrefix = auth()->user()->role === 'admin' ? 'admin.orders' : 'retailer.orders';
    @endphp
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Create Order') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <form action="{{ route($routePrefix . '.store') }}" method="POST">
                        @csrf

                        <div class="mb-4">
                            <label for="customer_id" class="block text-sm font-medium text-gray-700">Customer *</label>
                            <select name="customer_id" id="customer_id" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                <option value="">Select customer</option>
                                @foreach($customers ?? $retailers as $customer)
                                    <option value="{{ $customer->id }}">{{ $customer->company_name }}</option>
                                @endforeach
                            </select>
                            @error('customer_id')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                        </div>

                        <div class="mb-4">
                            <label for="order_date" class="block text-sm font-medium text-gray-700">Order Date *</label>
                            <input type="date" name="order_date" id="order_date" value="{{ old('order_date', date('Y-m-d')) }}" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            @error('order_date')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                        </div>

                        <p class="text-sm text-gray-600 mb-2">Items — add one or more products to the order</p>

                        <div id="items-list" class="space-y-3 mb-4">
                            {{-- existing old input or one default row will be rendered by JS on load --}}
                        </div>

                        <div class="flex gap-2 mb-6">
                            <button type="button" id="add-item" class="inline-flex items-center px-3 py-2 bg-gray-200 rounded text-sm">+ Add Item</button>
                            <button type="button" id="clear-items" class="inline-flex items-center px-3 py-2 bg-gray-100 rounded text-sm">Clear</button>
                        </div>

                        <div class="flex items-center justify-end gap-4">
                            <a href="{{ route($routePrefix . '.index') }}" class="text-gray-600 hover:text-gray-900">Cancel</a>
                            <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded">Create Order</button>
                        </div>

                        <template id="item-template">
                            <div class="grid grid-cols-12 gap-3 items-center">
                                <div class="col-span-6">
                                    <label class="block text-sm font-medium text-gray-700">Product *</label>
                                    <select data-name-product class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                                        <option value="">Select product</option>
                                        @foreach($products as $product)
                                            <option value="{{ $product->id }}">{{ $product->name }} ({{ $product->stock_quantity }} available)</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-span-3">
                                    <label class="block text-sm font-medium text-gray-700">Quantity *</label>
                                    <input data-name-quantity type="number" min="1" value="1" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                                </div>

                                <div class="col-span-2">
                                    <label class="block text-sm font-medium text-gray-700">Unit Price</label>
                                    <div class="mt-1 text-sm text-gray-700" data-name-unit-price>—</div>
                                </div>

                                <div class="col-span-1 flex items-end">
                                    <button type="button" class="remove-item inline-flex items-center px-2 py-1 bg-red-50 text-red-600 rounded">✕</button>
                                </div>
                            </div>
                        </template>

                        @php
                            // Prepare a JS-safe products array to avoid Blade/PHP parsing issues with inline closures
                            $products_js = $products->map(function($p){
                                return [
                                    'id' => $p->id,
                                    'name' => $p->name,
                                    'price' => $p->selling_price,
                                    'stock' => $p->stock_quantity,
                                ];
                            })->toArray();
                        @endphp

                        <script>
                            (function(){
                                const products = @json($products_js);
                                const itemsList = document.getElementById('items-list');
                                const template = document.getElementById('item-template');

                                function addItem(prefill = {}){
                                    const clone = template.content.cloneNode(true);
                                    const select = clone.querySelector('select[data-name-product]');
                                    const qty = clone.querySelector('input[data-name-quantity]');
                                    const unitPriceEl = clone.querySelector('[data-name-unit-price]');
                                    const removeBtn = clone.querySelector('.remove-item');

                                    // set values if provided
                                    if(prefill.product_id) select.value = prefill.product_id;
                                    if(prefill.quantity) qty.value = prefill.quantity;

                                    // update unit price when product changes
                                    function updatePrice(){
                                        const pid = select.value;
                                        const p = products.find(x=>String(x.id)===String(pid));
                                        unitPriceEl.textContent = p ? ('$'+(p.price).toFixed(2)) : '—';
                                    }

                                    select.addEventListener('change', updatePrice);
                                    qty.addEventListener('input', ()=>{});
                                    removeBtn.addEventListener('click', (e)=>{
                                        e.target.closest('.grid').remove();
                                    });

                                    // append hidden fields with proper names based on current index
                                    const index = itemsList.children.length;
                                    const hiddenProduct = document.createElement('input');
                                    hiddenProduct.type = 'hidden';
                                    hiddenProduct.name = `items[${index}][product_id]`;
                                    hiddenProduct.value = select.value || '';

                                    const hiddenQty = document.createElement('input');
                                    hiddenQty.type = 'hidden';
                                    hiddenQty.name = `items[${index}][quantity]`;
                                    hiddenQty.value = qty.value || 1;

                                    // sync visible inputs to hidden before submit
                                    const sync = ()=>{
                                        hiddenProduct.value = select.value;
                                        hiddenQty.value = qty.value;
                                    };

                                    select.addEventListener('change', sync);
                                    qty.addEventListener('input', sync);

                                    const wrapper = document.createElement('div');
                                    wrapper.appendChild(clone);
                                    wrapper.appendChild(hiddenProduct);
                                    wrapper.appendChild(hiddenQty);

                                    itemsList.appendChild(wrapper);
                                    updatePrice();
                                }

                                document.getElementById('add-item').addEventListener('click', ()=> addItem());
                                document.getElementById('clear-items').addEventListener('click', ()=> itemsList.innerHTML='');

                                // If old input exists (validation) reconstruct rows
                                const old = @json(old('items', []));
                                if(old && old.length){
                                    old.forEach(function(o){ addItem(o); });
                                } else {
                                    addItem();
                                }
                            })();
                        </script>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
