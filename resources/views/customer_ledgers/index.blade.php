<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Customer Ledgers
        </h2>
    </x-slot>
    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h3 class="text-lg font-medium">Select a Customer to View Ledger</h3>
                    <ul class="mt-4 list-disc list-inside">
                        @forelse($customers as $customer)
                            <li>
                                <a href="{{ route('customer_ledgers.show', $customer->id) }}" class="text-blue-600 hover:underline">
                                    {{ $customer->customer_name }}
                                </a>
                            </li>
                        @empty
                            <p>No customers with transactions found.</p>
                        @endforelse
                    </ul>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>