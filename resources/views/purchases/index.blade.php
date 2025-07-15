<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Purchase History
        </h2>
    </x-slot>

    <style>
        .status-badge { display: inline-block; padding: .25em .6em; font-size: 75%; font-weight: 700; line-height: 1; text-align: center; white-space: nowrap; vertical-align: baseline; border-radius: .25rem; color: #fff; }
        .status-paid { background-color: #28a745; }
        .status-due { background-color: #dc3545; }
        .styled-table { width: 100%; border-collapse: collapse; }
        .styled-table thead tr { background-color: #f8f9fa; color: #333; text-align: left; }
        .styled-table th, .styled-table td { padding: 12px 15px; border: 1px solid #ddd; }
        .styled-table tbody tr:nth-of-type(even) { background-color: #f3f3f3; }
        .dark .styled-table thead tr { background-color: #374151; color: #f3f4f6; }
        .dark .styled-table th, .dark .styled-table td { border-color: #4b5563; }
        .dark .styled-table tbody tr:nth-of-type(even) { background-color: #4b5563; }
        .actions-container { display: flex; gap: 0.5rem; align-items: center; }

        /* MODIFIED: Added styles for clickable rows */
        .clickable-row { cursor: pointer; transition: background-color 0.2s ease-in-out; }
        .clickable-row:hover { background-color: #e9ecef; }
        .dark .clickable-row:hover { background-color: #4a5568; }
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

                    <div class="overflow-x-auto">
                        <table class="styled-table">
                            <thead>
                                <tr>
                                    <th>Vendor</th>
                                    <th>PO No</th>
                                    <th>Purchase Date</th>
                                    <th>Grand Total</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($purchases as $purchase)
                                    {{-- MODIFIED: Added class and data-attribute to the <tr> --}}
                                    <tr class="clickable-row" data-purchase-id="{{ $purchase->id }}" title="Click to view details">
                                        <td>{{ $purchase->vendor?->vendor_name ?? 'N/A' }}</td>
                                        <td>{{ $purchase->purchase_no }}</td>
                                        <td>{{ \Carbon\Carbon::parse($purchase->purchase_date)->format('d-m-Y') }}</td>
                                        <td>{{ number_format($purchase->grand_total, 2) }}</td>
                                        <td>
                                            @if($purchase->status == 'paid')
                                                <span class="status-badge status-paid">Paid</span>
                                            @else
                                                <span class="status-badge status-due">Due</span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="actions-container">
                                                @if($purchase->status == 'due')
                                                    <form method="POST" action="{{ route('purchases.markAsPaid', $purchase->id) }}">
                                                        @csrf
                                                        <button type="submit" class="btn btn-sm btn-success">Paid</button>
                                                    </form>
                                                @endif
                                                <form method="POST" action="{{ route('purchases.destroy', $purchase->id) }}">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button onclick="return confirm('Are you sure?')" type="submit" class="btn btn-sm btn-danger">Delete</button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center py-4">No purchases found.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                     <div class="mt-4">
                        {{ $purchases->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- =================================================================== -->
    <!-- ADDED: INVOICE PREVIEW MODAL (reused from sales) -->
    <!-- =================================================================== -->
    <div id="invoicePreviewModal" class="hidden fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div id="modalBackdrop" class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">​</span>
            <div class="inline-block align-bottom bg-white dark:bg-gray-800 rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-4xl sm:w-full">
                <div class="bg-white dark:bg-gray-800 px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="flex justify-between items-center pb-3 border-b dark:border-gray-700">
                        <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-gray-100" id="modal-title">
                            Details
                        </h3>
                        <button id="closeModalButton" type="button" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center dark:hover:bg-gray-600 dark:hover:text-white">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
                        </button>
                    </div>
                    <div id="invoicePreviewContent" class="mt-4 max-h-[70vh] overflow-y-auto">
                        <div class="text-center py-10">Loading...</div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <!-- =================================================================== -->
    <!-- ADDED: JAVASCRIPT LOGIC -->
    <!-- =================================================================== -->
    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const modal = document.getElementById('invoicePreviewModal');
            const modalBackdrop = document.getElementById('modalBackdrop');
            const closeModalButton = document.getElementById('closeModalButton');
            const invoicePreviewContent = document.getElementById('invoicePreviewContent');
            
            const openModal = () => modal.classList.remove('hidden');
            const closeModal = () => modal.classList.add('hidden');

            closeModalButton.addEventListener('click', closeModal);
            modalBackdrop.addEventListener('click', closeModal);
            
            document.addEventListener('keydown', (event) => {
                if (event.key === 'Escape' && !modal.classList.contains('hidden')) {
                    closeModal();
                }
            });
            
            const rows = document.querySelectorAll('.clickable-row');

            rows.forEach(row => {
                row.addEventListener('click', function (event) {
                    if (event.target.closest('button, a, form')) {
                        return;
                    }

                    // MODIFIED: Use data-purchase-id
                    const purchaseId = this.dataset.purchaseId;
                    
                    // MODIFIED: Use the correct route
                    const url = `{{ url('/purchases/preview') }}/${purchaseId}`;
                    
                    invoicePreviewContent.innerHTML = '<div class="text-center text-gray-500 dark:text-gray-300 py-10">Loading details...</div>';
                    openModal();

                    fetch(url)
                        .then(response => {
                            if (!response.ok) throw new Error(`HTTP error! status: ${response.status}`);
                            return response.text();
                        })
                        .then(html => {
                            invoicePreviewContent.innerHTML = html;
                        })
                        .catch(error => {
                            invoicePreviewContent.innerHTML = `<div class="text-center py-10 text-red-500">Failed to load details. Please try again.</div>`;
                            console.error('Error fetching details:', error);
                        });
                });
            });
        });
    </script>
    @endpush

</x-app-layout>