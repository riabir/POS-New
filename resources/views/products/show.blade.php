<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Product Details') }}
            </h2>
            <div class="flex items-center space-x-2">
                <a href="{{ route('products.index') }}" class="btn btn-secondary">Back to List</a>
                <!-- <a href="{{ route('products.edit', $product->id) }}" class="btn btn-primary">Edit Product</a> -->
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6 lg:p-8">
                    
                    {{-- Header Section --}}
                    <div class="border-b pb-4 mb-6">                      
                        <h3 class="text-2xl font-bold text-gray-800 dark:text-gray-200 mt-2">
                            {{ $product->header }}
                        </h3>
                        <div class="flex items-center justify-between mt-3">
                            <p class="text-sm text-gray-600 dark:text-gray-400">Product ID: <span class="font-semibold">{{ $product->category_id }}.
                                {{ $product->product_type_id }}.{{ $product->brand_id }}.{{ $product->id }}</span></p>                               
                            <p class="text-2xl font-bold text-green-600">MRP: <span class="font-mono">{{ number_format($product->mrp, 2) }}</span></p>
                        </div>
                        <div class="flex items-center justify-between mt-3">                            
                            <p class="text-sm text-gray-600 dark:text-gray-400">Model: <span class="font-semibold">{{ $product->model }}</span></p>
                        </div>
                        <div class="flex items-center justify-between mt-3">                            
                             <p class="text-sm text-gray-500">{{ $product->brand?->name }} / {{ $product->productType?->name }}</p>
                        </div>
                    </div>

                    {{-- Description Section --}}
                    @if($product->description)
                    <div class="mb-6">
                        <h4 class="font-semibold text-lg text-gray-800 dark:text-gray-200 mb-2">Description</h4>
                        <p class="text-gray-600 dark:text-gray-400 prose">{{ $product->description }}</p>
                    </div>
                    @endif

                    {{-- Specifications Section --}}
                    @if($product->specifications)
                    <div class="border-t pt-6">
                        <h4 class="font-semibold text-lg text-gray-800 dark:text-gray-200 mb-4">Specifications</h4>
                        <dl class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-4">
                            @foreach($product->specifications as $key => $value)
                                <div class="flex flex-col">
                                    <dt class="text-sm font-medium text-gray-500">{{ $key }}</dt>
                                    <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ $value }}</dd>
                                </div>
                            @endforeach
                        </dl>
                    </div>
                    @endif

                </div>
            </div>
        </div>
    </div>
</x-app-layout>