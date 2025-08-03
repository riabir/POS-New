<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Create Sale') }}
        </h2>
    </x-slot>

    {{-- CSS for Choices.js and custom styles --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/choices.js/public/assets/styles/choices.min.css" />

    <style>
        .form-control { display: block; width: 100%; padding: .375rem .75rem; font-size: 1rem; font-weight: 400; line-height: 1.5; color: #212529; background-color: #fff; background-clip: padding-box; border: 1px solid #ced4da; border-radius: .25rem; transition: border-color .15s ease-in-out, box-shadow .15s ease-in-out; }
        .dark .form-control { background-color: #374151; border-color: #4b5563; color: #f3f4f6; }
        .form-control[readonly], .form-control[disabled] { background-color: #e9ecef; opacity: 1; }
        .dark .form-control[readonly], .dark .form-control[disabled] { background-color: #4b5563; }
        .btn { display: inline-block; font-weight: 400; line-height: 1.5; color: #212529; text-align: center; vertical-align: middle; cursor: pointer; user-select: none; background-color: transparent; border: 1px solid transparent; padding: .375rem .75rem; font-size: 1rem; border-radius: .25rem; transition: all .15s ease-in-out; }
        .btn-primary { color: #fff; background-color: #0d6efd; border-color: #0d6efd; }
        .btn-success { color: #fff; background-color: #198754; border-color: #198754; }
        .btn-danger { color: #fff; background-color: #dc3545; border-color: #dc3545; }
        .totals-section { max-width: 320px; margin-left: auto; }
        .totals-row { display: flex; align-items: center; justify-content: space-between; margin-bottom: 0.5rem; }
        .totals-row label { font-weight: bold; margin-right: 1rem; white-space: nowrap; }
        .totals-row input { text-align: right; }
        .rotate-180 { transform: rotate(180deg); }
        .input-group { display: flex; }
        .input-group-text { padding: .375rem .75rem; background-color: #e9ecef; border: 1px solid #ced4da; border-right: 0; border-radius: .25rem 0 0 .25rem; }
        .dark .input-group-text { background-color: #4b5563; border-color: #6b7280; }
        .row { display: flex; flex-wrap: wrap; margin-right: -15px; margin-left: -15px; }
        .col-md-4 { position: relative; width: 100%; padding-right: 15px; padding-left: 15px; flex: 0 0 33.333333%; max-width: 33.333333%; }
        .col-md-3 { flex: 0 0 25%; max-width: 25%; }
        .col-md-2 { flex: 0 0 16.666667%; max-width: 16.666667%; }
        .col-md-1 { flex: 0 0 8.333333%; max-width: 8.333333%; }
        .align-items-center { align-items: center; }
    </style>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">

                    <form id="saleForm" method="POST" action="{{ route('sales.store') }}">
                        @csrf

                        <!-- Customer Information Section -->
                        <div class="border p-4 rounded mb-4 dark:border-gray-700">
                            <h3 class="font-bold text-lg mb-3">Customer Information</h3>
                            <div class="row">
                                <div class="col-md-4">
                                    <label for="phone_search">Search Customer by Phone</label>
                                    <div class="input-group">
                                        <span class="input-group-text">+88</span>
                                        <input type="text" id="phone_search" class="form-control" placeholder="Enter 11-digit number" maxlength="11">
                                    </div>
                                </div>
                            </div>
                            <div class="row mt-3">
                                <div class="col-md-4"><label>Customer Name</label><input type="text" id="customer_name" class="form-control" placeholder="Auto Fill" readonly></div>
                                <div class="col-md-4"><label>Email</label><input type="text" id="email" class="form-control" placeholder="Auto Fill" readonly></div>
                                <div class="col-md-4"><label>Address</label><input type="text" id="address" class="form-control" placeholder="Auto Fill" readonly></div>
                            </div>
                        </div>
                        <hr class="my-2 dark:border-gray-700" />
                        
                        <!-- Collapsible Commission Section -->
                        <div class="border rounded mb-4 dark:border-gray-700">
                            <div id="commission_toggle_button" class="flex justify-between items-center p-4 cursor-pointer hover:bg-gray-50 dark:hover:bg-gray-700">
                                <h3 class="font-bold text-lg mb-0">Commission / PR (Click to Expand)</h3>
                                <svg id="commission_arrow" class="w-6 h-6 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                            </div>
                            <div id="commission_content" class="p-4 border-t border-gray-200 dark:border-gray-700 hidden">
                                <div class="row">
                                    <div class="col-md-4">
                                        <label for="commission_phone_search">Search Recipient by Phone</label>
                                        <div class="input-group">
                                            <span class="input-group-text">+88</span>
                                            <input type="text" id="commission_phone_search" class="form-control" placeholder="Enter 11-digit number" maxlength="11">
                                        </div>
                                    </div>
                                </div>
                                <div class="row mt-3">
                                    <div class="col-md-4"><label>Recipient Name</label><input type="text" id="commission_recipient_name" class="form-control" placeholder="Auto Fill" readonly></div>
                                    <div class="col-md-4"><label for="commission_amount">Amount</label><input type="number" step="0.01" id="commission_amount" name="commission_amount" class="form-control" placeholder="Enter amount"></div>
                                </div>
                            </div>
                        </div>

                        <hr class="my-3 dark:border-gray-700" />

                        <!-- Hidden inputs -->
                        <input type="hidden" name="customer_id" id="form_customer_id" required>
                        <input type="hidden" name="sub_total" id="form_sub_total">
                        <input type="hidden" name="grand_total" id="form_grand_total">
                        <input type="hidden" name="commission_recipient_id" id="commission_recipient_id">
                        <input type="hidden" name="commission_recipient_type" id="commission_recipient_type">

                        <div class="mb-3"><label for="remarks">Remarks</label><textarea name="remarks" id="remarks" class="form-control" placeholder="Add any relevant notes..."></textarea></div>

                        <h3 class="font-bold text-lg mt-5 mb-3">Product Items</h3>
                        <div id="itemContainer"></div>
                        <button type="button" id="addItemBtn" class="btn btn-primary mt-3">+ Add Item</button>

                        <div class="totals-section mt-4">
                            <div class="totals-row"><label for="sub_total">Sub Total</label><input type="text" id="sub_total" class="form-control" value="0.00" readonly></div>
                            <div class="totals-row"><label for="discount">Discount</label><input type="number" id="discount" name="discount" class="form-control" value="0" min="0"></div>
                            <div class="totals-row"><label for="grand_total">Grand Total</label><input type="text" id="grand_total" class="form-control" value="0.00" readonly style="font-weight: bold; font-size: 1.1rem; color: #198754;"></div>
                        </div>

                        <hr class="my-4 dark:border-gray-700">
                        <button type="submit" class="btn btn-success">Create Sale</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Template for a single dynamic item row -->
    <template id="itemRowTemplate">
        <div class="item-row border p-3 mb-3 rounded dark:border-gray-700">
            <div class="row align-items-center">
                <div class="col-md-3"><label>Product <small class="stock-display text-muted" style="font-weight:normal;"></small></label><select class="form-control product-select" data-name="product_id" required></select></div>
                <div class="col-md-2"><label>Unit Price</label><input type="number" step="0.01" class="form-control unit-price" data-name="unit_price" required></div>
                <div class="col-md-1"><label>Quantity</label><input type="number" class="form-control quantity" value="1" min="1" data-name="quantity" required></div>
                <div class="col-md-2"><label>Total Price</label><input type="text" class="form-control total-price" data-name="total_price" readonly></div>
                <div class="col-md-1"><label>Warranty</label><input type="number" class="form-control warranty-input" value="" min="0" data-name="warranty" placeholder="Days"></div>
                <div class="col-md-2"><label>Serial Numbers</label><div class="serial-numbers"></div></div>
                <div class="col-md-1"><label>Action</label><button type="button" class="btn btn-danger remove-item w-100">X</button></div>
            </div>
        </div>
    </template>

    {{-- ========================================================== --}}
    {{--  CHANGE #1: Pass 'mrp' from the Product to JavaScript      --}}
    {{-- ========================================================== --}}
    @php
        $productsForChoices = ($products ?? collect())->map(function($product) {
            return [
                'value' => $product->id, 
                'label' => $product->header, 
                'customProperties' => [
                    'mrp' => $product->mrp ?? '0.00', 
                    'stock' => $product->stock->quantity ?? '0', 
                    'warranty' => $product->latestPurchaseItem->warranty ?? '0'
                ]
            ];
        })->values()->all();
    @endphp

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/choices.js/public/assets/scripts/choices.min.js"></script>
    <script>window.productsForChoices = @json($productsForChoices);</script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const productsForChoices = window.productsForChoices || [];
            const placeholderChoice = { value: '', label: 'Select a Product', selected: true, disabled: true };
            const itemContainer = document.getElementById('itemContainer');
            const addItemBtn = document.getElementById('addItemBtn');
            const discountInput = document.getElementById('discount');
            const choicesInstances = {};
            const phoneSearchInput = document.getElementById('phone_search');
            const formCustomerIdInput = document.getElementById('form_customer_id');
            const commissionPhoneSearchInput = document.getElementById('commission_phone_search');
            const commissionRecipientIdInput = document.getElementById('commission_recipient_id');
            const commissionRecipientTypeInput = document.getElementById('commission_recipient_type');
            const commissionRecipientNameInput = document.getElementById('commission_recipient_name');
            const commissionAmountInput = document.getElementById('commission_amount');
            const commissionToggleButton = document.getElementById('commission_toggle_button');
            const commissionContent = document.getElementById('commission_content');
            const commissionArrow = document.getElementById('commission_arrow');
            
            commissionToggleButton.addEventListener('click', () => {
                commissionContent.classList.toggle('hidden');
                commissionArrow.classList.toggle('rotate-180');
            });

            const updateTotals = () => { let subTotal = 0; itemContainer.querySelectorAll('.total-price').forEach(input => { subTotal += parseFloat(input.value) || 0; }); const discount = parseFloat(discountInput.value) || 0; const grandTotal = subTotal - discount; document.getElementById('sub_total').value = subTotal.toFixed(2); document.getElementById('grand_total').value = grandTotal.toFixed(2); document.getElementById('form_sub_total').value = subTotal.toFixed(2); document.getElementById('form_grand_total').value = grandTotal.toFixed(2); };
            const reindexAllRows = () => { itemContainer.querySelectorAll('.item-row').forEach((row, index) => { row.querySelectorAll('[data-name]').forEach(input => { input.name = `items[${index}][${input.dataset.name}]`; }); updateSerialFields(row, index); const removeBtn = row.querySelector('.remove-item'); if (removeBtn) removeBtn.disabled = (itemContainer.children.length === 1); }); };
            const updateSerialFields = (row, index) => { const quantity = parseInt(row.querySelector('.quantity').value) || 0; const serialContainer = row.querySelector('.serial-numbers'); serialContainer.innerHTML = ''; for (let i = 0; i < quantity; i++) { serialContainer.insertAdjacentHTML('beforeend', `<input type="text" class="form-control mb-1" name="items[${index}][serial_number][]" placeholder="Serial #${i + 1}" required>`); } };
            
            // ==========================================================
            //  CHANGE #2: Use 'mrp' to set the unit price
            // ==========================================================
            function handleProductChange(event) {
                const selectElement = event.target;
                const row = selectElement.closest('.item-row');
                if (!row) return;
                
                const selectedProductId = parseInt(selectElement.value);
                const selectedProductData = productsForChoices.find(p => p.value === selectedProductId);
                if (!selectedProductData) return;

                const props = selectedProductData.customProperties;
                
                // Use 'mrp' as the unit price
                row.querySelector('.unit-price').value = props.mrp; 
                
                row.querySelector('.warranty-input').value = props.warranty;
                row.querySelector('.stock-display').textContent = `(Stock: ${props.stock})`;
                row.querySelector('.unit-price').dispatchEvent(new Event('input', { bubbles: true }));
            }

            const addNewItemRow = () => { const templateClone = document.getElementById('itemRowTemplate').content.cloneNode(true); const newRow = templateClone.querySelector('.item-row'); const rowId = `item-row-${Date.now()}`; newRow.id = rowId; itemContainer.appendChild(templateClone); const selectEl = newRow.querySelector('.product-select'); const newChoice = new Choices(selectEl, { searchEnabled: true, shouldSort: false, itemSelectText: '', }); newChoice.setChoices([placeholderChoice, ...productsForChoices], 'value', 'label', true); choicesInstances[rowId] = newChoice; selectEl.addEventListener('change', handleProductChange); reindexAllRows(); updateTotals(); };

            addItemBtn.addEventListener('click', addNewItemRow);
            discountInput.addEventListener('input', updateTotals);
            phoneSearchInput.addEventListener('input', function () { const phone = this.value.trim(); formCustomerIdInput.value = ''; document.getElementById('customer_name').value = ''; document.getElementById('email').value = ''; document.getElementById('address').value = ''; if (phone.length !== 11) return; fetch(`{{ url('searchcustomer') }}/${phone}`).then(response => { if (!response.ok) throw new Error('Customer not found'); return response.json(); }).then(customer => { formCustomerIdInput.value = customer.id; document.getElementById('customer_name').value = customer.customer_name; document.getElementById('email').value = customer.email; document.getElementById('address').value = customer.address; }).catch(error => { console.error('Customer Search Error:', error); document.getElementById('customer_name').value = 'Customer not found.'; }); });
            commissionPhoneSearchInput.addEventListener('input', function () { const phone = this.value.trim(); commissionRecipientIdInput.value = ''; commissionRecipientTypeInput.value = ''; commissionRecipientNameInput.value = ''; commissionAmountInput.disabled = true; if (phone.length !== 11) return; fetch(`{{ url('/api/search-recipient') }}/${phone}`).then(response => { if (!response.ok) throw new Error('Recipient not found'); return response.json(); }).then(recipient => { commissionRecipientIdInput.value = recipient.id; commissionRecipientTypeInput.value = recipient.type; commissionRecipientNameInput.value = recipient.name; commissionAmountInput.disabled = false; }).catch(error => { console.error('Recipient Search Error:', error); commissionRecipientNameInput.value = 'Recipient not found.'; }); });
            itemContainer.addEventListener('input', function (e) { const row = e.target.closest('.item-row'); if (!row) return; if (e.target.classList.contains('unit-price') || e.target.classList.contains('quantity')) { const unitPrice = parseFloat(row.querySelector('.unit-price').value) || 0; const quantity = parseInt(row.querySelector('.quantity').value) || 0; row.querySelector('.total-price').value = (unitPrice * quantity).toFixed(2); if (e.target.classList.contains('quantity')) { const index = Array.from(itemContainer.children).indexOf(row); updateSerialFields(row, index); } updateTotals(); } });
            itemContainer.addEventListener('click', function (e) { if (e.target.classList.contains('remove-item')) { const rowToRemove = e.target.closest('.item-row'); if (rowToRemove && itemContainer.children.length > 1) { if (choicesInstances[rowToRemove.id]) { choicesInstances[rowToRemove.id].destroy(); delete choicesInstances[rowToRemove.id]; } rowToRemove.remove(); reindexAllRows(); updateTotals(); } } });

            addNewItemRow();
            commissionAmountInput.disabled = true;
        });
    </script>
    @endpush
</x-app-layout>