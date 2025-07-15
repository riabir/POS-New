{{-- This view receives a $sale object --}}
<div class="p-6 text-sm text-gray-900 dark:text-gray-100">
    {{-- Invoice Header --}}
    <div class="flex justify-between items-start mb-6">
        <div>
            <h2 class="text-2xl font-bold text-gray-800 dark:text-white">INVOICE</h2>
            <p>Invoice #: {{ $sale->bill_no }}</p>
            <p>Date: {{ \Carbon\Carbon::parse($sale->bill_date)->format('F d, Y') }}</p>
        </div>
        <div class="text-right">
            {{-- You should replace this with your actual company details --}}
            <h3 class="text-xl font-bold text-gray-800 dark:text-white">Your Company Name</h3>
            <p class="text-gray-600 dark:text-gray-400">123 Main Street<br>Anytown, USA 12345</p>
        </div>
    </div>

    {{-- Customer Details --}}
    <div class="mb-8">
        <h4 class="font-bold text-gray-700 dark:text-gray-300 mb-2">Bill To:</h4>

        {{-- We use a div to group customer details and apply consistent styling --}}
        <div class="text-gray-600 dark:text-gray-400">

            {{-- Customer Name (already there, but made safer) --}}
            <p class="font-semibold text-lg text-gray-800 dark:text-white">
                {{ $sale->customer?->customer_name ?? 'N/A' }}
            </p>

            {{-- ADDED: Address (only shown if it exists) --}}
            @if($sale->customer?->address)
                <p>{{ $sale->customer->address }}</p>
            @endif

            {{-- ADDED: Phone Number (only shown if it exists) --}}
            @if($sale->customer?->phone)
                <p>Phone: {{ $sale->customer->phone }}</p>
            @endif

            {{-- ADDED: Email (only shown if it exists) --}}
            @if($sale->customer?->email)
                <p>Email: {{ $sale->customer->email }}</p>
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
                @foreach($sale->items as $item)
                    <tr class="border-b dark:border-gray-700">
                        <td class="p-2 border-x border-gray-300 dark:border-gray-600 align-top">{{ $loop->iteration }}</td>
                        <td class="p-2 border-x border-gray-300 dark:border-gray-600 align-top">

                            {{-- Main Product Model/Name --}}
                            <div class="font-bold text-base">
                                {{-- Access the product object, then its model property --}}
                                {{ $item->product?->model ?? 'Product Model Not Found' }}
                            </div>

                            {{-- Brand Name --}}
                            <div class="text-sm text-gray-600 dark:text-gray-400">
                                {{-- Access the product, then its brand relationship, then the brand's name --}}
                                {{ $item->product?->brand?->name ?? 'N/A' }}
                            </div>

                            {{-- Description --}}
                            @if($item->product?->description)
                                <p class="mt-1 text-xs text-gray-500">
                                    {{ $item->product->description }}
                                </p>
                            @endif

                            {{-- Serials --}}
                            @if(!empty($item->serial_numbers))
                                <div class="mt-2 text-xs text-gray-500">
                                    <span class="font-semibold">S/N:</span> {{ implode(', ', $item->serial_numbers) }}
                                </div>
                            @endif

                        </td>
                        <td class="p-2 border-x border-gray-300 dark:border-gray-600 text-center align-top">
                            {{ $item->quantity }}
                        </td>
                        <td class="p-2 border-x border-gray-300 dark:border-gray-600 text-right align-top">
                            {{ number_format($item->unit_price, 2) }}
                        </td>
                        <td class="p-2 border-x border-gray-300 dark:border-gray-600 text-right align-top">
                            {{ number_format($item->total_price, 2) }}
                        </td>
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
                <span class="text-right font-medium">{{ number_format($sale->sub_total, 2) }}</span>
            </div>
            <div class="flex justify-between py-1">
                <span class="text-gray-600 dark:text-gray-400">Discount:</span>
                <span class="text-right font-medium">{{ number_format($sale->discount, 2) }}</span>
            </div>
            <hr class="my-2 border-gray-300 dark:border-gray-600">
            <div class="flex justify-between py-1 font-bold text-lg text-gray-800 dark:text-white">
                <span>Grand Total:</span>
                <span class="text-right">{{ number_format($sale->grand_total, 2) }}</span>
            </div>
        </div>
    </div>

    {{-- Remarks --}}
    @if($sale->remarks)
        <div class="mt-8">
            <h4 class="font-bold text-gray-700 dark:text-gray-300 mb-2">Remarks:</h4>
            <p class="text-gray-600 dark:text-gray-400 p-3 bg-gray-50 dark:bg-gray-700 rounded-md">{{ $sale->remarks }}</p>
        </div>
    @endif
</div>