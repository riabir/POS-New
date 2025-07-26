<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                Sale Profit Breakdown
            </h2>
            <div class="flex items-center space-x-4">
                <a href="{{ route('sales.showPreview', $sale) }}" target="_blank" class="btn btn-info">View Customer Invoice</a>
                <a href="{{ route('profit.index') }}" class="btn btn-secondary">← Back to Profit Report</a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6 lg:p-8">

                    {{-- Header with Customer and Sale Info --}}
                    <div class="flex flex-wrap justify-between items-start mb-8 pb-4 border-b dark:border-gray-700 gap-4">
                        <div>
                            <h3 class="text-2xl font-bold text-gray-900 dark:text-gray-100">{{ $sale->customer->customer_name ?? 'N/A' }}</h3>
                            <p class="text-sm text-gray-600 dark:text-gray-400">{{ $sale->customer->phone ?? '' }}</p>
                        </div>
                        <div class="text-right text-sm">
                            <p class="font-mono text-lg">Bill No: <span class="font-semibold">{{ $sale->bill_no }}</span></p>
                            <p class="text-gray-500">Date: {{ $sale->bill_date->format('F j, Y') }}</p>
                        </div>
                    </div>

                    {{-- Items Table with Profit Breakdown --}}
                    <h4 class="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-4">Items Breakdown</h4>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead class="bg-gray-50 dark:bg-gray-700">
                                <tr>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Item</th>
                                    <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase">Qty</th>
                                    <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase">Sale Price (Unit)</th>
                                    <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase">Cost Price (Unit)</th>
                                    <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase">Gross Profit (Item)</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                @foreach($sale->items as $item)
                                <tr>
                                    <td class="px-4 py-4">
                                        <p class="font-medium text-gray-900 dark:text-gray-100">{{ $item->product->model ?? 'N/A' }}</p>
                                        <p class="text-xs text-gray-500">{{ $item->product->brand->name ?? '' }}</p>
                                    </td>
                                    <td class="px-4 py-4 text-center">{{ $item->quantity }}</td>
                                    <td class="px-4 py-4 text-right font-mono">৳{{ number_format($item->unit_price, 2) }}</td>
                                    <td class="px-4 py-4 text-right font-mono text-orange-500">৳{{ number_format($item->cost_price, 2) }}</td>
                                    <td class="px-4 py-4 text-right font-mono text-blue-600 font-semibold">৳{{ number_format(($item->unit_price - $item->cost_price) * $item->quantity, 2) }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    {{-- Financial Summary Section --}}
                    <div class="mt-8 flex justify-end">
                        <div class="w-full max-w-md space-y-3">
                            <h4 class="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-2">Financial Summary</h4>
                            
                            {{-- Customer-facing calculations --}}
                            <div class="flex justify-between text-sm border-t dark:border-gray-600 pt-2"><span class="text-gray-500 dark:text-gray-400">Subtotal:</span> <span class="font-mono">৳{{ number_format($sale->sub_total, 2) }}</span></div>
                            <div class="flex justify-between text-sm"><span class="text-gray-500 dark:text-gray-400">Discount:</span> <span class="font-mono text-red-500">- ৳{{ number_format($sale->discount, 2) }}</span></div>
                            <div class="flex justify-between font-bold text-base border-t dark:border-gray-600 pt-2"><span class="text-gray-900 dark:text-gray-100">Grand Total (Paid by Customer):</span> <span class="font-mono">৳{{ number_format($sale->grand_total, 2) }}</span></div>
                            
                            <hr class="dark:border-gray-700 my-4">

                            {{-- Internal profit calculations --}}
                            @php
                                $grossProfit = $sale->items->sum(fn($item) => ($item->unit_price - $item->cost_price) * $item->quantity);
                                $totalCommission = $sale->commissions->sum('amount');
                            @endphp
                            
                            <div class="flex justify-between text-sm"><span class="text-gray-500 dark:text-gray-400">Gross Profit (from Items):</span> <span class="font-mono text-blue-600">৳{{ number_format($grossProfit, 2) }}</span></div>
                            <div class="flex justify-between text-sm"><span class="text-gray-500 dark:text-gray-400">Sales Commission Cost:</span> <span class="font-mono text-red-500">- ৳{{ number_format($totalCommission, 2) }}</span></div>
                            
                            {{-- The $sale->total_profit accessor already calculates this, so it's guaranteed to be correct --}}
                            <div class="flex justify-between font-bold text-xl text-green-600 border-t-2 border-green-500 pt-2"><span class="">Net Profit:</span> <span class="font-mono">৳{{ number_format($sale->total_profit, 2) }}</span></div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>