<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{-- Dynamic title for editing a specific vendor --}}
                {{ __('Edit Vendor:') }} <span class="text-indigo-600 dark:text-indigo-400">{{ $vendor->vendor_name }}</span>
            </h2>
            <a href="{{ route('vendors.index') }}" class="inline-flex items-center px-4 py-2 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-500 rounded-md font-semibold text-xs text-gray-700 dark:text-gray-300 uppercase tracking-widest shadow-sm hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 disabled:opacity-25 transition ease-in-out duration-150">
                Back to List
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-700 overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6 lg:p-8 bg-white dark:bg-gray-800 dark:bg-gradient-to-bl dark:from-gray-700/50 dark:via-transparent border-b border-gray-200 dark:border-gray-700">
                    
                    {{-- Display Validation Errors --}}
                    @if ($errors->any())
                        <div class="mb-6 bg-red-100 border-l-4 border-red-500 text-red-700 p-4" role="alert">
                            <p class="font-bold">Whoops! Something went wrong.</p>
                            <ul class="mt-2 list-disc list-inside text-sm">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="post" action="{{ route('vendors.update', $vendor->id) }}" class="mt-6 space-y-6">
                        @csrf
                        @method('PATCH') {{-- Use PATCH for updates, it's more idiomatic than PUT for partial updates --}}

                        {{-- Form Section Grid --}}
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                            {{-- Vendor Name --}}
                            <div>
                                <label for="vendor_name" class="block font-medium text-sm text-gray-700 dark:text-gray-300">{{ __('Vendor Name') }}</label>
                                <input type="text" id="vendor_name" name="vendor_name" value="{{ old('vendor_name', $vendor->vendor_name) }}" 
                                       class="border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm mt-1 block w-full" 
                                       required autofocus autocomplete="organization">
                            </div>

                            {{-- Concern Person --}}
                            <div>
                                <label for="concern_person" class="block font-medium text-sm text-gray-700 dark:text-gray-300">{{ __('Concern Person') }}</label>
                                <input type="text" id="concern_person" name="concern_person" value="{{ old('concern_person', $vendor->concern_person) }}" 
                                       class="border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm mt-1 block w-full" 
                                       required autocomplete="name">
                            </div>

                            {{-- Designation --}}
                            <div>
                                <label for="designation" class="block font-medium text-sm text-gray-700 dark:text-gray-300">{{ __('Designation') }}</label>
                                <input type="text" id="designation" name="designation" value="{{ old('designation', $vendor->designation) }}" 
                                       class="border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm mt-1 block w-full" 
                                       required autocomplete="designation">
                            </div>

                            {{-- Phone --}}
                            <div>
                                <label for="phone" class="block font-medium text-sm text-gray-700 dark:text-gray-300">{{ __('Phone') }}</label>
                                <input type="tel" id="phone" name="phone" value="{{ old('phone', $vendor->phone) }}" 
                                       class="border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm mt-1 block w-full" 
                                       required autocomplete="tel">
                            </div>

                            {{-- Email --}}
                            <div>
                                <label for="email" class="block font-medium text-sm text-gray-700 dark:text-gray-300">{{ __('Email Address') }}</label>
                                <input type="email" id="email" name="email" value="{{ old('email', $vendor->email) }}" 
                                       class="border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm mt-1 block w-full" 
                                       required autocomplete="email">
                            </div>

                            {{-- Address (Spans full width) --}}
                            <div class="md:col-span-2">
                                <label for="address" class="block font-medium text-sm text-gray-700 dark:text-gray-300">{{ __('Address') }}</label>
                                <textarea id="address" name="address" rows="3"
                                          class="border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm mt-1 block w-full"
                                          required autocomplete="street-address">{{ old('address', $vendor->address) }}</textarea>
                            </div>

                        </div>

                        {{-- Form Actions --}}
                        <div class="flex items-center justify-end mt-8 pt-6 border-t border-gray-200 dark:border-gray-700">
                            <a href="{{ route('vendors.index') }}" class="text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white underline mr-4">
                                {{ __('Cancel') }}
                            </a>
                            
                            <button type="submit" class="inline-flex items-center px-4 py-2 bg-gray-800 dark:bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-white dark:text-gray-800 uppercase tracking-widest hover:bg-gray-700 dark:hover:bg-white focus:bg-gray-700 dark:focus:bg-white active:bg-gray-900 dark:active:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                                {{ __('Update Vendor') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>