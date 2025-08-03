<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Products') }}
            </h2>
            <a href="{{ route('products.create') }}" class="btn btn-primary">Add New Product</a>
        </div>
    </x-slot>

    <style>
        .table { width: 100%; border-collapse: collapse; }
        .table th, .table td { padding: 12px 15px; border: 1px solid #ddd; text-align: left; }
        .dark .table th, .dark .table td { border-color: #4b5563; }
        .btn-group { display: flex; gap: 0.5rem; }
        .clickable-row { cursor: pointer; transition: background-color 0.2s ease-in-out; }
        .clickable-row:hover { background-color: #f3f4f6; }
        .dark .clickable-row:hover { background-color: #374151; }
        /* Styles for the filter form */
        .filter-form { background-color: #f9f9f9; padding: 1.5rem; border-radius: 0.5rem; margin-bottom: 1.5rem; border: 1px solid #e2e8f0; }
        .dark .filter-form { background-color: #4a5568; border-color: #2d3748; }
        .filter-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem; }
        .filter-buttons { display: flex; gap: 0.5rem; align-items: flex-end; }
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

                    {{-- ================================== --}}
                    {{-- NEW: FILTER FORM START --}}
                    {{-- ================================== --}}
                    <div class="filter-form">
                        <h3 class="text-lg font-semibold mb-3">Filter Products</h3>
                        <form action="{{ route('products.index') }}" method="GET">
                            <div class="filter-grid">
                                {{-- Filter by Combined Product ID --}}
                                <div>
                                    <label for="product_id_string" class="block text-sm font-medium">Product ID</label>
                                    <input type="text" name="product_id_string" id="product_id_string" value="{{ request('product_id_string') }}" class="mt-1 block w-full rounded-md shadow-sm" placeholder="e.g., 10.12.5.15">
                                </div>
                                
                                {{-- Filter by Brand --}}
                                <div>
                                    <label for="brand_id" class="block text-sm font-medium">Brand</label>
                                    <select name="brand_id" id="brand_id" class="mt-1 block w-full rounded-md shadow-sm">
                                        <option value="">All Brands</option>
                                        @foreach($brands as $brand)
                                            <option value="{{ $brand->id }}" {{ request('brand_id') == $brand->id ? 'selected' : '' }}>
                                                {{ $brand->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                {{-- Filter by Model --}}
                                <div>
                                    <label for="model" class="block text-sm font-medium">Model</label>
                                    <input type="text" name="model" id="model" value="{{ request('model') }}" class="mt-1 block w-full rounded-md shadow-sm" placeholder="e.g., 15-fc0166AU">
                                </div>

                                {{-- Filter and Clear Buttons --}}
                                <div class="filter-buttons">
                                    <button type="submit" class="btn btn-primary">Filter</button>
                                    <a href="{{ route('products.index') }}" class="btn btn-secondary">Clear</a>
                                </div>
                            </div>
                        </form>
                    </div>
                    {{-- ================================== --}}
                    {{-- FILTER FORM END --}}
                    {{-- ================================== --}}


                    <div class="overflow-x-auto">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>P_ID</th>
                                    <th>Header / Title</th>
                                    <th>Brand</th>
                                    <th>Model</th>
                                    <th class="text-right">MRP</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($products as $product)
                                    <tr class="clickable-row" onclick="window.location='{{ route('products.show', $product->id) }}';">
                                        <td class="whitespace-nowrap font-mono text-sm">
                                            {{ $product->category_id }}.{{ $product->product_type_id }}.{{ $product->brand_id }}.{{ $product->id }}
                                        </td>
                                        <td class="font-medium">{{ $product->header }}</td>
                                        <td>{{ $product->brand?->name }}</td>
                                        <td>{{ $product->model }}</td>
                                        <td class="text-right font-mono">{{ number_format($product->mrp, 2) }}</td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <a href="{{ route('products.edit', $product->id) }}" onclick="event.stopPropagation();" class="btn btn-sm btn-warning">Edit</a>
                                                <form method="POST" action="{{ route('products.destroy', $product->id) }}" onsubmit="return confirm('Are you sure?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" onclick="event.stopPropagation();" class="btn btn-sm btn-danger">Delete</button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center py-4">No products found matching your criteria.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-4">
                        {{ $products->links() }}
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>