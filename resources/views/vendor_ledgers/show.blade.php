<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Ledger for: {{ $vendor->vendor_name }}
        </h2>
    </x-slot>

    <style>
        .styled-table { width: 100%; border-collapse: collapse; margin-top: 1rem; }
        .styled-table thead tr { background-color: #f8f9fa; color: #333; text-align: left; }
        .styled-table th, .styled-table td { padding: 12px 15px; border: 1px solid #ddd; vertical-align: top; }
        .dark .styled-table thead tr { background-color: #374151; color: #f3f4f6; }
        .dark .styled-table th, .dark .styled-table td { border-color: #4b5563; }
        .text-right { text-align: right; }
        .balance-due { color: #dc3545; font-weight: bold; } /* We owe vendor */
        .balance-paid { color: #28a745; font-weight: bold; } /* Vendor owes us (Advance) */
        .dark .balance-due { color: #f87171; }
        .dark .balance-paid { color: #4ade80; }
    </style>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <a href="{{ route('vendor.ledgers.index') }}" class="text-indigo-600 dark:text-indigo-400 hover:underline mb-4 inline-block">← Back to Vendor List</a>
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <div class="overflow-x-auto">
                        <table class="styled-table">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Description</th>
                                    <th class="text-right">Debit (Payment)</th>
                                    <th class="text-right">Credit (Bill)</th>
                                    <th class="text-right">Balance</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php $balance = 0; @endphp
                                @forelse($vendor->ledgers as $ledger)
                                    @php
                                        // A credit (bill) increases what we OWE them (positive balance).
                                        // A debit (payment) decreases what we OWE them (moves towards negative/advance).
                                        $balance += ($ledger->credit - $ledger->debit);
                                    @endphp
                                    <tr>
                                        <td>{{ $ledger->transaction_date->format('d M, Y') }}</td>
                                        <td>
                                            {{ $ledger->description }}
                                            @if($ledger->purchase)
                                                <span class="text-gray-500 text-xs block">(PO: {{$ledger->purchase->purchase_no}})</span>
                                            @endif
                                            @if($ledger->notes)
                                                <br><small class="text-gray-500 dark:text-gray-400"><strong>Note:</strong> {{ $ledger->notes }}</small>
                                            @endif
                                        </td>
                                        <td class="text-right">
                                            @if($ledger->debit > 0)
                                                {{ number_format($ledger->debit, 2) }}
                                                @if($ledger->payment_type)
                                                    <br><small class="text-gray-600 dark:text-gray-400 font-semibold">{{ $ledger->payment_type }}</small>
                                                @endif
                                                @if($ledger->received_by)
                                                    <br><small class="text-gray-500 dark:text-gray-500">By: {{ $ledger->received_by }}</small>
                                                @endif
                                            @else
                                                -
                                            @endif
                                        </td>
                                        <td class="text-right">
                                            @if($ledger->credit > 0)
                                                {{ number_format($ledger->credit, 2) }}
                                                @if($ledger->bill_by)
                                                    <br><small class="text-gray-500 dark:text-gray-500">By: {{ $ledger->bill_by }}</small>
                                                @endif
                                            @else
                                                 -
                                            @endif
                                        </td>
                                        <td class="text-right {{ $balance > 0 ? 'balance-due' : 'balance-paid' }}">
                                            {{ number_format(abs($balance), 2) }}
                                            <span class="text-xs">{{ $balance >= 0 ? 'Due' : 'Adv' }}</span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center py-4">No transactions found for this vendor.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                            <tfoot>
                                @php
                                    $final_balance = $vendor->ledgers->sum('credit') - $vendor->ledgers->sum('debit');
                                @endphp
                                <tr class="font-bold bg-gray-100 dark:bg-gray-700">
                                    <td colspan="4" class="text-right text-base">Final Balance:</td>
                                    <td class="text-right text-base {{ $final_balance > 0 ? 'balance-due' : 'balance-paid' }}">
                                        {{ number_format(abs($final_balance), 2) }}
                                        <span class="text-sm font-semibold">{{ $final_balance >= 0 ? 'Due' : 'Advance' }}</span>
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