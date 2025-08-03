<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Customer Accounts - Due Bills
        </h2>
    </x-slot>
    <style>
        .styled-table {
            width: 100%;
            border-collapse: collapse;
            table-layout: auto;
        }
        .styled-table thead tr {
            background-color: #f8f9fa;
            color: #333;
            text-align: left;
        }
        .styled-table th,
        .styled-table td {
            padding: 12px 10px;
            border: 1px solid #ddd;
            vertical-align: top;
        }
        .dark .styled-table thead tr {
            background-color: #374151;
            color: #f3f4f6;
        }
        .dark .styled-table th,
        .dark .styled-table td {
            border-color: #4b5563;
        }
        .btn {
            display: inline-block;
            padding: .5rem 1rem;
            font-size: 0.9rem;
            font-weight: bold;
            text-align: center;
            border-radius: .25rem;
            cursor: pointer;
            transition: background-color .15s ease-in-out;
        }
        .btn-success {
            color: #fff;
            background-color: #28a745;
            border-color: #28a745;
        }
        .btn-success:hover {
            background-color: #218838;
        }
        .form-control {
            display: block;
            width: 100%;
            padding: .375rem .75rem;
            font-size: 1rem;
            line-height: 1.5;
            color: #495057;
            background-color: #fff;
            border: 1px solid #ced4da;
            border-radius: .25rem;
        }
        .dark .form-control {
            color: #f3f4f6;
            background-color: #374151;
            border-color: #4b5563;
        }
        .text-danger-custom {
            color: #dc3545;
        }
        .dark .text-danger-custom {
            color: #f87171;
        }
        .hidden-fields {
            display: none;
        }
        .advance-info {
            background-color: #e2f0fb;
            border-left: 4px solid #2196f3;
            padding: 8px;
            margin-bottom: 10px;
            border-radius: 4px;
        }
        .dark .advance-info {
            background-color: #1e3a5f;
            border-left-color: #64b5f6;
        }
        
        /* New styles for validation feedback */
        .max-payment-label {
            display: block;
            margin-top: 0.25rem;
            font-size: 0.75rem;
            color: #6c757d;
        }
        .dark .max-payment-label {
            color: #9ca3af;
        }
        .input-valid {
            border-color: #28a745;
        }
        .input-invalid {
            border-color: #dc3545;
        }
        .payment-error {
            color: #dc3545;
            font-size: 0.8rem;
            margin-top: 0.25rem;
        }
        .dark .payment-error {
            color: #f87171;
        }
    </style>
    <div class="py-12">
        <div class="max-w-screen-xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    {{-- Session & Error Messages --}}
                    @if(session('success'))
                    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4"
                        role="alert">
                        {{ session('success') }}
                    </div>
                    @endif
                    @if($errors->any())
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4"
                        role="alert">
                        <strong class="font-bold">Error!</strong>
                        <ul class="mt-2 list-disc list-inside">
                            @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                    @endif
                    <div class="overflow-x-auto">
                        <table class="styled-table">
                            <thead>
                                <tr>
                                    <th style="width: 18%;">Customer / Invoice</th>
                                    <th style="width: 15%;">Dates</th>
                                    <th style="width: 22%;">Bill Details</th>
                                    <th style="width: 45%;">Process Transaction</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($unpaidBills as $bill)
                                <tr class="border-t border-gray-200 dark:border-gray-700">
                                    <td>
                                        <span class="font-bold">{{ $bill->customer->customer_name }}</span><br>
                                        <small>Invoice: {{ $bill->sale->bill_no ?? 'N/A' }}</small>
                                    </td>
                                    <td>
                                        Bill Date:
                                        {{ \Carbon\Carbon::parse($bill->sale->bill_date)->format('d M, Y') }}<br>
                                        Due Date: {{ \Carbon\Carbon::parse($bill->due_date)->format('d M, Y') }}
                                    </td>
                                    <td>
                                        Total Bill: {{ number_format($bill->amount, 2) }}<br>
                                        Paid: {{ number_format($bill->paid_amount, 2) }}<br>
                                        <strong class="text-danger-custom">Balance:
                                            {{ number_format($bill->balance, 2) }}</strong><br>
                                        <span
                                            class="capitalize text-sm mt-1 inline-block px-2 py-1 rounded {{ $bill->status == 'partially_paid' ? 'bg-yellow-200 text-yellow-800' : 'bg-red-200 text-red-800' }}">
                                            {{ str_replace('_', ' ', $bill->status) }}
                                        </span>
                                    </td>
                                    <td>
                                        <form method="POST"
                                            action="{{ route('customer_accounts.processPayment', $bill->id) }}" class="payment-form">
                                            @csrf
                                            <div class="advance-info">
                                                <strong>Available Advance:</strong> {{ number_format($bill->customer->available_advance, 2) }}
                                            </div>
                                            <div class="space-y-3">
                                                {{-- The "Smart" Dropdown --}}
                                                <div>
                                                    <label for="transaction_type_{{ $bill->id }}"
                                                        class="text-sm font-medium">Select Transaction Type</label>
                                                    <select name="transaction_type"
                                                        id="transaction_type_{{ $bill->id }}"
                                                        class="form-control mt-1 text-sm transaction-type-select" 
                                                        data-bill-id="{{ $bill->id }}"
                                                        data-balance="{{ $bill->balance }}"
                                                        data-available-advance="{{ $bill->customer->available_advance }}"
                                                        onchange="toggleFields(this)">
                                                        <option value="">-- Choose Action --</option>
                                                        <option value="payment">New Payment</option>
                                                        <option value="advance_adjustment">Use Advance Payment</option>
                                                        <option value="vat_adjustment">VAT Adjustment</option>
                                                        <option value="tax_adjustment">TAX Adjustment</option>
                                                        <option value="other_adjustment">Other Adjustment</option>
                                                    </select>
                                                </div>
                                                {{-- Container for all dynamic fields --}}
                                                <div id="fields_container_{{ $bill->id }}" class="space-y-3">
                                                    {{-- Fields for New Payment --}}
                                                    <div id="payment_fields_{{ $bill->id }}"
                                                        class="hidden-fields space-y-3">
                                                        <div>
                                                            <label for="payment_amount_{{ $bill->id }}"
                                                                class="text-sm font-medium">
                                                                Payment Amount
                                                                <span class="max-payment-label" id="max-payment-{{ $bill->id }}">Max: {{ number_format($bill->balance, 2) }}</span>
                                                            </label>
                                                            <input type="number" 
                                                                name="payment_amount"
                                                                id="payment_amount_{{ $bill->id }}" 
                                                                step="0.01"
                                                                min="0"
                                                                max="{{ $bill->balance }}"
                                                                placeholder="0.00" 
                                                                class="form-control mt-1 text-sm payment-input"
                                                                data-bill-id="{{ $bill->id }}"
                                                                data-balance="{{ $bill->balance }}">
                                                            <div class="payment-error" id="payment-error-{{ $bill->id }}"></div>
                                                        </div>
                                                        <div>
                                                            <label for="payment_type_{{ $bill->id }}"
                                                                class="text-sm font-medium">Payment Type</label>
                                                            <select name="payment_type"
                                                                id="payment_type_{{ $bill->id }}"
                                                                class="form-control mt-1 text-sm">
                                                                <option value="Cash">Cash</option>
                                                                <option value="Bank Transfer">Bank Transfer</option>
                                                                <option value="Cheque">Cheque</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    {{-- Fields for Advance Adjustment --}}
                                                    <div id="advance_adjustment_fields_{{ $bill->id }}"
                                                        class="hidden-fields space-y-3">
                                                        <div>
                                                            <label for="advance_amount_{{ $bill->id }}"
                                                                class="text-sm font-medium">
                                                                Amount to Use from Advance
                                                                <span class="max-payment-label" id="max-advance-{{ $bill->id }}">Max: {{ number_format(min($bill->balance, $bill->customer->available_advance), 2) }}</span>
                                                            </label>
                                                            <input type="number" 
                                                                name="advance_amount"
                                                                id="advance_amount_{{ $bill->id }}" 
                                                                step="0.01"
                                                                min="0"
                                                                max="{{ min($bill->balance, $bill->customer->available_advance) }}"
                                                                placeholder="0.00" 
                                                                class="form-control mt-1 text-sm advance-input"
                                                                data-bill-id="{{ $bill->id }}"
                                                                data-balance="{{ $bill->balance }}"
                                                                data-available-advance="{{ $bill->customer->available_advance }}">
                                                            <small class="text-gray-500 dark:text-gray-400">Available: {{ number_format($bill->customer->available_advance, 2) }}</small>
                                                            <div class="payment-error" id="advance-error-{{ $bill->id }}"></div>
                                                        </div>
                                                    </div>
                                                    {{-- Fields for ALL other adjustments (VAT, TAX, Other) --}}
                                                    <div id="other_adjustment_fields_{{ $bill->id }}" class="hidden-fields space-y-3">
                                                        <div>
                                                            <label for="adjustment_amount_{{ $bill->id }}" class="text-sm font-medium">Adjustment Amount</label>
                                                            <input type="number" name="adjustment_amount" id="adjustment_amount_{{ $bill->id }}" step="0.01" placeholder="0.00" class="form-control mt-1 text-sm">
                                                        </div>
                                                        <div>
                                                            <label for="effect_{{ $bill->id }}" class="text-sm font-medium">Effect on Bill</label>
                                                            <select name="effect" id="effect_{{ $bill->id }}" class="form-control mt-1 text-sm">
                                                                <option value="credit">CREDIT (Decrease Bill Amount - Tax Refund)</option>
                                                            </select>
                                                        </div>
                                                        <div>
                                                            <label for="notes_{{ $bill->id }}" class="text-sm font-medium">Reason / Notes (Required)</label>
                                                            <input type="text" name="notes" id="notes_{{ $bill->id }}" placeholder="e.g., VAT correction for..." class="form-control mt-1 text-sm">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="mt-3">
                                                <button type="submit" class="btn btn-success w-full payment-submit"
                                                    onclick="return confirm('Are you sure you want to process this transaction?')">
                                                    Submit Transaction
                                                </button>
                                                <div class="payment-error" id="total-error-{{ $bill->id }}"></div>
                                            </div>
                                        </form>
                                        <a href="{{ route('customer_accounts.show_ledger', $bill->id) }}"
                                            class="mt-2 text-sm text-blue-600 hover:underline inline-block w-full text-center">
                                            View Bill Ledger
                                        </a>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="text-center py-4">No unpaid bills found. Great job!</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-4">
                        {{ $unpaidBills->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Get all transaction type selects
            const transactionSelects = document.querySelectorAll('.transaction-type-select');
            
            transactionSelects.forEach(select => {
                const billId = select.dataset.billId;
                const currentBalance = parseFloat(select.dataset.balance);
                const availableAdvance = parseFloat(select.dataset.availableAdvance);
                
                // Get all input fields for this form
                const paymentInput = document.getElementById(`payment_amount_${billId}`);
                const advanceInput = document.getElementById(`advance_amount_${billId}`);
                const paymentErrorElement = document.getElementById(`payment-error-${billId}`);
                const advanceErrorElement = document.getElementById(`advance-error-${billId}`);
                const totalErrorElement = document.getElementById(`total-error-${billId}`);
                const maxPaymentLabel = document.getElementById(`max-payment-${billId}`);
                const maxAdvanceLabel = document.getElementById(`max-advance-${billId}`);
                
                // Function to validate payment input
                function validatePaymentInput() {
                    const paymentAmount = parseFloat(paymentInput.value) || 0;
                    
                    // Clear previous error
                    paymentErrorElement.textContent = '';
                    paymentInput.classList.remove('input-invalid');
                    
                    // Check if payment exceeds balance
                    if (paymentAmount > currentBalance) {
                        paymentErrorElement.textContent = `Payment exceeds balance by ${(paymentAmount - currentBalance).toFixed(2)}`;
                        paymentInput.classList.add('input-invalid');
                        return false;
                    }
                    
                    return true;
                }
                
                // Function to validate advance input
                function validateAdvanceInput() {
                    const advanceAmount = parseFloat(advanceInput.value) || 0;
                    const maxAdvance = Math.min(currentBalance, availableAdvance);
                    
                    // Clear previous error
                    advanceErrorElement.textContent = '';
                    advanceInput.classList.remove('input-invalid');
                    
                    // Check if advance exceeds maximum allowed
                    if (advanceAmount > maxAdvance) {
                        advanceErrorElement.textContent = `Amount exceeds maximum allowed by ${(advanceAmount - maxAdvance).toFixed(2)}`;
                        advanceInput.classList.add('input-invalid');
                        return false;
                    }
                    
                    return true;
                }
                
                // Add event listeners for real-time validation
                if (paymentInput) {
                    paymentInput.addEventListener('input', validatePaymentInput);
                }
                
                if (advanceInput) {
                    advanceInput.addEventListener('input', validateAdvanceInput);
                }
                
                // Add form submission validation
                const form = select.closest('form');
                form.addEventListener('submit', function(e) {
                    const transactionType = select.value;
                    let isValid = true;
                    
                    // Clear previous total error
                    totalErrorElement.textContent = '';
                    
                    // Validate based on transaction type
                    if (transactionType === 'payment' && paymentInput) {
                        isValid = validatePaymentInput();
                    } else if (transactionType === 'advance_adjustment' && advanceInput) {
                        isValid = validateAdvanceInput();
                    }
                    
                    // If validation fails, prevent form submission
                    if (!isValid) {
                        e.preventDefault();
                        totalErrorElement.textContent = 'Please correct the errors before submitting.';
                    }
                });
            });
        });
        
        function toggleFields(selectElement) {
            const billId = selectElement.id.split('_').pop();
            const selectedType = selectElement.value;
            
            // Hide all field containers first
            document.getElementById(`payment_fields_${billId}`).style.display = 'none';
            document.getElementById(`advance_adjustment_fields_${billId}`).style.display = 'none';
            document.getElementById(`other_adjustment_fields_${billId}`).style.display = 'none';
            
            // Show the relevant container based on selection
            if (selectedType === 'payment') {
                document.getElementById(`payment_fields_${billId}`).style.display = 'block';
            } else if (selectedType === 'advance_adjustment') {
                document.getElementById(`advance_adjustment_fields_${billId}`).style.display = 'block';
            } else if (['vat_adjustment', 'tax_adjustment', 'other_adjustment'].includes(selectedType)) {
                document.getElementById(`other_adjustment_fields_${billId}`).style.display = 'block';
            }
        }
    </script>
    @endpush
</x-app-layout>