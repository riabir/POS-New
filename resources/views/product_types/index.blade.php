<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Manage Product Types
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">

                    {{-- 1. Page Header and "Add New" Button --}}
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h1 class="h3 mb-0">Product Type List</h1>
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#productTypeModal">
                            + Add New Product Type
                        </button>
                    </div>

                    {{-- 2. Styled Session Success Message --}}
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    {{-- 3. Well-Styled and Responsive Table --}}
                    <div class="table-responsive">
                        <table class="table table-striped table-hover table-bordered">
                            <thead class="table-light">
                                <tr>
                                    <th scope="col" style="width: 5%;">#</th>
                                    <th scope="col">Product Type</th>
                                    <th scope="col">Parent Category</th>
                                    <th scope="col" style="width: 15%;" class="text-center">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                {{-- 4. Using @forelse for a clean empty state --}}
                                @forelse($product_types as $product_type)
                                    <tr>
                                        <th scope="row">{{ $loop->iteration }}</th>
                                        <td>{{ $product_type->name }}</td>
                                        <td>{{ $product_type->category->name }}</td>
                                        <td>
                                            {{-- 5. Actions in a flex container for alignment --}}
                                            <div class="d-flex justify-content-center gap-2">
                                                <a href="{{ route('product_types.edit', $product_type->id) }}" class="btn btn-sm btn-warning">Edit</a>
                                                <form method="POST" action="{{ route('product_types.destroy', $product_type->id) }}" onsubmit="return confirm('Are you sure you want to delete this?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center">No product types found.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    {{-- 6. Pagination Links --}}
                    <div class="mt-4">
                        {{-- Make sure you are using ->paginate() in your controller for this to work --}}
                        {{-- $product_types->links() --}}
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
                        <!-- Category Dropdown -->
                        <div class="mb-3">
                            <label for="category_id" class="form-label">Parent Category</label>
                            <select class="form-select" id="category_id" name="category_id" required>
                                <option value="" selected disabled>Select a category...</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Product Type Name -->
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

    {{-- 7. Pushing Scripts correctly using Laravel's stack system --}}
    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    @endpush
</x-app-layout>