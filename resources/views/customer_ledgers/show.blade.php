<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Ledger for: {{ $customer->customer_name }}
        </h2>
    </x-slot>

    <style>
        .styled-table { width: 100%; border-collapse: collapse; margin-top: 1rem; }
        .styled-table thead tr { background-color: #f8f9fa; color: #333; text-align: left; }
        .styled-table th, .styled-table td { padding: 12px 15px; border: 1px solid #ddd; vertical-align: top; }
        .dark .styled-table thead tr { background-color: #374151; color: #f3f4f6; }
        .dark .styled-table th, .dark .styled-table td { border-color: #4b5563; }
        .text-right { text-align: right; }
        .balance-due { color: #dc3545; font-weight: bold; }
        .balance-paid { color: #28a745; font-weight: bold; }
    </style>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <a href="{{ route('customer.ledgers.index') }}" class="text-blue-600 hover:underline mb-4 inline-block">← Back to Customer List</a>
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
                                @forelse($customer->ledgers as $ledger)
                                    @php $balance += $ledger->credit - $ledger->debit; @endphp
                                    <tr>
                                        <td>{{ \Carbon\Carbon::parse($ledger->transaction_date)->format('d-m-Y') }}</td>
                                        <td>
                                            {{ $ledger->description }}
                                            @if($ledger->sale)
                                                <span class="text-gray-500 text-xs block">(PO: {{$ledger->sale->bill_no}})</span>
                                            @endif
                                            @if($ledger->notes)
                                                <br><small class="text-gray-500 dark:text-gray-400"><strong>Note:</strong> {{ $ledger->notes }}</small>
                                            @endif
                                        </td>
                                        <td class="text-right">
                                            {{ $ledger->debit > 0 ? number_format($ledger->debit, 2) : '-' }}
                                            @if($ledger->payment_type)
                                                <br><small class="text-gray-600 dark:text-gray-400 font-semibold">{{ $ledger->payment_type }}</small>
                                            @endif
                                            @if($ledger->received_by)
                                                <br><small class="text-gray-500 dark:text-gray-500">By: {{ $ledger->received_by }}</small>
                                            @endif
                                        </td>
                                        <td class="text-right">
                                            {{ $ledger->credit > 0 ? number_format($ledger->credit, 2) : '-' }}
                                            @if($ledger->bill_by)
                                                <br><small class="text-gray-500 dark:text-gray-500">By: {{ $ledger->bill_by }}</small>
                                            @endif
                                        </td>
                                        <td class="text-right {{ $balance > 0 ? 'balance-due' : 'balance-paid' }}">
                                            {{ number_format($balance, 2) }}
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center py-4">No transactions found.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                            <tfoot>
                                <tr class="font-bold bg-gray-100 dark:bg-gray-700">
                                    <td colspan="4" class="text-right">Current Balance:</td>
                                    <td class="text-right {{ $balance > 0 ? 'balance-due' : 'balance-paid' }}">
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