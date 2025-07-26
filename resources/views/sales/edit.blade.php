<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Edit Sale:') }} {{ $sale->bill_no }}
        </h2>
    </x-slot>

    {{-- Re-use the same styles from your create.blade.php --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/choices.js/public/assets/styles/choices.min.css" />
    <style>
        /* (Copy all the <style> content from your sales/create.blade.php here) */
        .form-control { /* ... */ }
        .dark .form-control { /* ... */ }
        /* ... etc ... */
    </style>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">

                    <form id="saleForm" method="POST" action="{{ route('sales.update', $sale) }}">
                        @csrf
                        @method('PATCH')

                        <!-- Customer Information Section -->
                        <div class="border p-4 rounded mb-4 dark:border-gray-700">
                            <h3 class="font-bold text-lg mb-3">Customer Information</h3>
                            <div class="row">
                                <div class="col-md-4">
                                    <label for="phone_search">Search/Change Customer by Phone</label>
                                    <div class="input-group">
                                        <span class="input-group-text">+880</span>
                                        {{-- Pre-fill the search with the current customer's phone --}}
                                        <input type="text" id="phone_search" class="form-control" placeholder="Enter 10-digit number" maxlength="10" value="{{ substr($sale->customer->phone, -10) }}">
                                    </div>
                                </div>
                            </div>
                            <div class="row mt-3">
                                <div class="col-md-4"><label>Customer Name</label><input type="text" id="customer_name" class="form-control" value="{{ $sale->customer->customer_name }}" readonly></div>
                                <div class="col-md-4"><label>Email</label><input type="text" id="email" class="form-control" value="{{ $sale->customer->email }}" readonly></div>
                                <div class="col-md-4"><label>Address</label><input type="text" id="address" class="form-control" value="{{ $sale->customer->address }}" readonly></div>
                            </div>
                        </div>
                        <hr class="my-3 dark:border-gray-700" />

                        {{-- Hidden inputs --}}
                        <input type="hidden" name="customer_id" id="form_customer_id" value="{{ $sale->customer_id }}" required>

                        <div class="mb-3"><label for="remarks">Remarks</label><textarea name="remarks" id="remarks" class="form-control">{{ old('remarks', $sale->remarks) }}</textarea></div>

                        <h3 class="font-bold text-lg mt-5 mb-3">Product Items (Read-Only)</h3>
                        <p class="text-sm text-gray-500 mb-3">Note: To edit items, please delete this sale and create a new one. This ensures stock accuracy.</p>
                        
                        {{-- Display Existing Items (Read-Only) --}}
                        <div id="itemContainer">
                             @foreach($sale->items as $item)
                                <div class="item-row border p-3 mb-3 rounded dark:border-gray-700">
                                    <div class="row align-items-center">
                                        <div class="col-md-3"><label>Product</label><input type="text" class="form-control" value="{{ $item->product->model ?? 'N/A' }}" disabled></div>
                                        <div class="col-md-2"><label>Unit Price</label><input type="text" class="form-control" value="{{ number_format($item->unit_price, 2) }}" disabled></div>
                                        <div class="col-md-1"><label>Quantity</label><input type="text" class="form-control" value="{{ $item->quantity }}" disabled></div>
                                        <div class="col-md-2"><label>Total Price</label><input type="text" class="form-control" value="{{ number_format($item->total_price, 2) }}" disabled></div>
                                        <div class="col-md-1"><label>Warranty</label><input type="text" class="form-control" value="{{ $item->warranty ?? 'N/A' }} Days" disabled></div>
                                        <div class="col-md-3"><label>Serial Numbers</label><input type="text" class="form-control" value="{{ implode(', ', $item->serial_numbers) }}" disabled></div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <div class="totals-section mt-4">
                            <div class="totals-row"><label>Sub Total</label><input type="text" class="form-control" value="{{ number_format($sale->sub_total, 2) }}" readonly></div>
                            <div class="totals-row"><label for="discount">Discount</label><input type="number" id="discount" name="discount" class="form-control" value="{{ old('discount', $sale->discount) }}" min="0"></div>
                            <div class="totals-row"><label>Grand Total</label><input type="text" id="grand_total" class="form-control" value="{{ number_format($sale->grand_total, 2) }}" readonly style="font-weight: bold; font-size: 1.1rem; color: #198754;"></div>
                        </div>

                        <hr class="my-4 dark:border-gray-700">
                        <button type="submit" class="btn btn-success">Update Sale</button>
                        <a href="{{ route('sales.index') }}" class="btn btn-secondary">Cancel</a>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Customer search functionality (same as create blade)
            const phoneSearchInput = document.getElementById('phone_search');
            const formCustomerIdInput = document.getElementById('form_customer_id');
            phoneSearchInput.addEventListener('input', function () {
                const phone = this.value.trim();
                if (phone.length !== 10) return;
                fetch(`{{ url('searchcustomer') }}/${phone}`)
                    .then(response => response.ok ? response.json() : Promise.reject('Customer not found'))
                    .then(customer => {
                        formCustomerIdInput.value = customer.id;
                        document.getElementById('customer_name').value = customer.customer_name;
                        document.getElementById('email').value = customer.email;
                        document.getElementById('address').value = customer.address;
                    }).catch(error => {
                        console.error('Customer Search Error:', error);
                    });
            });

            // Dynamic Grand Total calculation
            const discountInput = document.getElementById('discount');
            const grandTotalDisplay = document.getElementById('grand_total');
            const subTotal = {{ $sale->sub_total }}; // Get subtotal from PHP

            discountInput.addEventListener('input', function() {
                const discount = parseFloat(this.value) || 0;
                const grandTotal = subTotal - discount;
                grandTotalDisplay.value = grandTotal.toFixed(2);
            });
        });
    </script>
    @endpush
</x-app-layout>