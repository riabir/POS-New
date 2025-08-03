<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Current Stock Levels
        </h2>
    </x-slot>

    <style>
        /* Minimal custom styles, we rely on Tailwind utilities */
        .table {
            width: 100%;
            border-collapse: collapse;
        }

        .table th,
        .table td {
            padding: 12px 15px;
            border: 1px solid #ddd;
            text-align: left;
        }

        .dark .table th,
        .dark .table td {
            border-color: #4b5563;
        }

        .clickable-row {
            cursor: pointer;
            transition: background-color 0.2s ease-in-out;
        }

        .clickable-row:hover {
            background-color: #f3f4f6;
        }

        .dark .clickable-row:hover {
            background-color: #374151;
        }
    </style>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">

                    {{-- FILTER FORM SECTION --}}
                    <div class="mb-6 p-4 bg-gray-50 dark:bg-gray-700/50 rounded-lg border dark:border-gray-600">
                        <h3 class="text-lg font-semibold mb-3">Filter Stock</h3>
                        <form method="GET" action="{{ route('stocks.index') }}">
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4">

                                {{-- Product ID Filter --}}
                                <div>
                                    <label for="product_id_string" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Product ID</label>
                                    <input type="text" name="product_id_string" id="product_id_string" value="{{ request('product_id_string') }}"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-900 dark:border-gray-600"
                                        placeholder="e.g., 10.12.5.15">
                                </div>

                                {{-- Model Filter --}}
                                <div>
                                    <label for="model" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Model</label>
                                    <input type="text" name="model" id="model" value="{{ request('model') }}"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-900 dark:border-gray-600"
                                        placeholder="Search model...">
                                </div>

                                {{-- Category Filter --}}
                                <div>
                                    <label for="category_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Category</label>
                                    <select name="category_id" id="category_id"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-900 dark:border-gray-600">
                                        <option value="">All Categories</option>
                                        @foreach($categories as $category)
                                        <option value="{{ $category->id }}" @selected(request('category_id') == $category->id)>
                                            {{ $category->name }}
                                        </option>
                                        @endforeach
                                    </select>
                                </div>

                                {{-- Product Type Filter --}}
                                <div>
                                    <label for="product_type_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Product Type</label>
                                    <select name="product_type_id" id="product_type_id"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-900 dark:border-gray-600">
                                        <option value="">Select Category First</option>
                                        {{-- This section is populated by the controller on page load if a category is already selected --}}
                                        @if($productTypes->isNotEmpty())
                                            <option value="">All Types</option>
                                            @foreach($productTypes as $type)
                                                <option value="{{ $type->id }}" @selected(request('product_type_id') == $type->id)>
                                                    {{ $type->name }}
                                                </option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>

                                {{-- Filter Buttons --}}
                                <div class="flex items-end gap-x-2">
                                    <button type="submit" class="btn btn-primary">Filter</button>
                                    <a href="{{ route('stocks.index') }}" class="btn btn-secondary">Reset</a>
                                </div>

                            </div>
                        </form>
                    </div>

                    {{-- STOCK TABLE --}}
                    <div class="overflow-x-auto">
                        <table class="table">
                            <thead class="bg-gray-100 dark:bg-gray-700">
                                <tr>
                                    <th>P_id</th>
                                    <th>Item</th>
                                    <th class="text-center">Qty</th>
                                    <th class="text-right">LSP</th>
                                    <th class="text-right">MRP</th>
                                    <th>SN</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($stocks as $stock)
                                <tr class="clickable-row" onclick="window.location='{{ route('products.show', $stock->product_id) }}';" title="Click to view product details">
                                    <td class="whitespace-nowrap font-mono text-sm align-top">
                                        {{ $stock->product->category_id }}.{{ $stock->product->product_type_id }}.{{ $stock->product->brand_id }}.{{ $stock->product->id }}
                                    </td>
                                    <td class="align-top">
                                        <div class="font-medium text-gray-800 dark:text-gray-200">{{ $stock->product->header ?? $stock->product->name }}</div>
                                        <div class="text-xs text-gray-500">{{ $stock->product->model }}</div>
                                    </td>
                                    <td class="text-center font-bold text-lg align-top">{{ $stock->quantity }}</td>
                                    <td class="text-right font-mono align-top">{{ number_format($stock->lsp, 2) }}</td>
                                    <td class="text-right font-mono text-green-600 align-top">{{ number_format($stock->product->mrp ?? 0, 2) }}</td>
                                    <td class="text-xs align-top">
                                        @if(!empty($stock->serial_numbers) && is_array($stock->serial_numbers))
                                            {{ implode(', ', $stock->serial_numbers) }}
                                        @endif
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="text-center py-4">No Stock found matching your criteria.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    {{-- PAGINATION LINKS --}}
                    <div class="mt-4">
                        {{-- withQueryString() ensures that your filters are not lost when you change pages --}}
                        {{ $stocks->withQueryString()->links() }}
                    </div>

                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    {{-- JAVASCRIPT FOR DEPENDENT DROPDOWN --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const categorySelect = document.getElementById('category_id');
            const productTypeSelect = document.getElementById('product_type_id');
            const selectedProductTypeId = "{{ request('product_type_id') }}";

            function updateProductTypes(categoryId, shouldResetSelection) {
                if (!categoryId) {
                    productTypeSelect.innerHTML = '<option value="">Select Category First</option>';
                    productTypeSelect.disabled = true;
                    return;
                }
                
                productTypeSelect.disabled = true;
                productTypeSelect.innerHTML = '<option value="">Loading...</option>';

                fetch(`/getproducttypes?id=${categoryId}`)
                    .then(response => response.json())
                    .then(data => {
                        let optionsHtml = '<option value="">All Types</option>';
                        data.forEach(type => {
                            const isSelected = !shouldResetSelection && type.id == selectedProductTypeId;
                            optionsHtml += `<option value="${type.id}" ${isSelected ? 'selected' : ''}>${type.name}</option>`;
                        });
                        
                        productTypeSelect.innerHTML = optionsHtml;
                        productTypeSelect.disabled = false;
                    })
                    .catch(error => {
                        console.error('Error fetching product types:', error);
                        productTypeSelect.innerHTML = '<option value="">Could not load types</option>';
                        productTypeSelect.disabled = false;
                    });
            }

            // Event listener for when the user manually changes the category
            categorySelect.addEventListener('change', function() {
                updateProductTypes(this.value, true); // `true` resets the product type selection
            });

            // Run on initial page load to populate product types if a category is already selected
            if (categorySelect.value) {
                updateProductTypes(categorySelect.value, false); // `false` keeps the current selection
            } else {
                productTypeSelect.disabled = true;
            }
        });
    </script>
    @endpush
</x-app-layout>