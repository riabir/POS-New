<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Manage Product Types
        </h2>
    </x-slot>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/choices.js/public/assets/styles/choices.min.css" />

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">

                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h1 class="h3 mb-0">Product Type List</h1>
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#productTypeModal">
                            + Add New Product Type
                        </button>
                    </div>

                    @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                    @endif

                    {{-- ================================== --}}
                    {{-- NEW: FILTER FORM START --}}
                    {{-- ================================== --}}
                    <div class="card mb-4 dark:bg-gray-700">
                        <div class="card-body">
                            <h5 class="card-title">Filter Product Types</h5>
                            <form action="{{ route('product_types.index') }}" method="GET">
                                <div class="row g-3 align-items-end">
                                    <div class="col-md-3">
                                        <label for="filter_id" class="form-label">Product Type ID</label>
                                        <input type="number" name="id" id="filter_id" value="{{ request('id') }}" class="form-control" placeholder="e.g., 25">
                                    </div>
                                    <div class="col-md-3">
                                        <label for="filter_category" class="form-label">Parent Category</label>
                                        <select name="category_id" id="filter_category" class="form-select">
                                            <option value="">All Categories</option>
                                            @foreach($categories as $category)
                                                <option value="{{ $category->id }}" @selected(request('category_id') == $category->id)>
                                                    {{ $category->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-3">
                                        <label for="filter_name" class="form-label">Product Type Name</label>
                                        <input type="text" name="name" id="filter_name" value="{{ request('name') }}" class="form-control" placeholder="Search by name...">
                                    </div>
                                    <div class="col-md-3">
                                        <button type="submit" class="btn btn-primary">Filter</button>
                                        <a href="{{ route('product_types.index') }}" class="btn btn-secondary">Clear</a>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                    {{-- ================================== --}}
                    {{-- FILTER FORM END --}}
                    {{-- ================================== --}}

                    <div class="table-responsive">
                        <table class="table table-striped table-hover table-bordered">
                            <thead class="table-light">
                                <tr>
                                    <th scope="col" style="width: 5%;">#</th>
                                    <th scope="col" style="width: 10%;">ID</th>
                                    <th scope="col">Parent Category</th>
                                    <th scope="col">Product Type</th>
                                    <th scope="col" style="width: 15%;" class="text-center">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($product_types as $product_type)
                                <tr>
                                    <th scope="row">{{ $loop->iteration + $product_types->firstItem() - 1 }}</th>
                                    <td>{{ $product_type->id }}</td>
                                    <td>{{ $product_type->category->name ?? 'N/A' }}</td>
                                    <td>{{ $product_type->name }}</td>
                                    <td>
                                        <div class="d-flex justify-content-center gap-2">
                                            <a href="{{ route('product_types.edit', $product_type->id) }}" class="btn btn-sm btn-warning">Edit</a>
                                            <form method="POST" action="{{ route('product_types.destroy', $product_type->id) }}" onsubmit="return confirm('Are you sure?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="text-center">No product types found matching your criteria.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-4">
                        {{ $product_types->links() }}
                    </div>

                </div>
            </div>
        </div>
    </div>

    <!-- Add Product Type Modal -->
    <div class="modal fade" id="productTypeModal" tabindex="-1" aria-labelledby="productTypeModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content dark:bg-gray-700">
                <div class="modal-header">
                    <h5 class="modal-title" id="productTypeModalLabel">Add New Product Type</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="productTypeForm" method="post" action="{{ route('product_types.store') }}">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="category_id" class="form-label">Parent Category</label>
                            <select class="form-control" id="category_id" name="category_id" required>
                                <option value="" selected disabled>Select a category...</option>
                                @foreach($categories as $category)
                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="name" class="form-label">Product Type Name</label>
                            <input type="text" class="form-control" id="name" name="name" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Save Product Type</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/choices.js/public/assets/scripts/choices.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize Choices.js for the modal's dropdown
            const modalCategorySelect = document.getElementById('category_id');
            if (modalCategorySelect) {
                new Choices(modalCategorySelect, { searchEnabled: true, shouldSort: false, itemSelectText: '', });
            }

            // Optional: Initialize Choices.js for the filter dropdown as well for consistency
            const filterCategorySelect = document.getElementById('filter_category');
            if (filterCategorySelect) {
                new Choices(filterCategorySelect, { searchEnabled: true, shouldSort: false, itemSelectText: '', });
            }
        });
    </script>
    @endpush
</x-app-layout>