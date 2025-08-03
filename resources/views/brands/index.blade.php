<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Brands') }}
            </h2>
            <a href="{{ route('brands.create') }}" class="btn btn-primary">Add New Brand</a>
        </div>
    </x-slot>

    <style>
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

        .btn-group {
            display: flex;
            gap: 0.5rem;
        }

        .filter-form {
            background-color: #f9f9f9;
            padding: 1.5rem;
            border-radius: 0.5rem;
            margin-bottom: 1.5rem;
            border: 1px solid #e2e8f0;
        }

        .dark .filter-form {
            background-color: #4a5568;
            border-color: #2d3748;
        }

        .filter-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1rem;
        }

        .filter-buttons {
            display: flex;
            gap: 0.5rem;
            align-items: flex-end;
        }
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

                    <!-- Filter Form -->
                    <div class="filter-form">
                        <h3 class="text-lg font-semibold mb-3">Filter Brands</h3>
                        <form action="{{ route('brands.index') }}" method="GET">
                            <div class="filter-grid">

                                <div>
                                    <label for="id" class="block text-sm font-medium">Brand ID</label>
                                    <input type="number" name="id" id="id" value="{{ request('id') }}" class="mt-1 block w-full rounded-md shadow-sm" placeholder="e.g., 12">
                                </div>
                                <div>
                                    <label for="name" class="block text-sm font-medium">Brand Name</label>
                                    <input type="text" name="name" id="name" value="{{ request('name') }}" class="mt-1 block w-full rounded-md shadow-sm">
                                </div>
                                <div>
                                    <label for="category_id" class="block text-sm font-medium">Category</label>
                                    <select name="category_id" id="category_id" class="mt-1 block w-full rounded-md shadow-sm">
                                        <option value="">All Categories</option>
                                        @foreach($categories as $category)
                                        <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <label for="product_type_id" class="block text-sm font-medium">Product Type</label>
                                    <select name="product_type_id" id="product_type_id" class="mt-1 block w-full rounded-md shadow-sm">
                                        <option value="">All Types</option>
                                        @foreach($productTypes as $type)
                                        <option value="{{ $type->id }}" {{ request('product_type_id') == $type->id ? 'selected' : '' }}>{{ $type->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="filter-buttons">
                                    <button type="submit" class="btn btn-primary">Filter</button>
                                    <a href="{{ route('brands.index') }}" class="btn btn-secondary">Clear</a>
                                </div>
                            </div>
                        </form>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="table">
                            <thead class="bg-gray-100 dark:bg-gray-700">
                                <tr>
                                    <th>#</th>
                                    <th>ID</th>
                                    <th>Brand Name</th>
                                    <th>Category</th>
                                    <th>Product Type</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($brands as $brand)
                                <tr>
                                    <th scope="row">{{ $loop->iteration + $brands->firstItem() - 1 }}</th>
                                    <td>{{ $brand->id }}</td>
                                    <td class="font-medium">{{ $brand->name }}</td>
                                    <td>{{ $brand->category?->name }}</td>
                                    <td>{{ $brand->productType?->name }}</td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('brands.edit', $brand->id) }}" class="btn btn-sm btn-warning">Edit</a>
                                            <form method="POST" action="{{ route('brands.destroy', $brand->id) }}" onsubmit="return confirm('Are you sure?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="text-center py-4">No brands found.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-4">
                        {{ $brands->links() }}
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>