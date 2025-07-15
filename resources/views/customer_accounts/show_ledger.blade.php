<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Ledger for Bill: {{ $customer_account->sale->bill_no ?? 'N/A' }}
        </h2>
    </x-slot>

    <style>
        .styled-table { width: 100%; border-collapse: collapse; margin-top: 1rem; }
        .styled-table thead tr { background-color: #f8f9fa; color: #333; text-align: left; font-weight: bold; }
        .styled-table th, .styled-table td { padding: 12px 15px; border: 1px solid #ddd; vertical-align: top; }
        .dark .styled-table thead tr { background-color: #374151; color: #f3f4f6; }
        .dark .styled-table th, .dark .styled-table td { border-color: #4b5563; }
        .text-right { text-align: right; }
    </style>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <a href="{{ route('customer_accounts.index') }}" class="text-blue-600 hover:underline mb-4 inline-block">← Back to Due Bills List</a>
            
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <div class="overflow-x-auto">
                        <table class="styled-table">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Description / Transaction Type</th>
                                    <th class="text-right">Debit (Bill)</th>
                                    <th class="text-right">Credit (Payment)</th>
                                    <th class="text-right">Running Balance</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php $balance = 0; @endphp
                                @forelse($customer_account->sale->ledgers as $entry)
                                    @php $balance += ($entry->debit - $entry->credit); @endphp
                                    <tr>
                                        <td>{{ \Carbon\Carbon::parse($entry->transaction_date)->format('d-M-Y') }}</td>
                                        <td>
                                            {{ $entry->description }}
                                            <span class="text-xs text-gray-500 block">
                                                ({{ Str::title(str_replace('_', ' ', $entry->transaction_type)) }})
                                            </span>
                                             @if($entry->notes)
                                                <br><small class="text-gray-500 dark:text-gray-400"><strong>Note:</strong> {{ $entry->notes }}</small>
                                            @endif
                                        </td>
                                        <td class="text-right text-red-600 font-mono">{{ $entry->debit > 0 ? number_format($entry->debit, 2) : '-' }}</td>
                                        <td class="text-right text-green-600 font-mono">{{ $entry->credit > 0 ? number_format($entry->credit, 2) : '-' }}</td>
                                        <td class="text-right font-bold font-mono">{{ number_format($balance, 2) }}</td>
                                    </tr>
                                @empty
                                    <tr><td colspan="5" class="text-center py-4">No transactions found for this specific bill.</td></tr>
                                @endforelse
                            </tbody>
                            <tfoot>
                                <tr class="font-bold bg-gray-100 dark:bg-gray-700">
                                    <td colspan="4" class="text-right">Final Bill Balance:</td>
                                    <td class="text-right font-bold font-mono @if($balance == 0) text-green-600 @else text-red-600 @endif">
                                        {{ number_format($balance, 2) }}
                                    </td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>