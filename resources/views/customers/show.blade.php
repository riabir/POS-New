<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Customer Details') }}
            </h2>
            <div class="flex items-center space-x-2">
                <a href="{{ route('customers.index') }}" class="btn btn-secondary">Back to List</a>
                <!-- <a href="{{ route('customers.edit', $customer->id) }}" class="btn btn-primary">Edit Customer</a> -->
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6 lg:p-8">
                    
                    <div class="space-y-4">
                        <h3 class="text-2xl font-semibold text-gray-800 dark:text-gray-200 border-b pb-3 mb-4">
                            {{ $customer->customer_name }}
                        </h3>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-4 text-sm">
                            <div class="flex flex-col">
                                <span class="font-medium text-gray-500">Customer ID</span>
                                <span class="mt-1">{{ $customer->id }}</span>
                            </div>
                             <div class="flex flex-col">
                                <span class="font-medium text-gray-500">Phone</span>
                                <span class="mt-1">{{ $customer->phone }}</span>
                            </div>
                             <div class="flex flex-col">
                                <span class="font-medium text-gray-500">Email</span>
                                <a href="mailto:{{ $customer->email }}" class="text-indigo-600 hover:underline mt-1">{{ $customer->email }}</a>
                            </div>
                             <div class="flex flex-col">
                                <span class="font-medium text-gray-500">Address</span>
                                <span class="mt-1">{{ $customer->address }}</span>
                            </div>
                             <div class="flex flex-col">
                                <span class="font-medium text-gray-500">Concern Person</span>
                                <span class="mt-1">{{ $customer->concern }}</span>
                            </div>
                             <div class="flex flex-col">
                                <span class="font-medium text-gray-500">Designation</span>
                                <span class="mt-1">{{ $customer->designation }}</span>
                            </div>
                             <div class="md:col-span-2 flex flex-col">
                                <span class="font-medium text-gray-500">Notes</span>
                                <p class="mt-1">{{ $customer->notes ?? 'N/A' }}</p>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>