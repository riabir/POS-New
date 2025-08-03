<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Create Purchase') }}
        </h2>
    </x-slot>

    {{-- 1. ADDED: CSS for Choices.js (for styling the searchable dropdowns) --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/choices.js/public/assets/styles/choices.min.css" />
    
    {{-- Basic Styling for a clean, organized layout --}}
    <style>
        .form-control {
            display: block;
            width: 100%;
            padding: .375rem .75rem;
            font-size: 1rem;
            font-weight: 400;
            line-height: 1.5;
            color: #212529;
            background-color: #fff;
            background-clip: padding-box;
            border: 1px solid #ced4da;
            border-radius: .25rem;
            transition: border-color .15s ease-in-out, box-shadow .15s ease-in-out;
        }

        .form-control[readonly],
        .form-control[disabled] {
            background-color: #e9ecef;
            opacity: 1;
        }

        /* Reusable Button Components */
        .btn {
            display: inline-block;
            font-weight: 400;
            line-height: 1.5;
            color: #212529;
            text-align: center;
            vertical-align: middle;
            cursor: pointer;
            user-select: none;
            background-color: transparent;
            border: 1px solid transparent;
            padding: .375rem .75rem;
            font-size: 1rem;
            border-radius: .25rem;
            transition: color .15s ease-in-out, background-color .15s ease-in-out, border-color .15s ease-in-out, box-shadow .15s ease-in-out;
        }

        .btn-primary {
            color: #fff;
            background-color: #0d6efd;
            border-color: #0d6efd;
        }

        .btn-success {
            color: #fff;
            background-color: #198754;
            border-color: #198754;
        }

        .btn-danger {
            color: #fff;
            background-color: #dc3545;
            border-color: #dc3545;
        }
        
        /* Totals Section for summary block */
        .totals-section {
            max-width: 320px;
            margin-left: auto;
        }

        .totals-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 0.5rem;
        }

        .totals-row label {
            font-weight: bold;
            margin-right: 1rem;
            white-space: nowrap;
        }

        .totals-row input {
            text-align: right;
        }
    </style>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">

                    <!-- Vendor Information Section -->
                    <div class="border p-4 rounded mb-4">
                        <h3 class="font-bold text-lg mb-3">Vendor Information</h3>
                        <div class="row">
                            <div class="col-md-4">
                                <label for="phone_search">Search Vendor by Phone</label>
                                <div class="input-group">
                                    <span class="input-group-text">+88</span>
                                    <input type="text" id="phone_search" class="form-control" placeholder="Enter 11-digit number" maxlength="11">
                                </div>
                            </div>
                        </div>
                        <div class="row mt-3">
                            <div class="col-md-4">
                                <label>Vendor Name</label>
                                <input type="text" id="vendor_name" class="form-control" placeholder="Auto Fill" readonly>
                            </div>
                            <div class="col-md-4">
                                <label>Email</label>
                                <input type="text" id="email" class="form-control" placeholder="Auto Fill" readonly>
                            </div>
                            <div class="col-md-4">
                                <label>Address</label>
                                <input type="text" id="address" class="form-control" placeholder="Auto Fill" readonly>
                            </div>
                        </div>
                    </div>

                    <hr class="my-5" />

                    <!-- Main Purchase Form -->
                    <form id="purchaseForm" method="POST" action="{{ route('purchases.store') }}">
                        @csrf
                        <input type="hidden" name="vendor_id" id="form_vendor_id" required>
                        <input type="hidden" name="sub_total" id="form_sub_total">
                        <input type="hidden" name="grand_total" id="form_grand_total">

                        <div class="mb-3">
                            <label for="remarks">Remarks</label>
                            <textarea name="remarks" id="remarks" class="form-control" placeholder="Add any relevant notes for this purchase..."></textarea>
                        </div>

                        <h3 class="font-bold text-lg mt-5 mb-3">Product Items</h3>
                        <div id="itemContainer">
                            {{-- Item rows will be dynamically inserted here by JavaScript --}}
                        </div>

                        <button type="button" id="addItemBtn" class="btn btn-primary mt-3">+ Add Item</button>

                        <!-- Totals Section -->
                        <div class="totals-section mt-4">
                            <div class="totals-row">
                                <label for="sub_total">Sub Total</label>
                                <input type="text" id="sub_total" class="form-control" value="0.00" readonly>
                            </div>
                            <div class="totals-row">
                                <label for="discount">Discount</label>
                                <input type="number" id="discount" name="discount" class="form-control" value="0" min="0">
                            </div>
                            <div class="totals-row">
                                <label for="grand_total">Grand Total</label>
                                <input type="text" id="grand_total" class="form-control" value="0.00" readonly style="font-weight: bold; font-size: 1.1rem; color: #198754;">
                            </div>
                        </div>

                        <hr class="my-4">
                        <button type="submit" class="btn btn-success">Create Purchase</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Template for a single dynamic item row -->
    <template id="itemRowTemplate">
        <div class="item-row border p-3 mb-3 rounded">
            <div class="row align-items-center">
                <div class="col-md-3">
                    <label>Product</label>
                    <select class="form-control product-select" data-name="product_id" required></select>
                </div>
                <div class="col-md-2"><label>Unit Price</label><input type="number" step="0.01" class="form-control unit-price" data-name="unit_price" required></div>
                <div class="col-md-1"><label>Quantity</label><input type="number" class="form-control quantity" value="1" min="1" data-name="quantity" required></div>
                <div class="col-md-2"><label>Total Price</label><input type="text" class="form-control total-price" data-name="total_price" readonly></div>
                <div class="col-md-1"><label>Warranty</label><input type="number" class="form-control" value="365" min="0" data-name="warranty" required></div>
                <div class="col-md-2"><label>Serial Numbers</label><div class="serial-numbers"></div></div>
                <div class="col-md-1"><label>Action</label><button type="button" class="btn btn-danger remove-item w-100">X</button></div>
            </div>
        </div>
    </template>

    {{-- Prepare the product data in a clean PHP block --}}
    @php
        $productsForChoices = ($products ?? collect())->map(function($product) {
            return [
                'value' => $product->id,
                'label' => $product->header,
            ];
        })->values()->all();
    @endphp

    @push('scripts')
        {{-- 2. ADDED: The Choices.js JavaScript library --}}
        <script src="https://cdn.jsdelivr.net/npm/choices.js/public/assets/scripts/choices.min.js"></script>

        {{-- Your existing script which is now able to find the 'Choices' class --}}
        <script>
        document.addEventListener('DOMContentLoaded', function() {
            // === 1. GET THE PREPARED DATA ===
            const productsForChoices = @json($productsForChoices);
            const placeholderChoice = {
                value: '',
                label: 'Select a Product',
                selected: true,
                disabled: true,
            };

            // === DOM ELEMENTS & STATE MANAGEMENT ===
            const itemContainer = document.getElementById('itemContainer');
            const addItemBtn = document.getElementById('addItemBtn');
            const discountInput = document.getElementById('discount');
            const phoneSearchInput = document.getElementById('phone_search');
            const formVendorIdInput = document.getElementById('form_vendor_id');
            const choicesInstances = {};

            // === CORE FUNCTIONS ===
            const updateTotals = () => {
                let subTotal = 0;
                itemContainer.querySelectorAll('.total-price').forEach(input => {
                    subTotal += parseFloat(input.value) || 0;
                });
                const discount = parseFloat(discountInput.value) || 0;
                const grandTotal = subTotal - discount;
                document.getElementById('sub_total').value = subTotal.toFixed(2);
                document.getElementById('grand_total').value = grandTotal.toFixed(2);
                document.getElementById('form_sub_total').value = subTotal.toFixed(2);
                document.getElementById('form_grand_total').value = grandTotal.toFixed(2);
            };

            const reindexAllRows = () => {
                itemContainer.querySelectorAll('.item-row').forEach((row, index) => {
                    row.querySelectorAll('[data-name]').forEach(input => {
                        input.name = `items[${index}][${input.dataset.name}]`;
                    });
                    updateSerialFields(row, index);
                    const removeBtn = row.querySelector('.remove-item');
                    if (removeBtn) removeBtn.disabled = (itemContainer.children.length === 1);
                });
            };

            const updateSerialFields = (row, index) => {
                const quantity = parseInt(row.querySelector('.quantity').value) || 0;
                const serialContainer = row.querySelector('.serial-numbers');
                serialContainer.innerHTML = '';
                for (let i = 0; i < quantity; i++) {
                    serialContainer.insertAdjacentHTML('beforeend',
                        `<input type="text" class="form-control mb-1" name="items[${index}][serial_number][]" placeholder="Serial #${i + 1}" required>`
                    );
                }
            };

            const addNewItemRow = () => {
                const templateClone = document.getElementById('itemRowTemplate').content.cloneNode(true);
                const newRow = templateClone.querySelector('.item-row');
                const rowId = `item-row-${Date.now()}`;
                newRow.id = rowId;
                itemContainer.appendChild(templateClone);

                const selectEl = newRow.querySelector('.product-select');
                const newChoice = new Choices(selectEl, {
                    searchEnabled: true,
                    shouldSort: false,
                    itemSelectText: '',
                });

                newChoice.setChoices([placeholderChoice, ...productsForChoices], 'value', 'label', true);
                choicesInstances[rowId] = newChoice;
                reindexAllRows();
                updateTotals();
            };

            // === EVENT LISTENERS ===
            addItemBtn.addEventListener('click', addNewItemRow);
            discountInput.addEventListener('input', updateTotals);
            
            phoneSearchInput.addEventListener('input', function() {
                const phone = this.value.trim();
                formVendorIdInput.value = '';
                document.getElementById('vendor_name').value = '';
                document.getElementById('email').value = '';
                document.getElementById('address').value = '';
                if (phone.length !== 11) return;
                fetch(`{{ url('searchvendor') }}/${phone}`)
                    .then(response => {
                        if (!response.ok) throw new Error('Vendor not found');
                        return response.json();
                    })
                    .then(vendor => {
                        formVendorIdInput.value = vendor.id;
                        document.getElementById('vendor_name').value = vendor.vendor_name;
                        document.getElementById('email').value = vendor.email;
                        document.getElementById('address').value = vendor.address;
                    })
                    .catch(error => {
                        console.error('Vendor Search Error:', error);
                        document.getElementById('vendor_name').value = 'Vendor not found.';
                    });
            });

            itemContainer.addEventListener('input', function(e) {
                const row = e.target.closest('.item-row');
                if (!row) return;

                if (e.target.classList.contains('unit-price') || e.target.classList.contains('quantity')) {
                    const unitPrice = parseFloat(row.querySelector('.unit-price').value) || 0;
                    const quantity = parseInt(row.querySelector('.quantity').value) || 0;
                    row.querySelector('.total-price').value = (unitPrice * quantity).toFixed(2);
                    if (e.target.classList.contains('quantity')) {
                        const index = Array.from(itemContainer.children).indexOf(row);
                        updateSerialFields(row, index);
                    }
                    updateTotals();
                }
            });

            itemContainer.addEventListener('click', function(e) {
                if (e.target.classList.contains('remove-item')) {
                    const rowToRemove = e.target.closest('.item-row');
                    if (rowToRemove && itemContainer.children.length > 1) {
                        if (choicesInstances[rowToRemove.id]) {
                            choicesInstances[rowToRemove.id].destroy();
                            delete choicesInstances[rowToRemove.id];
                        }
                        rowToRemove.remove();
                        reindexAllRows();
                        updateTotals();
                    }
                }
            });

            // --- INITIALIZE ---
            addNewItemRow();
        });
        </script>
    @endpush
</x-app-layout>