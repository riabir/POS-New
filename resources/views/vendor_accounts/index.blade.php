<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Accounts Payable - Unpaid Bills
        </h2>
    </x-slot>
    <style>
        .styled-table { width: 100%; border-collapse: collapse; table-layout: fixed; }
        .styled-table thead tr { background-color: #f8f9fa; color: #333; text-align: left; }
        .styled-table th, .styled-table td { padding: 12px 10px; border: 1px solid #ddd; vertical-align: top; }
        .dark .styled-table thead tr { background-color: #374151; color: #f3f4f6; }
        .dark .styled-table th, .dark .styled-table td { border-color: #4b5563; }
        .btn { display: inline-block; padding: .5rem 1rem; font-size: 0.9rem; font-weight: bold; text-align: center; border-radius: .25rem; cursor: pointer; transition: background-color .15s ease-in-out; }
        .btn-success { color: #fff; background-color: #28a745; border-color: #28a745; }
        .btn-success:hover { background-color: #218838; }
        .form-control { display: block; width: 100%; padding: .375rem .75rem; font-size: 1rem; line-height: 1.5; color: #495057; background-color: #fff; border: 1px solid #ced4da; border-radius: .25rem; }
        .dark .form-control { color: #f3f4f6; background-color: #374151; border-color: #4b5563; }
        .text-danger-custom { color: #dc3545; }
        .dark .text-danger-custom { color: #f87171; }
        
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
                    @if(session('success'))
                        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                            {{ session('success') }}
                        </div>
                    @endif
                    @if($errors->any())
                        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
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
                            <colgroup>
                                <col style="width: 18%;">
                                <col style="width: 15%;">
                                <col style="width: 22%;">
                                <col style="width: 45%;">
                            </colgroup>
                            <thead>
                                <tr>
                                    <th>Vendor / PO</th>
                                    <th>Dates</th>
                                    <th>Bill Details</th>
                                    <th>Payment Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($unpaidBills as $bill)
                                    <tr class="border-t border-gray-200 dark:border-gray-700">
                                        <td>
                                            <span class="font-bold">{{ $bill->vendor->vendor_name }}</span><br>
                                            <small>PO: {{ $bill->purchase->purchase_no ?? 'N/A' }}</small>
                                        </td>
                                        <td>
                                            Bill Date: {{ \Carbon\Carbon::parse($bill->purchase->purchase_date)->format('d M, Y') }}<br>
                                            Due Date: {{ \Carbon\Carbon::parse($bill->due_date)->format('d M, Y') }}
                                        </td>
                                        <td>
                                            Total Bill: {{ number_format($bill->amount, 2) }}<br>
                                            Paid: {{ number_format($bill->paid_amount, 2) }}<br>
                                            <strong class="text-danger-custom">Balance: {{ number_format($bill->balance, 2) }}</strong><br>
                                            <span class="capitalize text-sm mt-1 inline-block px-2 py-1 rounded {{ $bill->status == 'partially_paid' ? 'bg-yellow-200 text-yellow-800' : 'bg-red-200 text-red-800' }}">
                                                {{ str_replace('_', ' ', $bill->status) }}
                                            </span>
                                        </td>
                                        <td>
                                            <form method="POST" action="{{ route('vendor_accounts.processPayment', $bill->id) }}" class="payment-form">
                                                @csrf
                                                <div class="grid grid-cols-2 gap-4">
                                                    
                                                    <!-- Left Column: Direct Payment -->
                                                    <div class="space-y-3">
                                                        <div>
                                                            <label for="due_payment_{{ $bill->id }}" class="text-sm font-medium">
                                                                Due Payment Received
                                                                <span class="max-payment-label" id="max-due-{{ $bill->id }}">Max: {{ number_format($bill->balance, 2) }}</span>
                                                            </label>
                                                            <input type="number" 
                                                                   name="due_payment" 
                                                                   id="due_payment_{{ $bill->id }}" 
                                                                   step="0.01" 
                                                                   min="0" 
                                                                   max="{{ $bill->balance }}"
                                                                   placeholder="0.00" 
                                                                   class="form-control mt-1 text-sm payment-input"
                                                                   data-balance="{{ $bill->balance }}"
                                                                   data-bill-id="{{ $bill->id }}">
                                                            <div class="payment-error" id="due-error-{{ $bill->id }}"></div>
                                                        </div>
                                                        <div>
                                                           <label for="payment_type_{{ $bill->id }}" class="text-sm font-medium">Payment Type</label>
                                                            <select name="payment_type" id="payment_type_{{ $bill->id }}" class="form-control mt-1 text-sm">
                                                                <option value="Cash">Cash</option>
                                                                <option value="Bank Transfer">Bank Transfer</option>
                                                                <option value="Cheque">Cheque</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <!-- Right Column: Advance & Notes -->
                                                    <div class="space-y-3">
                                                         <div>
                                                            <label for="advance_adjustment_{{ $bill->id }}" class="text-sm font-medium">
                                                                Advance Payment Adjustment
                                                                <span class="max-payment-label" id="max-advance-{{ $bill->id }}">Max: {{ number_format(min($bill->balance, $bill->vendor->available_advance), 2) }}</span>
                                                            </label>
                                                            <input type="number" 
                                                                   name="advance_adjustment" 
                                                                   id="advance_adjustment_{{ $bill->id }}" 
                                                                   step="0.01" 
                                                                   min="0" 
                                                                   max="{{ min($bill->balance, $bill->vendor->available_advance) }}"
                                                                   placeholder="0.00" 
                                                                   class="form-control mt-1 text-sm advance-input"
                                                                   data-balance="{{ $bill->balance }}"
                                                                   data-available-advance="{{ $bill->vendor->available_advance }}"
                                                                   data-bill-id="{{ $bill->id }}">
                                                            <small class="text-gray-500 dark:text-gray-400">Available: {{ number_format($bill->vendor->available_advance, 2) }}</small>
                                                            <div class="payment-error" id="advance-error-{{ $bill->id }}"></div>
                                                        </div>
                                                        <div>
                                                            <label for="notes_{{ $bill->id }}" class="text-sm font-medium">Notes (Optional)</label>
                                                            <input type="text" name="notes" id="notes_{{ $bill->id }}" placeholder="e.g., Cheque no, details..." class="form-control mt-1 text-sm">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="mt-3">
                                                    <button type="submit" class="btn btn-success w-full payment-submit" onclick="return confirm('Are you sure you want to process this payment?')">
                                                        Process Payment
                                                    </button>
                                                    <div class="payment-error" id="total-error-{{ $bill->id }}"></div>
                                                </div>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center py-4">No unpaid bills found. Great job!</td>
                                    </tr>
                                @endforelse  <!-- This was missing -->
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
            // Get all payment forms
            const paymentForms = document.querySelectorAll('.payment-form');
            
            paymentForms.forEach(form => {
                const duePaymentInput = form.querySelector('.payment-input');
                const advanceAdjustmentInput = form.querySelector('.advance-input');
                const balanceElement = form.closest('tr').querySelector('.text-danger-custom');
                const currentBalance = parseFloat(balanceElement.textContent.replace(/[^0-9.-]+/g,""));
                const billId = duePaymentInput.dataset.billId;
                const maxDueLabel = document.getElementById(`max-due-${billId}`);
                const maxAdvanceLabel = document.getElementById(`max-advance-${billId}`);
                const dueErrorElement = document.getElementById(`due-error-${billId}`);
                const advanceErrorElement = document.getElementById(`advance-error-${billId}`);
                const totalErrorElement = document.getElementById(`total-error-${billId}`);
                
                // Function to update max allowed payment
                function updateMaxPayment() {
                    const advanceAdjustment = parseFloat(advanceAdjustmentInput.value) || 0;
                    const maxDuePayment = Math.max(0, currentBalance - advanceAdjustment);
                    const maxAdvance = Math.min(currentBalance, parseFloat(advanceAdjustmentInput.dataset.availableAdvance));
                    
                    // Update max attributes
                    duePaymentInput.max = maxDuePayment.toFixed(2);
                    advanceAdjustmentInput.max = maxAdvance.toFixed(2);
                    
                    // Update labels
                    maxDueLabel.textContent = `Max: ${maxDuePayment.toFixed(2)}`;
                    maxAdvanceLabel.textContent = `Max: ${maxAdvance.toFixed(2)}`;
                    
                    // Validate inputs
                    validateInputs();
                }
                
                // Function to validate inputs
                function validateInputs() {
                    const duePayment = parseFloat(duePaymentInput.value) || 0;
                    const advanceAdjustment = parseFloat(advanceAdjustmentInput.value) || 0;
                    const totalPayment = duePayment + advanceAdjustment;
                    
                    // Clear previous errors
                    dueErrorElement.textContent = '';
                    advanceErrorElement.textContent = '';
                    totalErrorElement.textContent = '';
                    duePaymentInput.classList.remove('input-invalid');
                    advanceAdjustmentInput.classList.remove('input-invalid');
                    
                    // Check for overpayment
                    if (totalPayment > currentBalance) {
                        totalErrorElement.textContent = `Total payment exceeds balance by ${(totalPayment - currentBalance).toFixed(2)}`;
                        return false;
                    }
                    
                    // Check individual inputs
                    if (duePayment > parseFloat(duePaymentInput.max)) {
                        dueErrorElement.textContent = `Amount exceeds maximum allowed`;
                        duePaymentInput.classList.add('input-invalid');
                        return false;
                    }
                    
                    if (advanceAdjustment > parseFloat(advanceAdjustmentInput.max)) {
                        advanceErrorElement.textContent = `Amount exceeds maximum allowed`;
                        advanceAdjustmentInput.classList.add('input-invalid');
                        return false;
                    }
                    
                    return true;
                }
                
                // Initialize
                updateMaxPayment();
                
                // Update when advance adjustment changes
                advanceAdjustmentInput.addEventListener('input', updateMaxPayment);
                duePaymentInput.addEventListener('input', validateInputs);
                
                // Validate on form submission
                form.addEventListener('submit', function(e) {
                    if (!validateInputs()) {
                        e.preventDefault();
                    }
                });
            });
        });
    </script>
    @endpush
</x-app-layout>