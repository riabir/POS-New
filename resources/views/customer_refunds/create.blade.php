<!-- resources/views/customer_refunds/create.blade.php -->
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Process Customer Refund
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h3 class="text-lg font-medium mb-6">Select a Customer to Process Refund</h3>

                    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                        @foreach($customers as $customer)
                            <div class="border rounded-lg p-4 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                                <div class="font-medium">{{ $customer->customer_name }}</div>
                                <div class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                                    Advance: {{ number_format($customer->available_advance, 2) }}
                                </div>
                                <div class="text-sm text-gray-500 dark:text-gray-400">
                                    Payable: {{ number_format($customer->payable_balance, 2) }}
                                </div>
                                <a href="{{ route('customer_refunds.create_for_customer', $customer->id) }}" class="mt-3 inline-flex items-center px-3 py-1 border border-transparent text-xs font-medium rounded text-white bg-yellow-500 hover:bg-yellow-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-yellow-500">
                                    Process Refund
                                </a>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>