<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Make an Advance Payment to a Customer
        </h2>
    </x-slot>
    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">

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

                    <form method="POST" action="{{ route('customer_advances.store') }}">
                        @csrf
                        <!-- Customer -->
                        <div>
                            <x-input-label for="customer_id" :value="__('Customer')" />
                            <select id="customer_id" name="customer_id" class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm" required>
                                <option value="">Select a Customer</option>
                                @foreach($customers as $customer)
                                    <option value="{{ $customer->id }}" {{ old('customer_id') == $customer->id ? 'selected' : '' }}>
                                        {{ $customer->customer_name }}
                                    </option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('customer_id')" class="mt-2" />
                        </div>

                        <!-- Amount -->
                        <div class="mt-4">
                            <x-input-label for="payment_amount" :value="__('Amount')" />
                            <x-text-input id="payment_amount" class="block mt-1 w-full" type="number" name="payment_amount" :value="old('payment_amount')" required step="0.01" />
                            <x-input-error :messages="$errors->get('payment_amount')" class="mt-2" />
                        </div>
                        
                        <!-- Date -->
                        <div class="mt-4">
                            <x-input-label for="transaction_date" :value="__('Payment Date')" />
                            <x-text-input id="transaction_date" class="block mt-1 w-full" type="date" name="transaction_date" :value="old('transaction_date', now()->format('Y-m-d'))" required />
                            <x-input-error :messages="$errors->get('transaction_date')" class="mt-2" />
                        </div>

                        <!-- Payment Type -->
                        <div class="mt-4">
                            <x-input-label for="payment_type" :value="__('Payment Type')" />
                            <select id="payment_type" name="payment_type" class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm" required>
                                <option value="Cash">Cash</option>
                                <option value="Bank Transfer">Bank Transfer</option>
                                <option value="Cheque">Cheque</option>
                            </select>
                        </div>
                        
                        <!-- Notes -->
                        <div class="mt-4">
                             <x-input-label for="notes" :value="__('Notes (Optional)')" />
                             <x-text-input id="notes" class="block mt-1 w-full" type="text" name="notes" :value="old('notes')" placeholder="e.g., Cheque #12345" />
                        </div>

                        <div class="flex items-center justify-end mt-4">
                            <x-primary-button>
                                {{ __('Submit Advance Payment') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>