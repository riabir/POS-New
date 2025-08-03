<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Edit Customer: ') }} <span class="text-indigo-600">{{ $customer->customer_name }}</span>
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">

                    @if ($errors->any())
                        {{-- ... error display ... --}}
                    @endif

                    <form method="post" action="{{ route('customers.update', $customer->id) }}" class="space-y-6">
                        @csrf
                        @method('PATCH')
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="customer_name" class="block font-medium text-sm">Customer / Company Name</label>
                                <input type="text" id="customer_name" name="customer_name" value="{{ old('customer_name', $customer->customer_name) }}" class="mt-1 block w-full rounded-md shadow-sm" required>
                            </div>
                            <div>
                                <label for="phone" class="block font-medium text-sm">Phone</label>
                                <input type="text" id="phone" name="phone" value="{{ old('phone', $customer->phone) }}" class="mt-1 block w-full rounded-md shadow-sm" required>
                            </div>
                            <div>
                                <label for="email" class="block font-medium text-sm">Email Address</label>
                                <input type="email" id="email" name="email" value="{{ old('email', $customer->email) }}" class="mt-1 block w-full rounded-md shadow-sm" required>
                            </div>
                            <div>
                                <label for="address" class="block font-medium text-sm">Address</label>
                                <input type="text" id="address" name="address" value="{{ old('address', $customer->address) }}" class="mt-1 block w-full rounded-md shadow-sm" required>
                            </div>
                            <div>
                                <label for="concern" class="block font-medium text-sm">Concern Person</label>
                                <input type="text" id="concern" name="concern" value="{{ old('concern', $customer->concern) }}" class="mt-1 block w-full rounded-md shadow-sm" required>
                            </div>
                             <div>
                                <label for="designation" class="block font-medium text-sm">Designation</label>
                                <input type="text" id="designation" name="designation" value="{{ old('designation', $customer->designation) }}" class="mt-1 block w-full rounded-md shadow-sm" required>
                            </div>
                             <div>
                                <label for="opening_balance" class="block font-medium text-sm">Opening Balance</label>
                                <input type="number" step="0.01" id="opening_balance" name="opening_balance" value="{{ old('opening_balance', $customer->opening_balance) }}" class="mt-1 block w-full rounded-md shadow-sm">
                            </div>
                        </div>
                        <div>
                            <label for="notes" class="block font-medium text-sm">Notes</label>
                            <textarea id="notes" name="notes" rows="3" class="mt-1 block w-full rounded-md shadow-sm">{{ old('notes', $customer->notes) }}</textarea>
                        </div>

                        <div class="flex items-center gap-4 border-t pt-6">
                            <button type="submit" class="btn btn-primary">Update Customer</button>
                            <a href="{{ route('customers.index') }}" class="btn btn-secondary">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>