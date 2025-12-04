<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ __('New Purchase Order') }}</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm sm:rounded-lg p-6">
                <form method="POST" action="{{ route('purchase-orders.store') }}">
                    @csrf

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700">{{ __('Supplier') }}</label>
                        <select name="supplier_id" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                            <option value="">Select supplier</option>
                            @foreach($suppliers as $s)
                                <option value="{{ $s->id }}">{{ $s->company_name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700">{{ __('Order Date') }}</label>
                        <input type="date" name="order_date" value="{{ old('order_date', date('Y-m-d')) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>
                    </div>

                    <p class="text-sm text-gray-600 mb-2">Items</p>
                    <div id="po-items" class="space-y-3 mb-4"></div>
                    <div class="flex gap-2 mb-6">
                        <button type="button" id="po-add-item" class="inline-flex items-center px-3 py-2 bg-gray-200 rounded text-sm">+ Add Item</button>
                        <button type="button" id="po-clear" class="inline-flex items-center px-3 py-2 bg-gray-100 rounded text-sm">Clear</button>
                    </div>

                    <div class="flex items-center justify-end gap-4">
                        <a href="{{ route('purchase-orders.index') }}" class="text-gray-600">Cancel</a>
                        <button type="submit" class="bg-indigo-600 text-white px-4 py-2 rounded">Create PO</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <template id="po-item-template">
        <div class="grid grid-cols-12 gap-3 items-center">
            <div class="col-span-6">
                <select data-product class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>
                    <option value="">Select product</option>
                    @foreach($products as $product)
                        <option value="{{ $product->id }}">{{ $product->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-span-3">
                <input data-qty type="number" min="1" value="1" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>
            </div>
            <div class="col-span-2">
                <div data-unit class="mt-1 text-sm text-gray-700">—</div>
            </div>
            <div class="col-span-1 flex items-end">
                <button type="button" class="po-remove inline-flex items-center px-2 py-1 bg-red-50 text-red-600 rounded">✕</button>
            </div>
        </div>
    </template>

    <script>
        (function(){
            const products = @json($products->map(function($p){ return ['id'=>$p->id,'price'=>$p->cost_price ?? 0]; }));
            const list = document.getElementById('po-items');
            const tpl = document.getElementById('po-item-template');

            function add(prefill={}){
                const c = tpl.content.cloneNode(true);
                const sel = c.querySelector('[data-product]');
                const qty = c.querySelector('[data-qty]');
                const unit = c.querySelector('[data-unit]');
                const rem = c.querySelector('.po-remove');

                if(prefill.product_id) sel.value = prefill.product_id;
                if(prefill.quantity) qty.value = prefill.quantity;

                function updateUnit(){
                    const p = products.find(x=>String(x.id)===String(sel.value));
                    unit.textContent = p ? ('$'+Number(p.price).toFixed(2)) : '—';
                }
                sel.addEventListener('change', updateUnit);
                rem.addEventListener('click', (e)=> e.target.closest('div').remove());

                const idx = list.children.length;
                const hiddenP = document.createElement('input'); hiddenP.type='hidden'; hiddenP.name=`items[${idx}][product_id]`;
                const hiddenQ = document.createElement('input'); hiddenQ.type='hidden'; hiddenQ.name=`items[${idx}][quantity]`;

                sel.addEventListener('change', ()=> hiddenP.value = sel.value);
                qty.addEventListener('input', ()=> hiddenQ.value = qty.value);

                const wrapper = document.createElement('div');
                wrapper.appendChild(c);
                wrapper.appendChild(hiddenP);
                wrapper.appendChild(hiddenQ);
                list.appendChild(wrapper);
                updateUnit();
            }

            document.getElementById('po-add-item').addEventListener('click', ()=> add());
            document.getElementById('po-clear').addEventListener('click', ()=> list.innerHTML='');
            add();
        })();
    </script>
</x-app-layout>
