<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Commission Details') }}
            </h2>
            <a href="{{ route('commissions.index') }}" class="text-sm text-gray-700 dark:text-gray-300 hover:underline">
                ← Back to Commission List
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 md:p-8 text-gray-900 dark:text-gray-100">
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">

                        <!-- Commission Details Card -->
                        <div class="space-y-4">
                            <h3 class="text-lg font-semibold border-b pb-2 dark:border-gray-600">Commission Info</h3>
                            <dl class="space-y-3">
                                <div class="flex justify-between">
                                    <dt class="text-gray-500">Commission ID:</dt>
                                    <dd class="font-mono">COM-{{ $commission->id }}</dd>
                                </div>
                                <div class="flex justify-between">
                                    <dt class="text-gray-500">Date Recorded:</dt>
                                    <dd>{{ $commission->created_at->format('F j, Y, g:i a') }}</dd>
                                </div>
                                <div class="flex justify-between items-center bg-gray-100 dark:bg-gray-700 p-3 rounded-lg">
                                    <dt class="font-bold text-lg">Amount:</dt>
                                    <dd class="font-mono text-2xl font-bold text-green-600">৳{{ number_format($commission->amount, 2) }}</dd>
                                </div>
                                <div class="flex flex-col">
                                    <dt class="text-gray-500 mb-1">Notes:</dt>
                                    <dd class="p-3 bg-gray-50 dark:bg-gray-900/50 rounded-md text-sm italic">{{ $commission->notes ?: 'No notes provided.' }}</dd>
                                </div>
                            </dl>
                        </div>

                        <!-- Related Info Card -->
                        <div class="space-y-4">
                            <h3 class="text-lg font-semibold border-b pb-2 dark:border-gray-600">Related Records</h3>
                            
                            {{-- Sale Details --}}
                            @if($commission->sale)
                                <div class="p-4 border rounded-lg dark:border-gray-600">
                                    <h4 class="font-semibold mb-2">Source Sale</h4>
                                    <dl class="space-y-2 text-sm">
                                        <div class="flex justify-between"><dt class="text-gray-500">Bill No:</dt> <dd><a href="{{ route('sales.showPreview', $commission->sale_id) }}" class="text-indigo-600 hover:underline font-mono" target="_blank">{{ $commission->sale->bill_no }}</a></dd></div>
                                        <div class="flex justify-between"><dt class="text-gray-500">Sale Date:</dt> <dd>{{ $commission->sale->bill_date->format('d M, Y') }}</dd></div>
                                        <div class="flex justify-between"><dt class="text-gray-500">Sale Amount:</dt> <dd class="font-mono">৳{{ number_format($commission->sale->grand_total, 2) }}</dd></div>
                                    </dl>
                                </div>
                            @endif

                            {{-- Recipient Details --}}
                            @if($commission->recipient)
                                <div class="p-4 border rounded-lg dark:border-gray-600">
                                    <h4 class="font-semibold mb-2">Recipient</h4>
                                    <dl class="space-y-2 text-sm">
                                        <div class="flex justify-between"><dt class="text-gray-500">Name:</dt> <dd class="font-bold">{{ $commission->recipient_name }}</dd></div>
                                        <div class="flex justify-between"><dt class="text-gray-500">Type:</dt> <dd>{{ Str::afterLast($commission->recipient_type, '\\') }}</dd></div>
                                        <div class="flex justify-between"><dt class="text-gray-500">Phone:</dt> <dd class="font-mono">{{ $commission->recipient->phone ?: 'N/A' }}</dd></div>
                                    </dl>
                                </div>
                            @endif
                        </div>

                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>