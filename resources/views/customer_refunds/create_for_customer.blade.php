<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Process Customer Refund
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <div class="mb-6">
                        <h3 class="text-lg font-medium">Customer: {{ $customer->customer_name }}</h3>
                        <div class="mt-2 grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="bg-blue-50 dark:bg-blue-900/30 p-4 rounded-lg">
                                <p class="text-sm text-gray-600 dark:text-gray-400">Available Advance</p>
                                <p class="text-xl font-bold">{{ number_format($customer->available_advance, 2) }}</p>
                            </div>
                            <div class="bg-red-50 dark:bg-red-900/30 p-4 rounded-lg">
                                <p class="text-sm text-gray-600 dark:text-gray-400">Payable Balance</p>
                                <p class="text-xl font-bold">{{ number_format($customer->payable_balance, 2) }}</p>
                            </div>
                        </div>
                    </div>

                    @if(session('success'))
                        <div class="mb-4 font-medium text-sm text-green-600 dark:text-green-400 p-3 bg-green-100 dark:bg-gray-700 rounded-md">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if($errors->any())
                        <div class="mb-4 p-3 bg-red-100 text-red-700 rounded-md">
                            <strong class="font-bold">Error!</strong>
                            <ul class="list-disc list-inside">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('customer_refunds.store', $customer->id) }}">
                        @csrf
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Refund Amount -->
                            <div>
                                <x-input-label for="refund_amount" :value="__('Refund Amount')" />
                                <x-text-input id="refund_amount" class="block mt-1 w-full" type="number" name="refund_amount" step="0.01" required />
                                <x-input-error :messages="$errors->get('refund_amount')" class="mt-2" />
                            </div>

                            <!-- Refund Type -->
                            <div>
                                <x-input-label for="refund_type" :value="__('Refund Type')" />
                                <select id="refund_type" name="refund_type" class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm" required>
                                    <option value="advance">Refund Advance Payment</option>
                                    <option value="payable">Refund Against Payable Balance</option>
                                </select>
                                <x-input-error :messages="$errors->get('refund_type')" class="mt-2" />
                            </div>

                            <!-- Payment Type -->
                            <div>
                                <x-input-label for="payment_type" :value="__('Payment Method')" />
                                <select id="payment_type" name="payment_type" class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm" required>
                                    <option value="Cash">Cash</option>
                                    <option value="Bank Transfer">Bank Transfer</option>
                                    <option value="Cheque">Cheque</option>
                                </select>
                                <x-input-error :messages="$errors->get('payment_type')" class="mt-2" />
                            </div>

                            <!-- Refund Date -->
                            <div>
                                <x-input-label for="refund_date" :value="__('Refund Date')" />
                                <x-text-input id="refund_date" class="block mt-1 w-full" type="date" name="refund_date" value="{{ now()->format('Y-m-d') }}" required />
                                <x-input-error :messages="$errors->get('refund_date')" class="mt-2" />
                            </div>
                        </div>

                        <!-- Reason -->
                        <div class="mt-4">
                            <x-input-label for="reason" :value="__('Reason for Refund')" />
                            <textarea id="reason" name="reason" class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm" rows="3" required>{{ old('reason') }}</textarea>
                            <x-input-error :messages="$errors->get('reason')" class="mt-2" />
                        </div>

                        <div class="mt-6 flex items-center justify-end">
                            <a href="{{ route('customer_refunds.index') }}" class="text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 mr-4">
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