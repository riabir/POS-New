<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Ledger for: {{ $customer->customer_name }}
        </h2>
    </x-slot>
    
    <style>
        .styled-table { 
            width: 100%; 
            border-collapse: collapse; 
            margin-top: 1rem; 
            font-size: 0.9em;
        }
        .styled-table thead tr { 
            background-color: #f8f9fa; 
            color: #333; 
            text-align: left; 
            font-weight: bold; 
        }
        .styled-table th, 
        .styled-table td { 
            padding: 12px 15px; 
            border: 1px solid #ddd; 
            vertical-align: top; 
        }
        .dark .styled-table thead tr { 
            background-color: #374151; 
            color: #f3f4f6; 
        }
        .dark .styled-table th, 
        .dark .styled-table td { 
            border-color: #4b5563; 
        }
        .text-right { 
            text-align: right; 
        }
        .transaction-type { 
            font-size: 0.75rem; 
            padding: 2px 6px; 
            border-radius: 4px; 
            display: inline-block;
            margin-bottom: 4px;
        }
        .account-asset { 
            background-color: #d1ecf1; 
            color: #0c5460; 
        }
        .account-liability { 
            background-color: #f8d7da; 
            color: #721c24; 
        }
        .summary-box { 
            background-color: #f8f9fa; 
            border-radius: 8px; 
            padding: 15px; 
            margin-bottom: 20px; 
        }
        .dark .summary-box { 
            background-color: #374151; 
        }
        .ledger-section { 
            margin-bottom: 30px; 
        }
        .section-title { 
            font-size: 1.1rem; 
            font-weight: bold; 
            margin-bottom: 10px; 
            border-bottom: 1px solid #ddd; 
            padding-bottom: 5px; 
        }
        .dark .section-title {
            border-bottom-color: #4b5563;
        }
        .styled-table tbody tr:nth-of-type(even) {
            background-color: #f3f3f3;
        }
        .dark .styled-table tbody tr:nth-of-type(even) {
            background-color: #1f2937;
        }
        .styled-table tbody tr:hover {
            background-color: #f1f1f1;
        }
        .dark .styled-table tbody tr:hover {
            background-color: #374151;
        }
    </style>
    
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <a href="{{ route('customer_ledgers.index') }}" class="text-blue-600 hover:underline mb-4 inline-block">← Back to Customer List</a>
            
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <!-- Customer Summary -->
                    <div class="summary-box">
                        <h3 class="font-bold text-lg mb-3">Customer Summary</h3>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div class="bg-blue-50 dark:bg-blue-900/30 p-4 rounded-lg">
                                <p class="text-sm text-gray-600 dark:text-gray-400">Available Advance</p>
                                <p class="text-xl font-bold">{{ number_format($customer->available_advance, 2) }}</p>
                            </div>
                            <div class="bg-red-50 dark:bg-red-900/30 p-4 rounded-lg">
                                <p class="text-sm text-gray-600 dark:text-gray-400">Payable Balance</p>
                                <p class="text-xl font-bold {{ $customer->payable_balance > 0 ? 'text-red-600' : 'text-green-600' }}">
                                    {{ number_format($customer->payable_balance, 2) }}
                                </p>
                            </div>
                            <div class="bg-purple-50 dark:bg-purple-900/30 p-4 rounded-lg">
                                <p class="text-sm text-gray-600 dark:text-gray-400">Net Position</p>
                                <p class="text-xl font-bold {{ $customer->net_position > 0 ? 'text-red-600' : 'text-green-600' }}">
                                    {{ number_format($customer->net_position, 2) }}
                                </p>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Asset Account (Advances) -->
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
                                    @foreach($customer->ledgers->where('account_type', 'asset')->sortBy('transaction_date') as $ledger)
                                        @php 
                                            $assetBalance += $ledger->credit - $ledger->debit;
                                        @endphp
                                        <tr>
                                            <td>{{ \Carbon\Carbon::parse($ledger->transaction_date)->format('d-M-Y') }}</td>
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
                    
                    <!-- Liability Account (Payables) -->
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
                                    @foreach($customer->ledgers->where('account_type', 'liability')->sortBy('transaction_date') as $ledger)
                                        @php 
                                            $liabilityBalance += $ledger->debit - $ledger->credit;
                                        @endphp
                                        <tr>
                                            <td>{{ \Carbon\Carbon::parse($ledger->transaction_date)->format('d-M-Y') }}</td>
                                            <td>
                                                <span class="transaction-type account-liability">{{ ucfirst($ledger->transaction_type) }}</span>
                                                {{ $ledger->description }}
                                                @if($ledger->sale)
                                                    <span class="text-gray-500 text-xs block">(Invoice: {{$ledger->sale->bill_no}})</span>
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
                    
                    <!-- Accounting Explanation -->
                    <div class="mt-6 p-4 bg-blue-50 dark:bg-blue-900 rounded-lg">
                        <h4 class="font-bold text-blue-800 dark:text-blue-200 mb-2">Accounting Explanation</h4>
                        <ul class="text-sm text-blue-700 dark:text-blue-300 list-disc pl-5 space-y-1">
                            <li><strong>Asset Account (Advances):</strong> Tracks money received from customers in advance</li>
                            <li><strong>Liability Account (Payables):</strong> Tracks money owed to customers for sales</li>
                            <li><strong>Sales:</strong> Recorded as debits in liability account (increase amount owed)</li>
                            <li><strong>Payments:</strong> Recorded as credits in liability account (decrease amount owed)</li>
                            <li><strong>Advances:</strong> Recorded as credits in asset account (increase customer's advance)</li>
                            <li><strong>Advance Adjustments:</strong> Recorded as debits in asset account (decrease customer's advance) and credits in liability account (decrease amount owed)</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>