<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Add New Shareholder') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 md:p-8 text-gray-900 dark:text-gray-100">
                    @if(session('error'))
                        <div class="mb-4 font-medium text-sm text-red-600 dark:text-red-400 bg-red-100 dark:bg-red-900/50 border border-red-400 dark:border-red-600 rounded-md p-3">
                            {{ session('error') }}
                        </div>
                    @endif

                    <form method="POST" action="{{ route('shareholders.store') }}">
                        @csrf
                        <h3 class="text-lg font-medium text-gray-900 dark:text-gray-200 mb-4">Shareholder Details</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <x-input-label for="name" value="Name" />
                                <x-text-input id="name" name="name" :value="old('name')" class="block mt-1 w-full" required />
                                <x-input-error :messages="$errors->get('name')" class="mt-2" />
                            </div>
                            <div>
                                <x-input-label for="email" value="Email" />
                                <x-text-input id="email" name="email" :value="old('email')" type="email" class="block mt-1 w-full" />
                                <x-input-error :messages="$errors->get('email')" class="mt-2" />
                            </div>
                            <div>
                                <x-input-label for="phone" value="Phone" />
                                <x-text-input id="phone" name="phone" :value="old('phone')" class="block mt-1 w-full" />
                                <x-input-error :messages="$errors->get('phone')" class="mt-2" />
                            </div>
                            <div>
                                <x-input-label for="join_date" value="Join Date" />
                                <x-text-input id="join_date" name="join_date" :value="old('join_date')" type="date" class="block mt-1 w-full" required />
                                <x-input-error :messages="$errors->get('join_date')" class="mt-2" />
                            </div>
                            <div class="md:col-span-2">
                                <x-input-label for="address" value="Address" />
                                <textarea id="address" name="address" class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">{{ old('address') }}</textarea>
                                <x-input-error :messages="$errors->get('address')" class="mt-2" />
                            </div>
                        </div>

                        <h3 class="text-lg font-medium text-gray-900 dark:text-gray-200 mt-8 mb-4">Initial Investment</h3>
                        <div>
                            {{-- CHANGE: Label text updated --}}
                            <x-input-label for="initial_investment" value="Investment Amount (৳)" />
                            <x-text-input id="initial_investment" name="initial_investment" :value="old('initial_investment')" type="number" step="0.01" class="block mt-1 w-full" required />
                            <x-input-error :messages="$errors->get('initial_investment')" class="mt-2" />
                        </div>

                        <div class="mt-6">
                            <x-input-label for="notes" value="Notes" />
                            <textarea id="notes" name="notes" class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">{{ old('notes') }}</textarea>
                            <x-input-error :messages="$errors->get('notes')" class="mt-2" />
                        </div>
                        
                        <div class="flex items-center justify-end mt-8">
                            <a href="{{ route('shareholders.index') }}" class="text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800">Cancel</a>
                            <x-primary-button class="ml-4">Save Shareholder</x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>