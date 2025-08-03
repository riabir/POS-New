<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Expenses') }}
            </h2>
            <a href="{{ route('expenses.create') }}" class="btn btn-primary">New Expense</a>
        </div>
    </x-slot>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            {{-- Stat Cards --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-sm">
                    <h4 class="text-gray-500 dark:text-gray-400">Number Of Unapproved Expenses</h4>
                    <p class="text-3xl font-bold mt-2">{{ $unapprovedCount }}</p>
                </div>
                <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-sm">
                    <h4 class="text-gray-500 dark:text-gray-400">Unapproved Expenses Total</h4>
                    <p class="text-3xl font-bold mt-2">৳{{ number_format($unapprovedTotal, 2) }}</p>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <div class="border-b border-gray-200 dark:border-gray-700 mb-4">
                        <nav class="-mb-px flex space-x-8" aria-label="Tabs">
                            @php $statuses = ['pending', 'approved', 'rejected', 'paid']; @endphp
                            @foreach($statuses as $s)
                            <a href="{{ route('expenses.index', ['status' => $s]) }}"
                                class="{{ $status == $s ? 'border-indigo-500 text-indigo-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }} whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                                {{ ucfirst($s) }}
                            </a>
                            @endforeach
                        </nav>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead class="bg-gray-50 dark:bg-gray-700">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium uppercase">Employee</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium uppercase">Type</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium uppercase">Period</th>
                                    <th class="px-6 py-3 text-right text-xs font-medium uppercase">Total</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium uppercase">Submitted</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium uppercase">Action</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                @forelse($expenses as $expense)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm">{{ $expense->employee->full_name }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm">{{ $expense->expenseType->name }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm">{{ $expense->from_date->format('d/m/y') }} - {{ $expense->to_date->format('d/m/y') }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-right font-mono">৳{{ number_format($expense->total, 2) }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm">{{ $expense->created_at->diffForHumans() }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                                        @if($status === 'pending' || $status === 'verified')
                                        @if (auth()->user()->role === 'admin')
                                        <a href="{{ route('expenses.approve.form', $expense) }}" class="btn btn-sm btn-info">Review</a>
                                        @endif
                                        @elseif($status === 'approved')
                                        <a href="{{ route('expenses.pay.form', $expense) }}" class="btn btn-sm btn-success">Pay Now</a>
                                        @else
                                        -
                                        @endif
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="text-center py-8">No expenses found in this category.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-4">{{ $expenses->links() }}</div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>