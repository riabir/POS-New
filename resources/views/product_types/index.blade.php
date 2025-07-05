<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard-Product Types') }}
        </h2>
    </x-slot>

    </form>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <div class="container">

                        <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                            data-bs-target="#registerModal">
                            Product Types
                        </button>

                        <div class="modal fade" id="registerModal" tabindex="-1" aria-labelledby="registerModalLabel"
                            aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="registerModalLabel">Add Product Types</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                            aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <!-- categories Form -->
                                        <form id="product_typeForm" method="post"
                                            action="{{route('product_types.store')}}">
                                            @csrf

                                            <div class="mb-3">
                                                <label for="category_id" class="form-label">Category</label>
                                                <select class="form-select" id="category_id" name="category_id"
                                                    required>
                                                    <option value="">Select Category</option>
                                                    @foreach($categories as $category)
                                                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>

                                            <!-- Product Types -->
                                            <div class="mb-3">
                                                <label for="name" class="form-label">Product Types</label>
                                                <input type="text" class="form-control" id="name" name="name">
                                            </div>

                                            <div class="modal-footer">
                                                <button type="submit" class="btn btn-primary">Save</button>
                                                <button type="button" class="btn btn-secondary"
                                                    onclick="clearForm()">Clear</button>


                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <br><br><br>


                        <h1>Product Types List</h1>
                        <br>
                        @if(session('success'))
                            <div class="alert alert-success">
                                {{ session('success') }}
                            </div>
                        @endif

                        <table class="table">
                            <thead>
                                <tr>

                                    <th>No</th>
                                    <th>Categorie</th>
                                    <th>Product Types</th>
                                    <th>Action</th>
                                    <th></th>
                                </tr>
                            </thead>

                            <tbody>

                                @foreach($product_types as $product_type)
                                    <tr>
                                        <td>{{ $product_type->id }} </td>
                                        <!--  -->
                                        <td>{{ $product_type->Category->name }} </td>
                                        <td>{{ $product_type->name }} </td>
                                      
                                        <td>
                                            <a href="{{ route('product_types.edit', $product_type->id) }}"
                                                class="btn btn-sm btn-warning me-2">Edit</a>

                                            <form method="POST"
                                                action="{{ route('product_types.destroy', $product_type->id) }}"
                                                style="display:inline;">
                                                @csrf
                                                @method('DELETE')
                                                <button onclick="return confirm('Are you sure?')" type="submit"
                                                    class="btn btn-sm btn-danger">
                                                    Delete
                                                </button>
                                            </form>
                                        </td>

                                @endforeach
                            </tbody>
                        </table>

                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function clearForm() {
            document.getElementById("product_typeForm").reset();
        }
    </script>




    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

</x-app-layout>