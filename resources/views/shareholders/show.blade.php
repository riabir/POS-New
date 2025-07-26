<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-wrap justify-between items-center gap-4">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Shareholder Ledger: ') }} <span class="text-indigo-600 dark:text-indigo-400">{{ $shareholder->name }}</span>
            </h2>
            <div class="flex items-center gap-4">
                <a href="{{ route('shareholders.index') }}" class="text-sm text-gray-700 dark:text-gray-300 hover:underline">
                    ← Back to List
                </a>
                <a href="{{ route('shareholder_transactions.create', $shareholder) }}" class="inline-flex items-center px-4 py-2 bg-gray-800 dark:bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-white dark:text-gray-800 uppercase tracking-widest hover:bg-gray-700 dark:hover:bg-white active:bg-gray-900 dark:active:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                    Add Transaction
                </a>
            </div>
        </div>
    </x-slot>

    {{-- STYLES: Consistent styles for the application --}}
    <style>
        .status-badge { display: inline-block; padding: .25em .6em; font-size: 75%; font-weight: 700; line-height: 1; text-align: center; white-space: nowrap; vertical-align: baseline; border-radius: .25rem; color: #fff; }
        .status-credit { background-color: #28a745; }
        .status-debit { background-color: #dc3545; }
        .styled-table { width: 100%; border-collapse: collapse; }
        .styled-table thead tr { background-color: #f8f9fa; }
        .styled-table th { color: #333; text-align: left; font-weight: 600; }
        .styled-table th, .styled-table td { padding: 12px 15px; border: 1px solid #ddd; }
        .styled-table tbody tr:nth-of-type(even) { background-color: #f3f3f3; }
        .dark .styled-table thead tr { background-color: #374151; }
        .dark .styled-table th { color: #f3f4f6; }
        .dark .styled-table th, .dark .styled-table td { border-color: #4b5563; }
        .dark .styled-table tbody tr:nth-of-type(even) { background-color: #4b5563; }
    </style>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-6" role="alert">
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif

            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mb-8">
                <div class="p-6 grid grid-cols-1 md:grid-cols-3 gap-6 divide-y md:divide-y-0 md:divide-x divide-gray-200 dark:divide-gray-700">
                    <div class="pt-4 md:pt-0">
                        <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Shareholder</h3>
                        <p class="mt-1 text-lg font-semibold text-gray-900 dark:text-gray-100">{{ $shareholder->name }}</p>
                    </div>
                    <div class="pt-4 md:pt-0 md:pl-6 text-left md:text-center">
                        <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Status</h3>
                        <p class="mt-1 text-lg font-semibold text-gray-900 dark:text-gray-100">{{ $shareholder->is_active ? 'Active' : 'Inactive' }}</p>
                    </div>
                    <div class="pt-4 md:pt-0 md:pl-6 text-left md:text-right">
                        <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Current Balance</h3>
                        <p class="mt-1 text-3xl font-bold font-mono {{ $shareholder->currentBalance < 0 ? 'text-red-500' : 'text-green-600' }}">
                            ৳{{ number_format($shareholder->currentBalance, 2) }}
                        </p>
                    </div>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h3 class="text-xl font-semibold mb-4">Transaction History</h3>
                    <div class="overflow-x-auto">
                        <table class="styled-table">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Type</th>
                                    <th>Description</th>
                                    <th class="text-right">Debit</th>
                                    <th class="text-right">Credit</th>
                                    <th class="text-right">Balance</th>
                                </tr>
                            </thead>
                            <tbody>
                                {{-- === THIS IS THE CORRECTED LOGIC === --}}
                                {{-- We now loop through `$transactions` which is passed from the controller --}}
                                @forelse($transactions as $transaction)
                                    <tr>
                                        <td>{{ $transaction->transaction_date->format('d M, Y') }}</td>
                                        <td>
                                            @if($transaction->is_credit)
                                                <span class="status-badge status-credit">{{ $transaction->type }}</span>
                                            @else
                                                <span class="status-badge status-debit">{{ $transaction->type }}</span>
                                            @endif
                                        </td>
                                        <td>{{ $transaction->description }}</td>
                                        <td class="text-right font-mono text-red-500">
                                            {{-- The `is_debit` accessor from the model now works correctly --}}
                                            @if($transaction->is_debit)
                                                ৳{{ number_format($transaction->amount, 2) }}
                                            @endif
                                        </td>
                                        <td class="text-right font-mono text-green-600">
                                            @if($transaction->is_credit)
                                                ৳{{ number_format($transaction->amount, 2) }}
                                            @endif
                                        </td>
                                        <td class="text-right font-mono font-semibold text-gray-700 dark:text-gray-300">
                                            {{-- We simply print the running_balance calculated in the controller --}}
                                            ৳{{ number_format($transaction->running_balance, 2) }}
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center py-12">No transactions found for this shareholder.</td>
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
