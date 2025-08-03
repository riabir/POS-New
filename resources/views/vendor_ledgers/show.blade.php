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
        .balance-due { color: #dc3545; font-weight: bold; }
        .balance-paid { color: #28a745; font-weight: bold; }
        .transaction-type { font-size: 0.75rem; padding: 2px 6px; border-radius: 4px; }
        .account-asset { background-color: #d1ecf1; color: #0c5460; }
        .account-liability { background-color: #f8d7da; color: #721c24; }
        .summary-box { background-color: #f8f9fa; border-radius: 8px; padding: 15px; margin-bottom: 20px; }
        .dark .summary-box { background-color: #374151; }
        .ledger-section { margin-bottom: 30px; }
        .section-title { font-size: 1.1rem; font-weight: bold; margin-bottom: 10px; border-bottom: 1px solid #ddd; padding-bottom: 5px; }
    </style>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <a href="{{ route('vendor.ledgers.index') }}" class="text-blue-600 hover:underline mb-4 inline-block">← Back to Vendor List</a>
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <div class="summary-box">
                        <h3 class="font-bold text-lg mb-3">Vendor Summary</h3>
                        <div class="grid grid-cols-3 gap-4">
                            <div>
                                <p class="text-sm text-gray-600 dark:text-gray-400">Available Advance</p>
                                <p class="text-xl font-bold">{{ number_format($vendor->available_advance, 2) }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600 dark:text-gray-400">Payable Balance</p>
                                <p class="text-xl font-bold {{ $vendor->payable_balance > 0 ? 'balance-due' : 'balance-paid' }}">
                                    {{ number_format($vendor->payable_balance, 2) }}
                                </p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600 dark:text-gray-400">Net Position</p>
                                <p class="text-xl font-bold {{ $vendor->net_position > 0 ? 'balance-due' : 'balance-paid' }}">
                                    {{ number_format($vendor->net_position, 2) }}
                                </p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="ledger-section">
                        <h4 class="section-title">Asset Account (Advances)</h4>
                        <div class="overflow-x-auto">
                            <table class="styled-table">
                                <thead>
                                    <tr>
                                        <th>Date</th>
                                        <th>Description</th>
                                        <th class="text-right">Debit</th>
                                        <th class="text-right">Credit</th>
                                        <th class="text-right">Balance</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php $assetBalance = 0; @endphp
                                    @foreach($vendor->ledgers->where('account_type', 'asset')->sortBy('transaction_date') as $ledger)
                                        @php 
                                            $assetBalance += $ledger->debit - $ledger->credit;
                                        @endphp
                                        <tr>
                                            <td>{{ \Carbon\Carbon::parse($ledger->transaction_date)->format('d-m-Y') }}</td>
                                            <td>
                                                <span class="transaction-type account-asset">{{ ucfirst($ledger->transaction_type) }}</span>
                                                {{ $ledger->description }}
                                                @if($ledger->notes)
                                                    <br><small class="text-gray-500 dark:text-gray-400"><strong>Note:</strong> {{ $ledger->notes }}</small>
                                                @endif
                                            </td>
                                            <td class="text-right">
                                                {{ $ledger->debit > 0 ? number_format($ledger->debit, 2) : '-' }}
                                            </td>
                                            <td class="text-right">
                                                {{ $ledger->credit > 0 ? number_format($ledger->credit, 2) : '-' }}
                                            </td>
                                            <td class="text-right">
                                                {{ number_format($assetBalance, 2) }}
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr class="font-bold bg-gray-100 dark:bg-gray-700">
                                        <td colspan="4" class="text-right">Asset Balance:</td>
                                        <td class="text-right">
                                            {{ number_format($assetBalance, 2) }}
                                        </td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                    
                    <div class="ledger-section">
                        <h4 class="section-title">Liability Account (Payables)</h4>
                        <div class="overflow-x-auto">
                            <table class="styled-table">
                                <thead>
                                    <tr>
                                        <th>Date</th>
                                        <th>Description</th>
                                        <th class="text-right">Debit</th>
                                        <th class="text-right">Credit</th>
                                        <th class="text-right">Balance</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php $liabilityBalance = 0; @endphp
                                    @foreach($vendor->ledgers->where('account_type', 'liability')->sortBy('transaction_date') as $ledger)
                                        @php 
                                            $liabilityBalance += $ledger->credit - $ledger->debit;
                                        @endphp
                                        <tr>
                                            <td>{{ \Carbon\Carbon::parse($ledger->transaction_date)->format('d-m-Y') }}</td>
                                            <td>
                                                <span class="transaction-type account-liability">{{ ucfirst($ledger->transaction_type) }}</span>
                                                {{ $ledger->description }}
                                                @if($ledger->purchase)
                                                    <span class="text-gray-500 text-xs block">(PO: {{$ledger->purchase->purchase_no}})</span>
                                                @endif
                                                @if($ledger->notes)
                                                    <br><small class="text-gray-500 dark:text-gray-400"><strong>Note:</strong> {{ $ledger->notes }}</small>
                                                @endif
                                            </td>
                                            <td class="text-right">
                                                {{ $ledger->debit > 0 ? number_format($ledger->debit, 2) : '-' }}
                                            </td>
                                            <td class="text-right">
                                                {{ $ledger->credit > 0 ? number_format($ledger->credit, 2) : '-' }}
                                            </td>
                                            <td class="text-right">
                                                {{ number_format($liabilityBalance, 2) }}
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr class="font-bold bg-gray-100 dark:bg-gray-700">
                                        <td colspan="4" class="text-right">Liability Balance:</td>
                                        <td class="text-right">
                                            {{ number_format($liabilityBalance, 2) }}
                                        </td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                    
                    <div class="mt-6 p-4 bg-blue-50 dark:bg-blue-900 rounded-lg">
                        <h4 class="font-bold text-blue-800 dark:text-blue-200 mb-2">How to Read This Ledger</h4>
                        <ul class="text-sm text-blue-700 dark:text-blue-300 list-disc pl-5 space-y-1">
                            <li><strong>Asset Account (Advances):</strong> Tracks money you've paid to the vendor in advance</li>
                            <li><strong>Liability Account (Payables):</strong> Tracks money you owe to the vendor</li>
                            <li><strong>Net Position:</strong> Liability Balance minus Asset Balance</li>
                            <li><strong>Positive Net Position:</strong> You owe the vendor</li>
                            <li><strong>Negative Net Position:</strong> Vendor owes you</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>