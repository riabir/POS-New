<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('All Sale Commissions') }}
        </h2>
    </x-slot>

    <style>
        .styled-table { width: 100%; border-collapse: collapse; }
        .styled-table thead tr { background-color: #f8f9fa; }
        .styled-table th { color: #333; text-align: left; font-weight: 600; }
        .styled-table th, .styled-table td { padding: 12px 15px; border: 1px solid #ddd; }
        .dark .styled-table thead tr { background-color: #374151; }
        .dark .styled-table th { color: #f3f4f6; }
        .dark .styled-table th, .dark .styled-table td { border-color: #4b5563; }
        .clickable-row:hover { background-color: #e9ecef; cursor: pointer; }
        .dark .clickable-row:hover { background-color: #4a5568; }
        .filter-form { background-color: #f9f9f9; padding: 1.5rem; border-radius: 0.5rem; margin-bottom: 1.5rem; border: 1px solid #e2e8f0; }
        .dark .filter-form { background-color: #4a5568; border-color: #2d3748; }
        .filter-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem; }
        .filter-buttons { display: flex; gap: 0.5rem; align-items: flex-end; }
    </style>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">

                    {{-- FILTER FORM --}}
                    <div class="filter-form">
                        <h3 class="text-lg font-semibold mb-3">Filter Commissions</h3>
                        <form action="{{ route('commissions.index') }}" method="GET">
                            <div class="filter-grid">
                                {{-- Date From --}}
                                <div>
                                    <label for="date_from" class="block text-sm font-medium">Date From</label>
                                    <input type="date" name="date_from" id="date_from" value="{{ request('date_from') }}" class="mt-1 block w-full rounded-md shadow-sm">
                                </div>
                                {{-- Date To --}}
                                <div>
                                    <label for="date_to" class="block text-sm font-medium">Date To</label>
                                    <input type="date" name="date_to" id="date_to" value="{{ request('date_to') }}" class="mt-1 block w-full rounded-md shadow-sm">
                                </div>
                                {{-- Bill Number --}}
                                <div>
                                    <label for="bill_no" class="block text-sm font-medium">Bill Number</label>
                                    <input type="text" name="bill_no" id="bill_no" value="{{ request('bill_no') }}" class="mt-1 block w-full rounded-md shadow-sm" placeholder="e.g., B-00123">
                                </div>
                                {{-- Recipient Type --}}
                                <div>
                                    <label for="recipient_type" class="block text-sm font-medium">Recipient Type</label>
                                    <select name="recipient_type" id="recipient_type" class="mt-1 block w-full rounded-md shadow-sm">
                                        <option value="">All Types</option>
                                        <option value="App\Models\Customer" @selected(request('recipient_type') == 'App\Models\Customer')>Customer</option>
                                        <option value="App\Models\Shareholder" @selected(request('recipient_type') == 'App\Models\Shareholder')>Shareholder</option>
                                    </select>
                                </div>
                                {{-- Recipient ID --}}
                                <div>
                                    <label for="recipient_id" class="block text-sm font-medium">Recipient ID</label>
                                    <input type="number" name="recipient_id" id="recipient_id" value="{{ request('recipient_id') }}" class="mt-1 block w-full rounded-md shadow-sm" placeholder="e.g., 15">
                                </div>
                                {{-- Recipient Name --}}
                                <div>
                                    <label for="recipient_name" class="block text-sm font-medium">Recipient Name</label>
                                    <input type="text" name="recipient_name" id="recipient_name" value="{{ request('recipient_name') }}" class="mt-1 block w-full rounded-md shadow-sm" placeholder="Search by name...">
                                </div>
                                {{-- Recipient Phone --}}
                                <div>
                                    <label for="recipient_phone" class="block text-sm font-medium">Recipient Phone</label>
                                    <input type="text" name="recipient_phone" id="recipient_phone" value="{{ request('recipient_phone') }}" class="mt-1 block w-full rounded-md shadow-sm" placeholder="Search by phone...">
                                </div>
                                {{-- Buttons --}}
                                <div class="filter-buttons">
                                    <button type="submit" class="btn btn-primary">Filter</button>
                                    <a href="{{ route('commissions.index') }}" class="btn btn-secondary">Clear</a>
                                </div>
                            </div>
                        </form>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="styled-table">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Sale Bill #</th>
                                    <th>Recipient</th>
                                    <th>Type</th>
                                    <th class="text-right">Amount</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($commissions as $commission)
                                    <tr class="clickable-row" data-href="{{ route('commissions.show', $commission) }}">
                                        <td>{{ $commission->created_at->format('d M, Y') }}</td>
                                        <td>
                                            @if($commission->sale)
                                                <a href="{{ route('sales.showPreview', $commission->sale_id) }}" class="text-indigo-600 hover:underline" target="_blank" onclick="event.stopPropagation()">
                                                    {{ $commission->sale->bill_no }}
                                                </a>
                                            @else
                                                <span class="text-gray-400">N/A</span>
                                            @endif
                                        </td>
                                        <td>
                                            <div>{{ $commission->recipient_name }}</div>
                                            <div class="text-xs text-gray-500">ID: {{ $commission->recipient_id }}</div>
                                        </td>
                                        <td>{{ Str::afterLast($commission->recipient_type, '\\') }}</td>
                                        <td class="text-right font-mono">৳{{ number_format($commission->amount, 2) }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center py-12">No commissions found matching your criteria.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                             {{-- ADD A TOTALS ROW AT THE BOTTOM --}}
                            <tfoot>
                                <tr class="bg-gray-100 dark:bg-gray-700 font-bold">
                                    <td colspan="4" class="text-right">Total for this page:</td>
                                    <td class="text-right font-mono">৳{{ number_format($commissions->sum('amount'), 2) }}</td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>

                    <div class="mt-8">
                        {{ $commissions->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const rows = document.querySelectorAll('.clickable-row');
            
            rows.forEach(row => {
                row.addEventListener('click', function(event) {
                    if (event.target.closest('a, button, input')) {
                        return;
                    }
                    window.location.href = this.dataset.href;
                });
            });
        });
    </script>
    @endpush
</x-app-layout>