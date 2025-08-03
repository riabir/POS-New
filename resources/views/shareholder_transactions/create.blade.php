<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Add Transaction for: ') . $shareholder->name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <form method="POST" action="{{ route('shareholder_transactions.store', $shareholder) }}">
                        @csrf
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <x-input-label for="transaction_date" value="Transaction Date" />
                                <x-text-input id="transaction_date" name="transaction_date" type="date" :value="old('transaction_date', now()->format('Y-m-d'))" class="block mt-1 w-full" required />
                                <x-input-error :messages="$errors->get('transaction_date')" class="mt-2" />
                            </div>
                            <div>
                                <x-input-label for="type" value="Transaction Type" />
                                <select name="type" id="type" class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm" required>
                                    @foreach($transactionTypes as $type)
                                        <option value="{{ $type }}" @selected(old('type') == $type)>{{ $type }}</option>
                                    @endforeach
                                </select>
                                <x-input-error :messages="$errors->get('type')" class="mt-2" />
                            </div>
                            <div class="md:col-span-2">
                                {{-- CHANGE: Label text updated --}}
                                <x-input-label for="amount" value="Amount (৳)" />
                                <x-text-input id="amount" name="amount" type="number" step="0.01" :value="old('amount')" class="block mt-1 w-full" required />
                                <x-input-error :messages="$errors->get('amount')" class="mt-2" />
                            </div>
                             <div class="md:col-span-2">
                                <x-input-label for="description" value="Description / Memo" />
                                <x-text-input id="description" name="description" :value="old('description')" class="block mt-1 w-full" />
                                <x-input-error :messages="$errors->get('description')" class="mt-2" />
                            </div>
                        </div>
                        <div class="flex items-center justify-end mt-6">
                            <a href="{{ route('shareholders.show', $shareholder) }}" class="text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800">Cancel</a>
                            <x-primary-button class="ml-4">Add Transaction</x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>