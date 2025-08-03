<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Create Bulk Payout for All Active Employees') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <p class="mb-4 text-gray-600 dark:text-gray-400">
                        This will create a payout for <strong>all active employees</strong> who have a valid salary structure. The amount will be calculated as a percentage of their basic salary.
                    </p>
                    <form method="POST" action="{{ route('payouts.storeBulk') }}">
                        @csrf
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <x-input-label for="payout_type" :value="__('Payout Type')" />
                                <select id="payout_type" name="payout_type" class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm" required>
                                    <option value="">Select a Type</option>
                                    @foreach($payoutTypes as $type)
                                        <option value="{{ $type }}" {{ old('payout_type') == $type ? 'selected' : '' }}>
                                            {{ $type }}
                                        </option>
                                    @endforeach
                                </select>
                                <x-input-error :messages="$errors->get('payout_type')" class="mt-2" />
                            </div>
                            <div>
                                <x-input-label for="payout_date" :value="__('Payout Date')" />
                                <x-text-input id="payout_date" class="block mt-1 w-full" type="date" name="payout_date" :value="old('payout_date')" required />
                                <x-input-error :messages="$errors->get('payout_date')" class="mt-2" />
                            </div>
                        </div>
                        <div class="mt-6">
                            <x-input-label for="percentage" :value="__('Percentage of Basic Salary (%)')" />
                            <x-text-input id="percentage" class="block mt-1 w-full" type="number" step="0.01" name="percentage" :value="old('percentage')" required />
                            <x-input-error :messages="$errors->get('percentage')" class="mt-2" />
                        </div>
                        <div class="mt-6">
                            <x-input-label for="notes" :value="__('Notes')" />
                            <textarea id="notes" name="notes" rows="3" class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">{{ old('notes') }}</textarea>
                            <x-input-error :messages="$errors->get('notes')" class="mt-2" />
                        </div>
                        <div class="flex items-center justify-end mt-6">
                            <a href="{{ route('payouts.index') }}" class="text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800">
                                {{ __('Cancel') }}
                            </a>
                            <x-primary-button class="ml-4">
                                Create Bulk Payout
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>