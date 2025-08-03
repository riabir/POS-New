<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Sales Profit Report
        </h2>
    </x-slot>

    <style>
        .table { width: 100%; border-collapse: collapse; }
        .table th, .table td { padding: 12px 15px; border: 1px solid #ddd; text-align: left; }
        .dark .table th, .dark .table td { border-color: #4b5563; }
        .clickable-row { cursor: pointer; transition: background-color 0.2s; }
        .clickable-row:hover { background-color: #f3f4f6; }
        .dark .clickable-row:hover { background-color: #374151; }
        .filter-form { background-color: #f9f9f9; padding: 1.5rem; border-radius: 0.5rem; margin-bottom: 1.5rem; border: 1px solid #e2e8f0; }
        .dark .filter-form { background-color: #4a5568; border-color: #2d3748; }
        .filter-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 1rem; }
        .filter-buttons { display: flex; gap: 0.5rem; align-items: flex-end; }
    </style>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">

                    {{-- FILTER FORM --}}
                    <div class="filter-form">
                        <h3 class="text-lg font-semibold mb-3">Filter Report</h3>
                        <form action="{{ route('profit.index') }}" method="GET">
                            <div class="filter-grid">
                                
                                {{-- Date From --}}
                                <div>
                                    <label for="date_from" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Date From</label>
                                    <input type="date" name="date_from" id="date_from" value="{{ request('date_from') }}" class="mt-1 block w-full rounded-md shadow-sm">
                                </div>

                                {{-- Date To --}}
                                <div>
                                    <label for="date_to" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Date To</label>
                                    <input type="date" name="date_to" id="date_to" value="{{ request('date_to') }}" class="mt-1 block w-full rounded-md shadow-sm">
                                </div>
                                
                                {{-- Bill Number --}}
                                <div>
                                    <label for="bill_no" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Bill Number</label>
                                    <input type="text" name="bill_no" id="bill_no" value="{{ request('bill_no') }}" class="mt-1 block w-full rounded-md shadow-sm" placeholder="e.g., B-00123">
                                </div>

                                {{-- Customer ID --}}
                                <div>
                                    <label for="customer_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Customer ID</label>
                                    <input type="number" name="customer_id" id="customer_id" value="{{ request('customer_id') }}" class="mt-1 block w-full rounded-md shadow-sm" placeholder="e.g., 42">
                                </div>

                                {{-- Customer Name --}}
                                <div>
                                    <label for="customer_name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Customer Name</label>
                                    <input type="text" name="customer_name" id="customer_name" value="{{ request('customer_name') }}" class="mt-1 block w-full rounded-md shadow-sm" placeholder="Search by name...">
                                </div>

                                {{-- Customer Phone --}}
                                <div>
                                    <label for="customer_phone" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Customer Phone</label>
                                    <input type="text" name="customer_phone" id="customer_phone" value="{{ request('customer_phone') }}" class="mt-1 block w-full rounded-md shadow-sm" placeholder="Search by phone...">
                                </div>
                                
                                {{-- Filter and Clear Buttons --}}
                                <div class="filter-buttons">
                                    <button type="submit" class="btn btn-primary">Filter</button>
                                    <a href="{{ route('profit.index') }}" class="btn btn-secondary">Clear</a>
                                </div>
                            </div>
                        </form>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="table">
                            <thead class="bg-gray-100 dark:bg-gray-700">
                                <tr>
                                    <th>Sale Date</th>
                                    <th>Bill No</th>
                                    <th>Customer</th>
                                    <th class="text-right">Sale Amount</th>
                                    <th class="text-right">Net Profit</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($sales as $sale)
                                    <tr class="clickable-row" onclick="window.location='{{ route('profit.show', $sale) }}';" title="Click to view profit breakdown">
                                        <td>{{ $sale->bill_date->format('d M, Y') }}</td>
                                        <td>{{ $sale->bill_no }}</td>
                                        <td>
                                            <div>{{ $sale->customer->customer_name ?? 'N/A' }}</div>
                                            <div class="text-xs text-gray-500">ID: {{ $sale->customer_id }}</div>
                                        </td>
                                        <td class="text-right font-mono">৳{{ number_format($sale->grand_total, 2) }}</td>
                                        <td class="text-right font-mono font-bold {{ $sale->total_profit >= 0 ? 'text-green-600' : 'text-red-500' }}">
                                            ৳{{ number_format($sale->total_profit, 2) }}
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center py-4">No sales found matching your criteria.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                            <tfoot>
                                <tr class="bg-gray-100 dark:bg-gray-700 font-bold">
                                    <td colspan="3" class="text-right">Total for this page:</td>
                                    <td class="text-right font-mono">৳{{ number_format($sales->sum('grand_total'), 2) }}</td>
                                    <td class="text-right font-mono {{ $sales->sum('total_profit') >= 0 ? 'text-green-600' : 'text-red-500' }}">
                                        ৳{{ number_format($sales->sum(fn($sale) => $sale->total_profit), 2) }}
                                    </td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>

                    <div class="mt-4">
                        {{ $sales->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>