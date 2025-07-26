<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            {{-- Stat Cards Grid --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">

                {{-- Today's Sales --}}
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm rounded-lg p-6">
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 truncate">Today's Sales</dt>
                    <dd class="mt-1 text-3xl font-semibold text-gray-900 dark:text-gray-100">৳{{ number_format($todaysSales, 2) }}</dd>
                </div>

                {{-- Monthly Sales --}}
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm rounded-lg p-6">
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 truncate">This Month's Sales</dt>
                    <dd class="mt-1 text-3xl font-semibold text-gray-900 dark:text-gray-100">৳{{ number_format($monthlySales, 2) }}</dd>
                </div>

                {{-- Yearly Sales --}}
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm rounded-lg p-6">
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 truncate">This Year's Sales</dt>
                    <dd class="mt-1 text-3xl font-semibold text-gray-900 dark:text-gray-100">৳{{ number_format($yearlySales, 2) }}</dd>
                </div>
                
                {{-- Customer Unpaid Amount --}}
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm rounded-lg p-6">
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 truncate">Customer Dues (Receivable)</dt>
                    <dd class="mt-1 text-3xl font-semibold text-yellow-600 dark:text-yellow-400">৳{{ number_format($customerUnpaidAmount, 2) }}</dd>
                </div>

                {{-- Vendor Unpaid Amount --}}
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm rounded-lg p-6">
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 truncate">Vendor Dues (Payable)</dt>
                    <dd class="mt-1 text-3xl font-semibold text-red-600 dark:text-red-400">৳{{ number_format($vendorUnpaidAmount, 2) }}</dd>
                </div>

                {{-- Company Asset (Stock) --}}
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm rounded-lg p-6">
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 truncate">Company Asset (Stock Value)</dt>
                    <dd class="mt-1 text-3xl font-semibold text-blue-600 dark:text-blue-400">৳{{ number_format($companyAssetValue, 2) }}</dd>
                </div>

                {{-- Liquid Cash --}}
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm rounded-lg p-6">
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 truncate">Liquid Cash (Approx.)</dt>
                    <dd class="mt-1 text-3xl font-semibold text-green-600 dark:text-green-400">৳{{ number_format($liquidCash, 2) }}</dd>
                </div>

            </div>

            {{-- Recent Sales Section --}}
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h3 class="text-lg font-semibold mb-4">Recent Sales Activity</h3>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                             <thead class="bg-gray-50 dark:bg-gray-700">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Bill No</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Customer</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Date</th>
                                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Amount</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                @forelse ($recentSales as $sale)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-indigo-600 dark:text-indigo-400">{{ $sale->bill_no }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-200">{{ $sale->customer->customer_name ?? 'N/A' }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">{{ $sale->bill_date->diffForHumans() }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-200 text-right font-mono">৳{{ number_format($sale->grand_total, 2) }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="px-6 py-12 text-center text-gray-500">No recent sales found.</td>
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