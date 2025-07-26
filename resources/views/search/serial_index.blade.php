<x-app-layout>
    {{-- This is the header slot, which appears at the top of the page --}}
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Product Lifecycle Tracker') }}
        </h2>
    </x-slot>

    {{-- This is the main content area --}}
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">

                    <p class="mb-4">Enter a product serial number to see its complete purchase and sale history.</p>

                    {{-- Search Form --}}
                    <form action="{{ route('serial.search') }}" method="GET" class="mb-4">
                        <div class="flex items-center">
                            <input type="text" 
                                   name="serial" 
                                   class="form-input rounded-md shadow-sm block w-full" 
                                   placeholder="Enter Serial Number..." 
                                   value="{{ old('serial', $serial ?? '') }}" 
                                   required>
                            <button class="inline-flex items-center px-4 py-2 bg-gray-800 dark:bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-white dark:text-gray-800 uppercase tracking-widest hover:bg-gray-700 dark:hover:bg-white active:bg-gray-900 dark:active:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150" type="submit">
                                Search
                            </button>
                        </div>
                    </form>

                    {{-- This section only shows if a search has been performed --}}
                    @if(isset($serial) && $serial)
                        <hr class="my-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-3">Results for Serial Number: <strong>{{ $serial }}</strong></h3>

                        {{-- Purchase History Section --}}
                        <div class="mb-6">
                            <h4 class="font-semibold mb-2">Purchase History</h4>
                            @if($purchases->isNotEmpty())
                                <div class="overflow-x-auto">
                                    <table class="min-w-full divide-y divide-gray-200">
                                        {{-- ... thead is fine ... --}}
                                        <thead class="bg-gray-50">
                                            <tr>
                                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Purchase #</th>
                                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Purchase Date</th>
                                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Vendor</th>
                                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                            </tr>
                                        </thead>
                                        <tbody class="bg-white divide-y divide-gray-200">
                                            @foreach($purchases as $purchase)
                                            {{-- MODIFIED: Removed onclick, added class and data-attributes --}}
                                            <tr class="hover:bg-gray-100 cursor-pointer clickable-row" data-id="{{ $purchase->id }}" data-type="purchase" title="Click to view purchase receipt">
                                                <td class="px-6 py-4 whitespace-nowrap">{{ $purchase->purchase_no }}</td>
                                                <td class="px-6 py-4 whitespace-nowrap">{{ $purchase->purchase_date->format('d M, Y') }}</td>
                                                <td class="px-6 py-4 whitespace-nowrap">{{ $purchase->vendor->vendor_name ?? 'N/A' }}</td>
                                                <td class="px-6 py-4 whitespace-nowrap"><span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-200 text-gray-800">{{ ucwords($purchase->status) }}</span></td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @else
                                <div class="bg-blue-100 border-l-4 border-blue-500 text-blue-700 p-4" role="alert">
                                    <p>No purchase record found for this serial number.</p>
                                </div>
                            @endif
                        </div>

                        {{-- Sale History Section --}}
                        <div>
                            <h4 class="font-semibold mb-2">Sale History</h4>
                            @if($sales->isNotEmpty())
                                <div class="overflow-x-auto">
                                    <table class="min-w-full divide-y divide-gray-200">
                                        {{-- ... thead is fine ... --}}
                                        <thead class="bg-gray-50">
                                            <tr>
                                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Bill #</th>
                                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Bill Date</th>
                                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Customer</th>
                                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                            </tr>
                                        </thead>
                                        <tbody class="bg-white divide-y divide-gray-200">
                                            @foreach($sales as $sale)
                                            {{-- MODIFIED: Removed onclick, added class and data-attributes --}}
                                            <tr class="hover:bg-gray-100 cursor-pointer clickable-row" data-id="{{ $sale->id }}" data-type="sale" title="Click to view sale invoice">
                                                <td class="px-6 py-4 whitespace-nowrap">{{ $sale->bill_no }}</td>
                                                <td class="px-6 py-4 whitespace-nowrap">{{ $sale->bill_date->format('d M, Y') }}</td>
                                                <td class="px-6 py-4 whitespace-nowrap">{{ $sale->customer->customer_name ?? 'N/A' }}</td>
                                                <td class="px-6 py-4 whitespace-nowrap"><span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-200 text-green-800">{{ ucwords($sale->status) }}</span></td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @else
                                <div class="bg-yellow-100 border-l-4 border-yellow-500 text-yellow-700 p-4" role="alert">
                                    <p>No sale record found for this serial number.</p>
                                </div>
                            @endif
                        </div>
                    @endif

                </div>
            </div>
        </div>
    </div>

    <!-- ADDED: INVOICE/RECEIPT PREVIEW MODAL -->
    <div id="previewModal" class="hidden fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div id="modalBackdrop" class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">​</span>
            <div class="inline-block align-bottom bg-white dark:bg-gray-800 rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-4xl sm:w-full">
                <div class="bg-white dark:bg-gray-800 px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="flex justify-between items-center pb-3 border-b dark:border-gray-700">
                        <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-gray-100" id="modal-title">Details Preview</h3>
                        <button id="closeModalButton" type="button" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center dark:hover:bg-gray-600 dark:hover:text-white">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
                        </button>
                    </div>
                    <div id="previewContent" class="mt-4 max-h-[70vh] overflow-y-auto">
                        <div class="text-center py-10">Loading...</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- ADDED: JAVASCRIPT LOGIC FOR MODAL -->
    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const modal = document.getElementById('previewModal');
            const modalBackdrop = document.getElementById('modalBackdrop');
            const closeModalButton = document.getElementById('closeModalButton');
            const previewContent = document.getElementById('previewContent');

            const openModal = () => modal.classList.remove('hidden');
            const closeModal = () => modal.classList.add('hidden');

            closeModalButton.addEventListener('click', closeModal);
            modalBackdrop.addEventListener('click', closeModal);

            const rows = document.querySelectorAll('.clickable-row');
            rows.forEach(row => {
                row.addEventListener('click', function () {
                    const rowId = this.dataset.id;
                    const rowType = this.dataset.type;
                    let url = '';

                    // Build the correct URL based on the row type
                    if (rowType === 'purchase') {
                        // Use the 'purchases.preview' route you defined
                        url = `{{ url('/purchases') }}/${rowId}/preview`;
                    } else if (rowType === 'sale') {
                        // Use the 'sales.preview' route you defined
                        url = `{{ url('/sales') }}/${rowId}/preview`;
                    }

                    previewContent.innerHTML = '<div class="text-center py-10">Loading...</div>';
                    openModal();

                    fetch(url)
                        .then(response => response.text())
                        .then(html => {
                            previewContent.innerHTML = html;
                        })
                        .catch(error => {
                            previewContent.innerHTML = `<div class="text-center text-red-500">Failed to load details.</div>`;
                            console.error('Error fetching details:', error);
                        });
                });
            });
        });
    </script>
    @endpush
</x-app-layout>