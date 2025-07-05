    <x-app-layout>
        <x-slot name="header">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Dashboard-Product') }}
            </h2>
        </x-slot>
        </form>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900 dark:text-gray-100">
                        <div class="container">
@if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

                            <form method="post" action="{{route('products.store')}}">
                                @csrf
                                <div class="mb-3">
                                    <label for="category_id" class="form-label">Category</label>
                                    <select class="form-select" id="category_id" name="category_id" required>
                                        <option value="">Select Category</option>
                                        @foreach($categories as $category)
                                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label for="product_type_id" class="form-label">Product Type</label>
                                    <select class="form-select" id="product_type_id" name="product_type_id" required disabled>
                                        <option value="">Select Product Type</option>
                                    </select>
                                </div>

                                <div class="mb-3">
                                    <label for="brand_id" class="form-label">Brand</label>
                                    <select class="form-select" id="brand_id" name="brand_id" required>
                                        <option value="">Select Brand</option>
                                        @foreach($brands as $brand)
                                        <option value="{{ $brand->id }}">{{ $brand->name }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="mb-3">
                                    <label for="model" class="form-label">Model</label>
                                    <input type="text" class="form-control" id="model" name="model" required>
                                </div>

                                <div class="mb-3">
                                    <label for="description" class="form-label">Description</label>
                                    <textarea class="form-control" id="description" name="description" rows="3" required></textarea>
                                </div>

                                <button type="submit" class="btn btn-primary">Submit</button>
                            </form>

                        </div>
                    </div>
                </div>
            </div>

            <script>
                $(document).ready(function() {
                    $('#category_id').change(function() {
                        var category_id = $(this).val();

                        if (category_id) {
                            $('#product_type_id').prop('disabled', false);
                            $.ajax({
                                url: "/getproducttypes",
                                type: "GET",
                                data: {
                                    id: category_id
                                },
                                success: function(data) {
                                    $('#product_type_id').empty();
                                    $('#product_type_id').append('<option value="">Select Product Type</option>');
                                    $.each(data, function(key, value) {
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