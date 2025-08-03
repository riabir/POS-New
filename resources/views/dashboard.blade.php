<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Page header with date and time -->
            <div class="mb-6">
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Business Dashboard</h1>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                    {{ now()->format('l, F j, Y') }}
                </p>
            </div>

            {{-- Row 1: Key Financial Health Indicators --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                {{-- Total Assets --}}
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-lg rounded-xl border-l-4 border-blue-500">
                    <div class="p-5">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 truncate">Total Assets</dt>
                            <dd class="mt-1 text-3xl font-semibold text-blue-600 dark:text-blue-400">৳{{ number_format($totalAssets, 2) }}</dd>
                        </dl>
                    </div>
                </div>
                {{-- Total Liabilities --}}
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-lg rounded-xl border-l-4 border-yellow-500">
                    <div class="p-5">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 truncate">Total Liabilities</dt>
                            <dd class="mt-1 text-3xl font-semibold text-yellow-600 dark:text-yellow-400">৳{{ number_format($totalLiabilities, 2) }}</dd>
                        </dl>
                    </div>
                </div>
                {{-- Liquid Cash --}}
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-lg rounded-xl border-l-4 border-green-500">
                    <div class="p-5">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 truncate">Liquid Cash (In Hand)</dt>
                            <dd class="mt-1 text-3xl font-semibold text-green-600 dark:text-green-400">৳{{ number_format($liquidCash, 2) }}</dd>
                        </dl>
                    </div>
                </div>
                {{-- Inventory Value --}}
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-lg rounded-xl border-l-4 border-purple-500">
                    <div class="p-5">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 truncate">Inventory Value</dt>
                            <dd class="mt-1 text-3xl font-semibold text-purple-600 dark:text-purple-400">৳{{ number_format($inventoryValue, 2) }}</dd>
                        </dl>
                    </div>
                </div>
            </div>

            {{-- Row 2: Receivables & Payables Details --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                {{-- Customer Dues --}}
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-lg rounded-xl border-l-4 border-teal-500">
                    <div class="p-5">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 truncate">Customer Dues</dt>
                            <dd class="mt-1 text-3xl font-semibold text-teal-600 dark:text-teal-400">৳{{ number_format($customerDues, 2) }}</dd>
                        </dl>
                    </div>
                </div>
                {{-- Vendor Dues --}}
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-lg rounded-xl border-l-4 border-red-500">
                    <div class="p-5">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 truncate">Vendor Dues</dt>
                            <dd class="mt-1 text-3xl font-semibold text-red-600 dark:text-red-400">৳{{ number_format($vendorDues, 2) }}</dd>
                        </dl>
                    </div>
                </div>
                {{-- Customer Advances Held --}}
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-lg rounded-xl border-l-4 border-orange-500">
                    <div class="p-5">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 truncate">Customer Advances (Held)</dt>
                            <dd class="mt-1 text-3xl font-semibold text-orange-600 dark:text-orange-400">৳{{ number_format($customerAdvancesHeld, 2) }}</dd>
                        </dl>
                    </div>
                </div>
                {{-- Vendor Advances Paid --}}
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-lg rounded-xl border-l-4 border-indigo-500">
                    <div class="p-5">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 truncate">Vendor Advances (Paid)</dt>
                            <dd class="mt-1 text-3xl font-semibold text-indigo-600 dark:text-indigo-400">৳{{ number_format($vendorAdvancesPaid, 2) }}</dd>
                        </dl>
                    </div>
                </div>
            </div>

            {{-- Your existing charts and recent sales tables can remain below --}}
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
                <div class="bg-white dark:bg-gray-800 shadow-lg rounded-xl p-6">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Sales Performance</h3>
                     <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                        <div class="text-center">
                            <div class="text-2xl font-bold text-blue-600 dark:text-blue-400">৳{{ number_format($todaysSales, 2) }}</div>
                            <div class="text-sm text-gray-500 dark:text-gray-400">Today</div>
                        </div>
                        <div class="text-center">
                            <div class="text-2xl font-bold text-green-600 dark:text-green-400">৳{{ number_format($monthlySales, 2) }}</div>
                            <div class="text-sm text-gray-500 dark:text-gray-400">This Month</div>
                        </div>
                        <div class="text-center">
                            <div class="text-2xl font-bold text-purple-600 dark:text-purple-400">৳{{ number_format($yearlySales, 2) }}</div>
                            <div class="text-sm text-gray-500 dark:text-gray-400">This Year</div>
                        </div>
                    </div>
                    {{-- Placeholder for a chart library --}}
                    <div class="h-60 flex items-center justify-center bg-gray-50 dark:bg-gray-750 rounded-lg">
                        <p class="text-gray-500">Sales Chart Area</p>
                    </div>
                </div>

                <div class="bg-white dark:bg-gray-800 shadow-lg rounded-xl p-6">
                     <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Recent Sales</h3>
                     <div class="overflow-x-auto">
                        <table class="min-w-full">
                            <tbody>
                                @forelse($recentSales as $sale)
                                    <tr class="border-b border-gray-200 dark:border-gray-700">
                                        <td class="py-3 px-2 text-sm text-gray-600 dark:text-gray-300">{{ $sale->customer->customer_name ?? 'N/A' }}</td>
                                        <td class="py-3 px-2 text-sm text-gray-500 dark:text-gray-400">{{ $sale->bill_no }}</td>
                                        <td class="py-3 px-2 text-sm text-right font-medium text-gray-800 dark:text-gray-100">৳{{ number_format($sale->grand_total, 2) }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="text-center py-8 text-gray-500">No recent sales.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>