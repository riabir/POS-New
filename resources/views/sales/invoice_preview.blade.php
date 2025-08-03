<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice - {{ $sale->bill_no }}</title>
    {{-- We use the Tailwind CSS CDN to make this a self-contained, styled document --}}
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        /* Custom styles for the invoice */
        body {
            background-color: #f3f4f6; /* A light grey background */
        }
        /* Hide buttons and other non-invoice elements when printing */
        @media print {
            body {
                background-color: #fff;
            }
            .no-print {
                display: none !important;
            }
            .invoice-container {
                box-shadow: none !important;
                margin: 0 !important;
                max-width: 100% !important;
            }
        }
    </style>
</head>
<body class="p-4 sm:p-8">

    <!-- Action Buttons (Hidden on Print) -->
    <div class="max-w-4xl mx-auto mb-4 flex justify-end gap-2 no-print">
        <button onclick="window.print()" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
            Print Invoice
        </button>
        <button onclick="window.close()" class="px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700">
            Close
        </button>
    </div>

    <!-- Main Invoice Container -->
    <div class="invoice-container max-w-4xl mx-auto bg-white p-8 sm:p-12 shadow-lg rounded-lg">
        
        <!-- Header Section -->
        <header class="flex justify-between items-start mb-10">
            <div>
                <h1 class="text-3xl font-bold text-gray-800">Your Company Name</h1>
                <p class="text-gray-500">123 Business Rd, Suite 100<br>City, State, 12345</p>
                <p class="text-gray-500">your.email@company.com</p>
            </div>
            <div class="text-right">
                <h2 class="text-4xl font-extrabold uppercase text-gray-700">Invoice</h2>
                <p class="text-gray-500 mt-2">Bill No: <span class="font-semibold text-gray-800">{{ $sale->bill_no }}</span></p>
                <p class="text-gray-500">Date: <span class="font-semibold text-gray-800">{{ $sale->bill_date->format('F j, Y') }}</span></p>
            </div>
        </header>

        <!-- Customer Information -->
        <section class="mb-10">
            <h3 class="text-sm font-semibold uppercase text-gray-500 border-b pb-2">Bill To</h3>
            <div class="mt-4">
                @if($sale->customer)
                    <p class="font-bold text-lg text-gray-800">{{ $sale->customer->customer_name }}</p>
                    <p class="text-gray-600">{{ $sale->customer->address }}</p>
                    <p class="text-gray-600">{{ $sale->customer->email }}</p>
                    <p class="text-gray-600">{{ $sale->customer->phone }}</p>
                @else
                    <p class="text-gray-500">N/A</p>
                @endif
            </div>
        </section>

        <!-- Items Table -->
        <section class="mb-10">
            <table class="w-full text-left">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="p-3 font-semibold text-sm text-gray-600">#</th>
                        <th class="p-3 font-semibold text-sm text-gray-600">Item Description</th>
                        <th class="p-3 font-semibold text-sm text-gray-600 text-center">Qty</th>
                        <th class="p-3 font-semibold text-sm text-gray-600 text-right">Unit Price</th>
                        <th class="p-3 font-semibold text-sm text-gray-600 text-right">Total</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($sale->items as $item)
                        <tr class="border-b">
                            <td class="p-3">{{ $loop->iteration }}</td>
                            <td class="p-3">
                                <p class="font-semibold text-gray-800">{{ $item->product->model ?? 'N/A' }} {{ $item->product->brand?->name }}</p>
                                <p class="text-xs text-gray-500">{{ $item->product->description ?? '' }}</p>
                                @if(!empty($item->serial_numbers))
                                    <p class="text-xs text-gray-500 mt-1">Serials: <span class="font-mono">{{ implode(', ', $item->serial_numbers) }}</span></p>
                                @endif
                                @if($item->warranty)
                                    <p class="text-xs text-gray-500 mt-1">Warranty: <span class="font-semibold">{{ $item->warranty }} Days</span></p>
                                @endif
                            </td>
                            <td class="p-3 text-center">{{ $item->quantity }}</td>
                            <td class="p-3 text-right font-mono">৳{{ number_format($item->unit_price, 2) }}</td>
                            <td class="p-3 text-right font-mono">৳{{ number_format($item->total_price, 2) }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="p-3 text-center text-gray-500">This sale has no items.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </section>

        <!-- Totals Section -->
        <section class="flex justify-end mb-10">
            <div class="w-full max-w-xs space-y-2">
                <div class="flex justify-between">
                    <span class="text-gray-600">Subtotal:</span>
                    <span class="font-mono">৳{{ number_format($sale->sub_total, 2) }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-600">Discount:</span>
                    <span class="font-mono text-red-500">- ৳{{ number_format($sale->discount, 2) }}</span>
                </div>
                <div class="flex justify-between border-t-2 pt-2 mt-2">
                    <span class="font-bold text-lg text-gray-800">Grand Total:</span>
                    <span class="font-bold text-lg font-mono text-gray-800">৳{{ number_format($sale->grand_total, 2) }}</span>
                </div>
            </div>
        </section>

        <!-- Footer Notes -->
        <footer class="border-t pt-6">
            @if($sale->remarks)
                <div class="mb-4">
                    <h4 class="font-semibold text-gray-600 mb-1">Notes:</h4>
                    <p class="text-sm text-gray-500 italic">{{ $sale->remarks }}</p>
                </div>
            @endif
            <p class="text-center text-sm text-gray-500">Thank you for your business!</p>
        </footer>

    </div>
</body>
</html>