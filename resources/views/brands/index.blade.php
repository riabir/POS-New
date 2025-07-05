<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard-Brand') }}
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
                            New Brand
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
                                        <form id="product_typeForm" method="post" action="{{route('brands.store')}}">
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

                                            <div class="mb-3">
                                                <label for="product_type_id" class="form-label">Product Type</label>
                                                <select class="form-select" id="product_type_id" name="product_type_id"
                                                    required disabled>
                                                    <option value="">Select Product Type</option>
                                                </select>
                                            </div>

                                            <!-- Brand -->
                                            <div class="mb-3">
                                                <label for="name" class="form-label">Brand</label>
                                                <input type="text" class="form-control" id="name" name="name" required>
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


                        <h1>Brand List</h1>
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
                                    <th>Brand</th>
                                    <th>Edit</th>
                                    <th>Delete</th>
                                    <th></th>
                                </tr>
                            </thead>

                            <tbody>

                                @foreach($brands as $brand)
                                    <tr>
                                        <td>{{ $brand->id }} </td>
                                        <!--  -->
                                        <td>{{ $brand->Category->name }} </td>
                                        <td>{{ $brand->ProductType->name }} </td>
                                        <td>{{ $brand->name }} </td>
                                        <td>
                                            <a href="{{route('brands.edit', $brand->id)}}"
                                                class="btn btn-sm btn-warning">Edit</a>
                                        </td>
                                        <td>
                                            <form method="POST" action="{{route('brands.destroy', $brand->id)}}">
                                                @csrf
                                                @method('DELETE')
                                                <button onclick="return confirm('are you sure')"
                                                    type="submit">Delete</button>
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
    <script>
        $(document).ready(function () {
            $('#category_id').change(function () {
                var category_id = $(this).val();

                if (category_id) {
                    $('#product_type_id').prop('disabled', false);
                    $.ajax({
                        url: "/getproducttypes",
                        type: "GET",
                        data: {
                            id: category_id
                        },
                        success: function (data) {
                            $('#product_type_id').empty();
                            $('#product_type_id').append('<option value="">Select Product Type</option>');
                            $.each(data, function (key, value) {
                                $('#product_type_id').append('<option value="' + value.id + '">' + value.name + '</option>');
                            });
                        }
                    });
                } else {
                    $('#product_type_id').prop('disabled', true);
                    $('#product_type_id').empty();
                    $('#product_type_id').append('<option value="">Select Product Type</option>');
                }
            });
        });
    </script>

</x-app-layout>