<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Sales Report
        </h2>
    </x-slot>

    <style>
        .status-badge {
            display: inline-block;
            padding: .25em .6em;
            font-size: 75%;
            font-weight: 700;
            line-height: 1;
            text-align: center;
            white-space: nowrap;
            vertical-align: baseline;
            border-radius: .25rem;
            color: #fff;
        }

        .status-paid {
            background-color: #28a745;
        }

        .status-due {
            background-color: #dc3545;
        }

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
            border: 1px solid #ddd;
        }

        .dark .styled-table thead tr {
            background-color: #374151;
            color: #f3f4f6;
        }

        .dark .styled-table th,
        .dark .styled-table td {
            border-color: #4b5563;
        }

        .actions-container {
            display: flex;
            gap: 0.5rem;
            align-items: center;
        }

        .clickable-row {
            cursor: pointer;
        }

        .clickable-row:hover {
            background-color: #e9ecef;
        }

        .dark .clickable-row:hover {
            background-color: #4a5568;
        }

        .filter-form {
            background-color: #f9f9f9;
            padding: 1.5rem;
            border-radius: 0.5rem;
            margin-bottom: 1.5rem;
            border: 1px solid #e2e8f0;
        }

        .dark .filter-form {
            background-color: #4a5568;
            border-color: #2d3748;
        }

        .filter-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1rem;
        }

        .filter-buttons {
            display: flex;
            gap: 0.5rem;
            align-items: flex-end;
        }
    </style>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">

                    @if(session('success'))
                    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                        <span class="block sm:inline">{{ session('success') }}</span>
                    </div>
                    @endif

                    <div class="filter-form">
                        <form action="{{ route('sales.index') }}" method="GET">
                            <div class="filter-grid">
                                <div>
                                    <label for="date_from" class="block text-sm font-medium">Date From</label>
                                    <input type="date" name="date_from" id="date_from" value="{{ request('date_from') }}" class="mt-1 block w-full rounded-md shadow-sm">
                                </div>
                                <div>
                                    <label for="date_to" class="block text-sm font-medium">Date To</label>
                                    <input type="date" name="date_to" id="date_to" value="{{ request('date_to') }}" class="mt-1 block w-full rounded-md shadow-sm">
                                </div>
                                <div>
                                    <label for="phone" class="block text-sm font-medium">Customer Phone</label>
                                    <input type="text" name="phone" id="phone" value="{{ request('phone') }}" class="mt-1 block w-full rounded-md shadow-sm">
                                </div>
                                <div>
                                    <label for="bill_no" class="block text-sm font-medium">Bill No.</label>
                                    <input type="text" name="bill_no" id="bill_no" value="{{ request('bill_no') }}" class="mt-1 block w-full rounded-md shadow-sm">
                                </div>
                                <div>
                                    <label for="model" class="block text-sm font-medium">Product Model</label>
                                    <input type="text" name="model" id="model" value="{{ request('model') }}" class="mt-1 block w-full rounded-md shadow-sm">
                                </div>
                                <div class="filter-buttons">
                                    <button type="submit" class="btn btn-primary">Filter</button>
                                    <a href="{{ route('sales.index') }}" class="btn btn-secondary">Clear</a>
                                </div>
                            </div>
                        </form>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="styled-table">
                            <thead>
                                <tr>
                                    <th>Customer</th>
                                    <th>Bill No</th>
                                    <th>Sale Date</th>
                                    <th>Grand Total</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($sales as $sale)
                                <tr class="clickable-row" data-sale-id="{{ $sale->id }}" title="Click to view invoice preview">
                                    <td>{{ $sale->customer->customer_name ?? 'N/A' }}</td>
                                    <td>{{ $sale->bill_no }}</td>
                                    <td>{{ \Carbon\Carbon::parse($sale->bill_date)->format('d-m-Y') }}</td>
                                    <td>{{ number_format($sale->grand_total, 2) }}</td>
                                    <td>
                                        @if($sale->status == 'paid')
                                        <span class="status-badge status-paid">Paid</span>
                                        @else
                                        <span class="status-badge status-due">Due</span>
                                        @endif
                                    </td>
                                    <td>
                                        {{-- Blade directive to check if the logged-in user's role is 'admin' --}}
                                        @if (auth()->user()->role === 'admin')
                                        <div class="actions-container">
                                            {{-- Edit Button --}}
                                            <a href="{{ route('sales.edit', $sale) }}" class="btn btn-sm btn-warning" onclick="event.stopPropagation();">
                                                Edit
                                            </a>
                                            {{-- Delete Button --}}
                                            <form method="POST" action="{{ route('sales.destroy', $sale->id) }}">
                                                @csrf
                                                @method('DELETE')
                                                <button onclick="event.stopPropagation(); return confirm('Are you sure? This action cannot be undone.')" type="submit" class="btn btn-sm btn-danger">Delete</button>
                                            </form>
                                        </div>
                                        @else
                                        {{-- For non-admin users, you can display a message or leave it empty --}}
                                        <span class="text-xs text-gray-500">No actions available</span>
                                        @endif
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="text-center py-4">No sales found matching your criteria.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-4"> {{ $sales->links() }} </div>
                </div>
            </div>
        </div>
    </div>

    <div id="invoicePreviewModal" class="hidden fixed inset-0 z-50 overflow-y-auto">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div id="modalBackdrop" class="fixed inset-0 bg-gray-500 bg-opacity-75"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen">​</span>
            <div class="inline-block align-bottom bg-white dark:bg-gray-800 rounded-lg text-left overflow-hidden shadow-xl transform sm:my-8 sm:align-middle sm:max-w-4xl sm:w-full">
                <div class="bg-white dark:bg-gray-800 px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="flex justify-between items-center pb-3 border-b dark:border-gray-700">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100" id="modal-title">Invoice Preview</h3>
                        <button id="closeModalButton" type="button" class="text-gray-400 bg-transparent hover:text-gray-200 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                            </svg>
                        </button>
                    </div>
                    <div id="invoicePreviewContent" class="mt-4 max-h-[70vh] overflow-y-auto">
                        <div class="text-center py-10">Loading...</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const modal = document.getElementById('invoicePreviewModal');
            const modalBackdrop = document.getElementById('modalBackdrop');
            const closeModalButton = document.getElementById('closeModalButton');
            const invoicePreviewContent = document.getElementById('invoicePreviewContent');
            const openModal = () => modal.classList.remove('hidden');
            const closeModal = () => modal.classList.add('hidden');
            closeModalButton.addEventListener('click', closeModal);
            modalBackdrop.addEventListener('click', closeModal);

            document.querySelectorAll('tr.clickable-row').forEach(row => {
                row.addEventListener('click', function(event) {
                    if (event.target.closest('a, button, form')) {
                        return;
                    }
                    const saleId = this.dataset.saleId;
                    const url = `/sales/preview/${saleId}`;
                    invoicePreviewContent.innerHTML = '<div class="text-center py-10">Loading...</div>';
                    openModal();
                    fetch(url).then(response => response.text()).then(html => {
                        invoicePreviewContent.innerHTML = html;
                    });
                });
            });
        });
    </script>
    @endpush
</x-app-layout>