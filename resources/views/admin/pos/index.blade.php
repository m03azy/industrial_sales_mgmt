<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Point of Sale') }}
        </h2>
    </x-slot>

    <div class="py-6 h-[calc(100vh-65px)] overflow-hidden">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 h-full">
            <div class="flex h-full gap-6">
                
                <!-- Left Side: Product Grid -->
                <div class="w-2/3 flex flex-col h-full">
                    <!-- Search Bar -->
                    <div class="bg-white p-4 rounded-lg shadow mb-4">
                        <div class="relative">
                            <input type="text" id="product-search" class="w-full pl-10 pr-4 py-2 rounded-lg border border-gray-300 focus:outline-none focus:border-indigo-500" placeholder="Search products by name or SKU...">
                            <div class="absolute left-3 top-2.5 text-gray-400">
                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                            </div>
                        </div>
                    </div>

                    <!-- Products Grid -->
                    <div class="bg-white p-4 rounded-lg shadow flex-1 overflow-y-auto">
                        <div id="products-grid" class="grid grid-cols-3 gap-4">
                            @foreach($products as $product)
                                <div class="product-card border rounded-lg p-3 cursor-pointer hover:shadow-md transition-shadow bg-gray-50 hover:bg-white"
                                     onclick="addToCart({{ $product->id }}, '{{ addslashes($product->name) }}', {{ $product->price }}, {{ $product->stock_quantity }})">
                                    <div class="h-24 bg-gray-200 rounded mb-2 flex items-center justify-center text-gray-400">
                                        <svg class="h-8 w-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                    </div>
                                    <h3 class="font-semibold text-sm text-gray-800 truncate">{{ $product->name }}</h3>
                                    <p class="text-xs text-gray-500 mb-1">SKU: {{ $product->sku }}</p>
                                    <div class="flex justify-between items-center">
                                        <span class="font-bold text-indigo-600">${{ number_format($product->price, 2) }}</span>
                                        <span class="text-xs bg-green-100 text-green-800 px-2 py-0.5 rounded-full">{{ $product->stock_quantity }} in stock</span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        <div id="no-results" class="hidden text-center py-10 text-gray-500">
                            No products found.
                        </div>
                    </div>
                </div>

                <!-- Right Side: Cart -->
                <div class="w-1/3 flex flex-col h-full bg-white rounded-lg shadow">
                    <!-- Customer Selection -->
                    <div class="p-4 border-b">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Customer</label>
                        <select id="customer-select" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            <option value="">Select Customer</option>
                            @foreach($customers as $customer)
                                <option value="{{ $customer->id }}">{{ $customer->name }} ({{ $customer->email }})</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Cart Items -->
                    <div class="flex-1 overflow-y-auto p-4">
                        <table class="w-full">
                            <thead class="text-xs text-gray-500 border-b">
                                <tr>
                                    <th class="text-left pb-2">Item</th>
                                    <th class="text-center pb-2">Qty</th>
                                    <th class="text-right pb-2">Total</th>
                                    <th class="w-8 pb-2"></th>
                                </tr>
                            </thead>
                            <tbody id="cart-items-body" class="text-sm">
                                <!-- Cart items will be injected here -->
                                <tr id="empty-cart-msg">
                                    <td colspan="4" class="text-center py-8 text-gray-400">Cart is empty</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <!-- Totals & Checkout -->
                    <div class="p-4 bg-gray-50 border-t">
                        <div class="space-y-2 mb-4">
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600">Subtotal:</span>
                                <span class="font-medium" id="cart-subtotal">$0.00</span>
                            </div>
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600">Tax (18%):</span>
                                <span class="font-medium" id="cart-tax">$0.00</span>
                            </div>
                            <div class="flex justify-between text-lg font-bold border-t pt-2">
                                <span>Total:</span>
                                <span class="text-indigo-600" id="cart-total">$0.00</span>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Payment Method</label>
                            <select id="payment-method" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                                <option value="cash">Cash</option>
                                <option value="card">Card</option>
                                <option value="mpesa">M-Pesa</option>
                                <option value="bank_transfer">Bank Transfer</option>
                            </select>
                        </div>

                        <button onclick="processCheckout()" id="checkout-btn" class="w-full bg-indigo-600 text-white py-3 rounded-lg font-bold hover:bg-indigo-700 transition-colors disabled:opacity-50 disabled:cursor-not-allowed" disabled>
                            Complete Order
                        </button>
                    </div>
                </div>

            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        let cart = [];
        const taxRate = 0.18;

        // Search Functionality
        const searchInput = document.getElementById('product-search');
        let searchTimeout;

        searchInput.addEventListener('input', function() {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(() => {
                const query = this.value;
                fetch(`{{ route('admin.pos.search') }}?query=${query}`)
                    .then(response => response.json())
                    .then(products => updateProductGrid(products));
            }, 300);
        });

        function updateProductGrid(products) {
            const grid = document.getElementById('products-grid');
            const noResults = document.getElementById('no-results');
            
            grid.innerHTML = '';
            
            if (products.length === 0) {
                noResults.classList.remove('hidden');
            } else {
                noResults.classList.add('hidden');
                products.forEach(product => {
                    const card = `
                        <div class="product-card border rounded-lg p-3 cursor-pointer hover:shadow-md transition-shadow bg-gray-50 hover:bg-white"
                             onclick="addToCart(${product.id}, '${product.name.replace(/'/g, "\\'")}', ${product.price}, ${product.stock_quantity})">
                            <div class="h-24 bg-gray-200 rounded mb-2 flex items-center justify-center text-gray-400">
                                <svg class="h-8 w-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                            </div>
                            <h3 class="font-semibold text-sm text-gray-800 truncate">${product.name}</h3>
                            <p class="text-xs text-gray-500 mb-1">SKU: ${product.sku}</p>
                            <div class="flex justify-between items-center">
                                <span class="font-bold text-indigo-600">$${parseFloat(product.price).toFixed(2)}</span>
                                <span class="text-xs bg-green-100 text-green-800 px-2 py-0.5 rounded-full">${product.stock_quantity} in stock</span>
                            </div>
                        </div>
                    `;
                    grid.innerHTML += card;
                });
            }
        }

        // Cart Functionality
        function addToCart(id, name, price, stock) {
            const existingItem = cart.find(item => item.id === id);
            
            if (existingItem) {
                if (existingItem.quantity < stock) {
                    existingItem.quantity++;
                } else {
                    alert('Insufficient stock!');
                    return;
                }
            } else {
                cart.push({ id, name, price, quantity: 1, stock });
            }
            
            renderCart();
        }

        function removeFromCart(id) {
            cart = cart.filter(item => item.id !== id);
            renderCart();
        }

        function updateQuantity(id, change) {
            const item = cart.find(item => item.id === id);
            if (item) {
                const newQty = item.quantity + change;
                if (newQty > 0 && newQty <= item.stock) {
                    item.quantity = newQty;
                } else if (newQty <= 0) {
                    removeFromCart(id);
                    return; // renderCart called in removeFromCart
                } else {
                    alert('Insufficient stock!');
                }
            }
            renderCart();
        }

        function renderCart() {
            const tbody = document.getElementById('cart-items-body');
            const emptyMsg = document.getElementById('empty-cart-msg');
            const checkoutBtn = document.getElementById('checkout-btn');
            
            tbody.innerHTML = '';
            
            if (cart.length === 0) {
                tbody.appendChild(emptyMsg);
                checkoutBtn.disabled = true;
                updateTotals();
                return;
            }

            checkoutBtn.disabled = false;

            cart.forEach(item => {
                const row = document.createElement('tr');
                row.className = 'border-b last:border-0';
                row.innerHTML = `
                    <td class="py-3">
                        <div class="font-medium text-gray-800 truncate w-32">${item.name}</div>
                        <div class="text-xs text-gray-500">$${item.price.toFixed(2)}</div>
                    </td>
                    <td class="py-3 text-center">
                        <div class="flex items-center justify-center space-x-1">
                            <button onclick="updateQuantity(${item.id}, -1)" class="w-5 h-5 rounded bg-gray-200 text-gray-600 hover:bg-gray-300 flex items-center justify-center">-</button>
                            <span class="w-6 text-center">${item.quantity}</span>
                            <button onclick="updateQuantity(${item.id}, 1)" class="w-5 h-5 rounded bg-gray-200 text-gray-600 hover:bg-gray-300 flex items-center justify-center">+</button>
                        </div>
                    </td>
                    <td class="py-3 text-right font-medium">
                        $${(item.price * item.quantity).toFixed(2)}
                    </td>
                    <td class="py-3 text-right">
                        <button onclick="removeFromCart(${item.id})" class="text-red-500 hover:text-red-700">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                        </button>
                    </td>
                `;
                tbody.appendChild(row);
            });

            updateTotals();
        }

        function updateTotals() {
            const subtotal = cart.reduce((sum, item) => sum + (item.price * item.quantity), 0);
            const tax = subtotal * taxRate;
            const total = subtotal + tax;

            document.getElementById('cart-subtotal').textContent = '$' + subtotal.toFixed(2);
            document.getElementById('cart-tax').textContent = '$' + tax.toFixed(2);
            document.getElementById('cart-total').textContent = '$' + total.toFixed(2);
        }

        function processCheckout() {
            const customerId = document.getElementById('customer-select').value;
            const paymentMethod = document.getElementById('payment-method').value;

            if (!customerId) {
                alert('Please select a customer.');
                return;
            }

            if (cart.length === 0) {
                alert('Cart is empty.');
                return;
            }

            const btn = document.getElementById('checkout-btn');
            const originalText = btn.textContent;
            btn.disabled = true;
            btn.textContent = 'Processing...';

            fetch('{{ route('admin.pos.checkout') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({
                    customer_id: customerId,
                    items: cart,
                    payment_method: paymentMethod
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Order completed successfully!');
                    cart = [];
                    renderCart();
                    // Optional: Open invoice in new tab
                    if (confirm('Do you want to view the invoice?')) {
                        window.open(data.invoice_url, '_blank');
                    }
                } else {
                    alert('Error: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred while processing the order.');
            })
            .finally(() => {
                btn.disabled = false;
                btn.textContent = originalText;
            });
        }
    </script>
    @endpush
</x-app-layout>
