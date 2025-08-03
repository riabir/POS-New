<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Process Vendor Refund
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <div class="mb-6 border-b border-gray-200 dark:border-gray-700 pb-6">
                        <h3 class="text-lg font-medium">Vendor: {{ $vendor->vendor_name }}</h3>
                        <div class="mt-2 grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="bg-red-50 dark:bg-red-900/30 p-4 rounded-lg">
                                <p class="text-sm text-gray-600 dark:text-gray-400">Payable Balance (We Owe)</p>
                                <p class="text-xl font-bold">{{ number_format($vendor->payable_balance, 2) }}</p>
                            </div>
                             <div class="bg-green-50 dark:bg-green-900/30 p-4 rounded-lg">
                                <p class="text-sm text-gray-600 dark:text-gray-400">Available Advance (They Paid Us)</p>
                                <p class="text-xl font-bold">{{ number_format($vendor->available_advance, 2) }}</p>
                            </div>
                        </div>
                    </div>

                    <x-auth-session-status class="mb-4" :status="session('status')" />
                    <x-input-error :messages="$errors->get('refund_amount')" class="mb-4" />


                    <form method="POST" action="{{ route('vendor_refunds.store', $vendor->id) }}">
                        @csrf
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Refund Amount -->
                            <div>
                                <x-input-label for="refund_amount" :value="__('Refund Amount')" />
                                <x-text-input id="refund_amount" class="block mt-1 w-full" type="number" name="refund_amount" :value="old('refund_amount')" step="0.01" required autofocus />
                            </div>

                            <!-- Refund Type -->
                            <div>
                                <x-input-label for="refund_type" :value="__('Refund From')" />
                                <select id="refund_type" name="refund_type" class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm" required>
                                    <option value="payable" @selected(old('refund_type') == 'payable')>Refund from Payable Balance</option>
                                    <option value="advance" @selected(old('refund_type') == 'advance')>Refund from Available Advance</option>
                                </select>
                            </div>

                            <!-- Payment Method -->
                            <div>
                                <x-input-label for="payment_type" :value="__('Payment Method')" />
                                <select id="payment_type" name="payment_type" class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm" required>
                                    <option value="Cash" @selected(old('payment_type') == 'Cash')>Cash</option>
                                    <option value="Bank Transfer" @selected(old('payment_type') == 'Bank Transfer')>Bank Transfer</option>
                                    <option value="Cheque" @selected(old('payment_type') == 'Cheque')>Cheque</option>
                                </select>
                            </div>

                            <!-- Refund Date -->
                            <div>
                                <x-input-label for="refund_date" :value="__('Refund Date')" />
                                <x-text-input id="refund_date" class="block mt-1 w-full" type="date" name="refund_date" value="{{ old('refund_date', now()->format('Y-m-d')) }}" required />
                            </div>
                        </div>

                        <!-- Reason -->
                        <div class="mt-4">
                            <x-input-label for="reason" :value="__('Reason for Refund')" />
                            <textarea id="reason" name="reason" class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm" rows="3" required>{{ old('reason') }}</textarea>
                            <x-input-error :messages="$errors->get('reason')" class="mt-2" />
                        </div>

                        <div class="mt-6 flex items-center justify-end">
                            <a href="{{ route('vendor_refunds.index') }}" class="text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 mr-4">
                                Cancel
                            </a>
                            <x-primary-button>
                                {{ __('Process Refund') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>