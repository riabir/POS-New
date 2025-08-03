<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Employee Payouts') }}
            </h2>
            <div class="flex items-center gap-4">
                <a href="{{ route('payouts.createBulk') }}" class="inline-flex items-center px-4 py-2 bg-gray-800 dark:bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-white dark:text-gray-800 uppercase tracking-widest hover:bg-gray-700 dark:hover:bg-white active:bg-gray-900 dark:active:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                    Bulk Payout
                </a>
                <a href="{{ route('payouts.create') }}" class="inline-flex items-center px-4 py-2 bg-gray-800 dark:bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-white dark:text-gray-800 uppercase tracking-widest hover:bg-gray-700 dark:hover:bg-white active:bg-gray-900 dark:active:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                    Add Individual Payout
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-6" role="alert">
                <span class="block sm:inline">{{ session('success') }}</span>
            </div>
            @endif

            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">

                    <div class="overflow-x-auto">
                        <table class="styled-table">
                            <thead>
                                <tr>
                                    <th>Employee</th>
                                    <th>Payout Type</th>
                                    <th>Amount</th>
                                    <th>Payout Date</th>
                                    <th>Notes</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($payouts as $payout)
                                <tr class="clickable-row" data-href="{{ route('payouts.show', $payout->id) }}">
                                    <td>
                                        <div class="font-medium text-gray-900 dark:text-white">
                                            {{ $payout->employee->fullName ?? 'N/A' }}
                                        </div>
                                        <div class="text-sm text-gray-500 dark:text-gray-400">
                                            ID: {{ $payout->employee->emp_id ?? 'N/A' }}
                                        </div>
                                    </td>
                                    <td>
                                        <span class="px-2 py-1 font-semibold leading-tight text-blue-700 bg-blue-100 rounded-full dark:bg-blue-700 dark:text-blue-100">
                                            {{ $payout->payout_type }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="font-semibold text-green-600 dark:text-green-400">
                                            ${{ number_format($payout->amount, 2) }}
                                        </div>
                                    </td>
                                    <td>{{ $payout->payout_date->format('d M, Y') }}</td>
                                    <td class="text-sm text-gray-600 dark:text-gray-400">
                                        {{-- Limit notes to 40 characters to keep table clean --}}
                                        {{ \Illuminate\Support\Str::limit($payout->notes, 40, '...') }}
                                    </td>
                                    <td>
                                        <div class="actions-container action-buttons">
                                            <a href="{{ route('payouts.edit', $payout->id) }}" class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-200" title="Edit">Edit</a>
                                            
                                            @if (auth()->user()->role === 'admin')
                                            <form method="POST" action="{{ route('payouts.destroy', $payout->id) }}" onsubmit="return confirm('Are you sure you want to delete this payout?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-200" title="Delete">
                                                    Delete
                                                </button>
                                            </form>
                                             @endif
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="text-center py-12 text-gray-500">
                                        No payouts found.
                                        <a href="{{ route('payouts.create') }}" class="text-indigo-600 hover:underline">Add the first one!</a>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination Links -->
                    <div class="mt-6">
                        {{ $payouts->links() }}
                    </div>

                </div>
            </div>
        </div>
    </div>

    {{-- You can move these styles to your main app.css to avoid repetition --}}
    <style>
        .styled-table {
            width: 100%;
            border-collapse: collapse;
        }

        .styled-table thead tr {
            background-color: #f8f9fa;
            color: #333;
            text-align: left;
        }

        .styled-table th,
        .styled-table td {
            padding: 12px 15px;
            border-bottom: 1px solid #ddd;
        }

        .dark .styled-table thead tr {
            background-color: #374151;
            color: #f3f4f6;
        }

        .dark .styled-table th,
        .dark .styled-table td {
            border-color: #4b5563;
        }

        .dark .styled-table tbody tr {
            background-color: #374151;
        }

        .dark .styled-table td {
            color: #f3f4f6;
        }

        .actions-container {
            display: flex;
            gap: 1rem;
            align-items: center;
        }

        .clickable-row {
            cursor: pointer;
            transition: background-color 0.2s ease-in-out;
        }

        .clickable-row:hover {
            background-color: #f1f5f9;
        }

        .dark .clickable-row:hover {
            background-color: #4a5568;
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Find all table rows with the data-href attribute
            document.querySelectorAll('tr[data-href]').forEach(row => {
                row.addEventListener('click', function(event) {
                    // Make sure the click is not on an action button or link inside the actions container
                    if (!event.target.closest('.action-buttons')) {
                        window.location.href = this.dataset.href;
                    }
                });
            });
            // Stop propagation on action buttons to prevent the row click
            document.querySelectorAll('.action-buttons a, .action-buttons button').forEach(action => {
                action.addEventListener('click', event => event.stopPropagation());
            });
        });
    </script>
</x-app-layout>