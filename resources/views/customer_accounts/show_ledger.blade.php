<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Ledger for Bill: {{ $customer_account->sale->bill_no ?? 'N/A' }}
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
        .styled-table tbody tr:nth-of-type(even) {
            background-color: #f9f9f9;
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
        .bill-info-box {
            background-color: #f8f9fa;
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 20px;
        }
        .dark .bill-info-box {
            background-color: #374151;
        }
        .info-card {
            background-color: #fff;
            border-radius: 6px;
            padding: 12px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        }
        .dark .info-card {
            background-color: #1f2937;
            box-shadow: 0 1px 3px rgba(0,0,0,0.3);
        }
    </style>
    
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <a href="{{ route('customer_accounts.index') }}" class="text-blue-600 hover:underline mb-4 inline-block">← Back to Due Bills List</a>
            
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <!-- Bill Information Section -->
                    <div class="bill-info-box">
                        <h3 class="font-bold text-lg mb-4">Bill Information</h3>
                        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                            <div class="info-card">
                                <p class="text-sm text-gray-600 dark:text-gray-400">Original Bill Amount</p>
                                <p class="text-xl font-bold">{{ number_format($customer_account->amount, 2) }}</p>
                            </div>
                            <div class="info-card">
                                <p class="text-sm text-gray-600 dark:text-gray-400">Amount Paid</p>
                                <p class="text-xl font-bold text-green-600">{{ number_format($customer_account->paid_amount, 2) }}</p>
                            </div>
                            <div class="info-card">
                                <p class="text-sm text-gray-600 dark:text-gray-400">Current Balance</p>
                                <p class="text-xl font-bold {{ $customer_account->balance > 0 ? 'text-red-600' : 'text-green-600' }}">
                                    {{ number_format($customer_account->balance, 2) }}
                                </p>
                            </div>
                            <div class="info-card">
                                <p class="text-sm text-gray-600 dark:text-gray-400">Bill Status</p>
                                <p class="text-xl font-bold">
                                    <span class="inline-block px-3 py-1 text-xs font-semibold rounded-full 
                                        {{ $customer_account->status == 'paid' ? 'bg-green-100 text-green-800' : 
                                           ($customer_account->status == 'partially_paid' ? 'bg-yellow-100 text-yellow-800' : 
                                           'bg-red-100 text-red-800') }}">
                                        {{ ucwords(str_replace('_', ' ', $customer_account->status)) }}
                                    </span>
                                </p>
                            </div>
                        </div>
                        
                        <div class="mt-4 grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                            <div>
                                <p class="text-gray-600 dark:text-gray-400">Bill Date: {{ \Carbon\Carbon::parse($customer_account->sale->bill_date)->format('d M, Y') }}</p>
                            </div>
                            <div>
                                <p class="text-gray-600 dark:text-gray-400">Due Date: {{ \Carbon\Carbon::parse($customer_account->due_date)->format('d M, Y') }}</p>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Ledger Table Section -->
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
                                    @php 
                                        $balance += ($entry->debit - $entry->credit);
                                        $transactionTypeClass = '';
                                        switch($entry->transaction_type) {
                                            case 'sale':
                                                $transactionTypeClass = 'bg-red-100 text-red-800';
                                                break;
                                            case 'payment':
                                                $transactionTypeClass = 'bg-green-100 text-green-800';
                                                break;
                                            case 'advance':
                                                $transactionTypeClass = 'bg-blue-100 text-blue-800';
                                                break;
                                            case 'advance_adjustment':
                                                $transactionTypeClass = 'bg-cyan-100 text-cyan-800';
                                                break;
                                            case 'vat_adjustment':
                                            case 'tax_adjustment':
                                                $transactionTypeClass = 'bg-yellow-100 text-yellow-800';
                                                break;
                                            case 'other_adjustment':
                                                $transactionTypeClass = 'bg-gray-100 text-gray-800';
                                                break;
                                            case 'refund':
                                                $transactionTypeClass = 'bg-orange-100 text-orange-800';
                                                break;
                                        }
                                    @endphp
                                    <tr>
                                        <td>{{ \Carbon\Carbon::parse($entry->transaction_date)->format('d-M-Y') }}</td>
                                        <td>
                                            <span class="transaction-type {{ $transactionTypeClass }}">
                                                {{ Str::title(str_replace('_', ' ', $entry->transaction_type)) }}
                                            </span>
                                            {{ $entry->description }}
                                            @if($entry->notes)
                                                <br><small class="text-gray-500 dark:text-gray-400"><strong>Note:</strong> {{ $entry->notes }}</small>
                                            @endif
                                        </td>
                                        <td class="text-right text-red-600 font-mono">{{ $entry->debit > 0 ? number_format($entry->debit, 2) : '-' }}</td>
                                        <td class="text-right text-green-600 font-mono">{{ $entry->credit > 0 ? number_format($entry->credit, 2) : '-' }}</td>
                                        <td class="text-right font-bold font-mono {{ $balance == 0 ? 'text-green-600' : 'text-red-600' }}">
                                            {{ number_format($balance, 2) }}
                                        </td>
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
                    
                    <!-- Accounting Explanation -->
                    <div class="mt-6 p-4 bg-blue-50 dark:bg-blue-900 rounded-lg">
                        <h4 class="font-bold text-blue-800 dark:text-blue-200 mb-2">Understanding This Ledger</h4>
                        <ul class="text-sm text-blue-700 dark:text-blue-300 list-disc pl-5 space-y-1">
                            <li><strong>Original Bill Amount:</strong> The initial amount of the bill, which remains fixed</li>
                            <li><strong>Amount Paid:</strong> Total payments received from the customer</li>
                            <li><strong>Current Balance:</strong> The remaining amount due after all transactions</li>
                            <li><strong>Debits:</strong> Increase the amount owed (sales, additional taxes)</li>
                            <li><strong>Credits:</strong> Decrease the amount owed (payments, adjustments, refunds)</li>
                            <li><strong>Running Balance:</strong> Shows the cumulative effect of all transactions</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>