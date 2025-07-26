<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Add New Vendor') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">

                    {{-- Display Validation Errors --}}
                    @if ($errors->any())
                        <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                            <strong class="font-bold">Whoops!</strong>
                            <span class="block sm:inline">There were some problems with your input.</span>
                            <ul class="mt-3 list-disc list-inside text-sm">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="post" action="{{ route('vendors.store') }}" class="space-y-6">
                        @csrf

                        {{-- Vendor Name --}}
                        <div>
                            <label for="vendor_name" class="block font-medium text-sm text-gray-700 dark:text-gray-300">Vendor Name</label>
                            <input type="text" id="vendor_name" name="vendor_name" value="{{ old('vendor_name') }}" class="mt-1 block w-full rounded-md shadow-sm" required>
                        </div>

                        {{-- Phone --}}
                        <div>
                            <label for="phone" class="block font-medium text-sm text-gray-700 dark:text-gray-300">Phone</label>
                            <input type="text" id="phone" name="phone" value="{{ old('phone') }}" class="mt-1 block w-full rounded-md shadow-sm" required>
                        </div>

                        {{-- Email --}}
                        <div>
                            <label for="email" class="block font-medium text-sm text-gray-700 dark:text-gray-300">Email Address</label>
                            <input type="email" id="email" name="email" value="{{ old('email') }}" class="mt-1 block w-full rounded-md shadow-sm" required>
                        </div>

                        {{-- Address --}}
                        <div>
                            <label for="address" class="block font-medium text-sm text-gray-700 dark:text-gray-300">Address</label>
                            <input type="text" id="address" name="address" value="{{ old('address') }}" class="mt-1 block w-full rounded-md shadow-sm" required>
                        </div>

                        {{-- Concern Person --}}
                        <div>
                            <label for="concern_person" class="block font-medium text-sm text-gray-700 dark:text-gray-300">Concern Person</label>
                            <input type="text" id="concern_person" name="concern_person" value="{{ old('concern_person') }}" class="mt-1 block w-full rounded-md shadow-sm" required>
                        </div>

                        {{-- Designation --}}
                        <div>
                            <label for="designation" class="block font-medium text-sm text-gray-700 dark:text-gray-300">Designation</label>
                            <input type="text" id="designation" name="designation" value="{{ old('designation') }}" class="mt-1 block w-full rounded-md shadow-sm" required>
                        </div>

                        {{-- Submit Button --}}
                        <div class="flex items-center gap-4">
                            <button type="submit" class="btn btn-primary">
                                Submit
                            </button>
                            <a href="{{ route('vendors.index') }}" class="btn btn-secondary">
                                Cancel
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>