<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Current Stocks
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

        .styled-table tbody tr:nth-of-type(even) {
            background-color: #f3f3f3;
        }

        .dark .styled-table thead tr {
            background-color: #374151;
            color: #f3f4f6;
        }

        .dark .styled-table th,
        .dark .styled-table td {
            border-color: #4b5563;
        }

        .dark .styled-table tbody tr:nth-of-type(even) {
            background-color: #4b5563;
        }

        .actions-container {
            display: flex;
            gap: 0.5rem;
            align-items: center;
        }
    </style>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">

                    {{-- Success Message --}}
                    @if(session('success'))
                        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4"
                            role="alert">
                            <span class="block sm:inline">{{ session('success') }}</span>
                        </div>
                    @endif

                    {{-- Filter Form --}}
                    <form method="GET" action="{{ route('stocks.index') }}" class="mb-6">
                        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Product
                                    ID</label>
                                <select name="product_id" class="w-full p-2 border rounded">
                                    <option value="">All</option>
                                    @foreach($products as $product)
                                        <option value="{{ $product->id }}" {{ request('product_id') == $product->id ? 'selected' : '' }}>
                                            {{ $product->id }} - {{ $product->model }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300">Category</label>
                                <select name="category_id" class="w-full p-2 border rounded">
                                    <option value="">All</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>
                                            {{ $category->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Product
                                    Type</label>
                                <select name="product_type_id" class="w-full p-2 border rounded">
                                    <option value="">All</option>
                                    @foreach($productTypes as $type)
                                        <option value="{{ $type->id }}" {{ request('product_type_id') == $type->id ? 'selected' : '' }}>
                                            {{ $type->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Model</label>
                                <input type="text" name="model" value="{{ request('model') }}"
                                    class="w-full p-2 border rounded" placeholder="Enter model...">
                            </div>
                        </div>

                        <div class="mt-4 flex justify-end gap-3">
                            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                                Filter
                            </button>
                            <a href="{{ route('stocks.index') }}"
                                class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600">
                                Reset
                            </a>
                        </div>
                    </form>

                    {{-- Table --}}
                    <div class="overflow-x-auto">
                        <table class="styled-table">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>P_ID</th>
                                    <th>Model</th>
                                    <th>Quantity</th>
                                    <th>SN</th>
                                    <th>LSP</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($stocks as $stock)
                                    <tr>
                                        <th>{{ $loop->iteration }}</th>
                                        <td>{{ $stock->product_id }}</td>
                                        <td>{{ $stock->product->model }}</td>
                                        <td>{{ $stock->quantity }}</td>
                                        <td>{{ is_array($stock->serial_numbers) ? implode(', ', $stock->serial_numbers) : $stock->serial_numbers }}
                                        </td>
                                        <td>{{ $stock->lsp }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center py-4">No Stock found.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    {{-- Pagination --}}
                    <div class="mt-4">
                        {{ $stocks->withQueryString()->links() }}
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>