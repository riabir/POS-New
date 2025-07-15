
{{-- This view receives a $purchase object --}}
<div class="p-6 text-sm text-gray-900 dark:text-gray-100">
    {{-- Header --}}
    <div class="flex justify-between items-start mb-6">
        <div>
            <h2 class="text-2xl font-bold text-gray-800 dark:text-white">PURCHASE RECEIPT</h2>
            <p>PO #: {{ $purchase->purchase_no }}</p>
            <p>Date: {{ \Carbon\Carbon::parse($purchase->purchase_date)->format('F d, Y') }}</p>
        </div>
        <div class="text-right">
            <h3 class="text-xl font-bold text-gray-800 dark:text-white">Your Company Name</h3>
            <p class="text-gray-600 dark:text-gray-400">123 Main Street<br>Anytown, USA 12345</p>
        </div>
    </div>

    {{-- Vendor Details --}}
    <div class="mb-8">
        <h4 class="font-bold text-gray-700 dark:text-gray-300 mb-2">Vendor:</h4>
        <div class="text-gray-600 dark:text-gray-400">
            <p class="font-semibold text-lg text-gray-800 dark:text-white">
                {{ $purchase->vendor?->vendor_name ?? 'N/A' }}
            </p>
            @if($purchase->vendor?->address)
                <p>{{ $purchase->vendor->address }}</p>
            @endif
            @if($purchase->vendor?->phone)
                <p>Phone: {{ $purchase->vendor->phone }}</p>
            @endif
            @if($purchase->vendor?->email)
                <p>Email: {{ $purchase->vendor->email }}</p>
            @endif
        </div>
    </div>

    {{-- Items Table --}}
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-gray-200 dark:bg-gray-700">
                    <th class="p-2 border border-gray-300 dark:border-gray-600">#</th>
                    <th class="p-2 border border-gray-300 dark:border-gray-600">Product</th>
                    <th class="p-2 border border-gray-300 dark:border-gray-600 text-center">Quantity</th>
                    <th class="p-2 border border-gray-300 dark:border-gray-600 text-right">Unit Price</th>
                    <th class="p-2 border border-gray-300 dark:border-gray-600 text-right">Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach($purchase->items as $item)
                <tr class="border-b dark:border-gray-700">
                    <td class="p-2 border-x border-gray-300 dark:border-gray-600 align-top">{{ $loop->iteration }}</td>
                    <td class="p-2 border-x border-gray-300 dark:border-gray-600 align-top">
                        <div class="font-bold">{{ $item->product?->model ?? 'Product Not Found' }}</div>
                        <div class="text-sm text-gray-600 dark:text-gray-400">{{ $item->product?->brand?->name ?? 'N/A' }}</div>
                        @if($item->product?->description)
                            <p class="mt-1 text-xs text-gray-500">{{ $item->product->description }}</p>
                        @endif
                        @if(!empty($item->serial_numbers))
                            <div class="mt-2 text-xs text-gray-500">
                                <span class="font-semibold">Serials:</span> {{ implode(', ', $item->serial_numbers) }}
                            </div>
                        @endif
                    </td>
                    <td class="p-2 border-x border-gray-300 dark:border-gray-600 text-center align-top">{{ $item->quantity }}</td>
                    <td class="p-2 border-x border-gray-300 dark:border-gray-600 text-right align-top">{{ number_format($item->unit_price, 2) }}</td>
                    <td class="p-2 border-x border-gray-300 dark:border-gray-600 text-right align-top">{{ number_format($item->total_price, 2) }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    {{-- Totals Section --}}
    <div class="flex justify-end mt-6">
        <div class="w-full max-w-xs">
            <div class="flex justify-between py-1">
                <span class="text-gray-600 dark:text-gray-400">Subtotal:</span>
                <span class="text-right font-medium">{{ number_format($purchase->sub_total, 2) }}</span>
            </div>
            <div class="flex justify-between py-1">
                <span class="text-gray-600 dark:text-gray-400">Discount:</span>
                <span class="text-right font-medium">{{ number_format($purchase->discount, 2) }}</span>
            </div>
            <hr class="my-2 border-gray-300 dark:border-gray-600">
            <div class="flex justify-between py-1 font-bold text-lg text-gray-800 dark:text-white">
                <span>Grand Total:</span>
                <span class="text-right">{{ number_format($purchase->grand_total, 2) }}</span>
            </div>
        </div>
    </div>

    {{-- Remarks --}}
    @if($purchase->remarks)
    <div class="mt-8">
        <h4 class="font-bold text-gray-700 dark:text-gray-300 mb-2">Remarks:</h4>
        <p class="text-gray-600 dark:text-gray-400 p-3 bg-gray-50 dark:bg-gray-700 rounded-md">{{ $purchase->remarks }}</p>
    </div>
    @endif
</div>