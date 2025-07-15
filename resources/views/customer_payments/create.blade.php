<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Process Payment for PO: {{ $customer_account->sale->bill_no }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 md:p-8 text-gray-900 dark:text-gray-100">

                    <!-- Bill Summary -->
                    <div class="mb-6 border-b pb-4 dark:border-gray-600">
                        <h3 class="text-lg font-bold">Bill Details</h3>
                        <div class="grid grid-cols-2 gap-4 mt-2 text-sm">
                            <div><strong>Customer:</strong> {{ $customer_account->customer->customer_name }}</div>
                            <div><strong>Bill Date:</strong> {{ \Carbon\Carbon::parse($customer_account->sale->bill_date)->format('d M, Y') }}</div>
                            <div><strong>Total Bill:</strong> {{ number_format($customer_account->amount, 2) }}</div>
                            <div class="font-bold text-red-600"><strong>Balance Due:</strong> {{ number_format($customer_account->balance, 2) }}</div>
                        </div>
                    </div>

                    <form method="POST" action="{{ route('customer_payments.store', $customer_account->id) }}">
                        @csrf
                        
                        <!-- New Payment Section -->
                        <h3 class="text-lg font-bold">New Payment</h3>
                        <div class="mt-4 grid md:grid-cols-2 gap-6">
                            <div>
                                <x-input-label for="payment_amount" value="Payment Amount Received" />
                                <x-text-input id="payment_amount" class="block mt-1 w-full" type="number" name="payment_amount" :value="old('payment_amount')" step="0.01" placeholder="0.00" />
                            </div>
                            <div>
                                <x-input-label for="payment_type" value="Payment Type" />
                                <select name="payment_type" id="payment_type" class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 rounded-md shadow-sm">
                                    <option value="">Select if paying</option>
                                    <option value="Cash">Cash</option>
                                    <option value="Bank Transfer">Bank Transfer</option>
                                    <option value="Cheque">Cheque</option>
                                </select>
                            </div>
                        </div>

                        <!-- Advance Adjustment Section -->
                        <h3 class="text-lg font-bold mt-6">Advance Adjustment</h3>
                        <p class="text-sm text-gray-600 dark:text-gray-400">Apply customer's existing advance balance to this bill.</p>
                        <div class="mt-4">
                            <x-input-label for="advance_adjustment" value="Amount to Adjust from Advance" />
                            <x-text-input id="advance_adjustment" class="block mt-1 w-full" type="number" name="advance_adjustment" :value="old('advance_adjustment')" step="0.01" placeholder="0.00" />
                            <small class="text-gray-500 dark:text-gray-400">Available Advance: {{ number_format($customer_account->customer->available_advance ?? 0, 2) }}</small>
                        </div>
                        
                        <!-- Common Fields -->
                         <div class="mt-6 grid md:grid-cols-2 gap-6">
                            <div>
                                <x-input-label for="payment_date" value="Payment Date" />
                                <x-text-input id="payment_date" class="block mt-1 w-full" type="date" name="payment_date" :value="old('payment_date', now()->format('Y-m-d'))" required />
                            </div>
                            <div>
                                <x-input-label for="notes" value="Notes (Optional)" />
                                <x-text-input id="notes" class="block mt-1 w-full" type="text" name="notes" :value="old('notes')" placeholder="e.g., Cheque No. 12345" />
                            </div>
                         </div>
                        
                        <div class="flex items-center justify-end mt-8">
                            <a href="{{ route('customer_accounts.index') }}" class="text-sm text-gray-600 hover:text-gray-900 mr-4">Cancel</a>
                            <x-primary-button>
                                {{ __('Process Payment') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>