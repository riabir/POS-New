<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard-Products') }}
        </h2>
    </x-slot>
    </form>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <div class="container">

                        <a href="{{ route('products.create') }}" class="btn btn-primary">Add New Product</a>
                        <br><br><br>

                        <h1>Produtcs List</h1>
                        <br>
                        @if(session('success'))
                            <div class="alert alert-success">
                                {{ session('success') }}
                            </div>
                        @endif

                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Product Caeagory</th>
                                    <th>Product Type </th>
                                    <th>Brand</th>
                                    <th>Model</th>
                                    <th>Descriptions </th>
                                    <th>Action</th>
                                </tr>
                            </thead>

                            <tbody>
                                @foreach($products as $product)
                                    <tr>
                                        <td>{{ $product->category->name}} </td>
                                        <td>{{ $product->productType->name }}</td>
                                        <td>{{ $product->brand->name }}</td>
                                        <td>{{ $product->model }}</td>
                                        <td>{{ $product->description }}</td>
                                        <td>
                                            <a href="{{ route('products.edit', $product->id) }}"
                                                class="btn btn-sm btn-warning me-2">Edit</a>

                                            <form method="POST"
                                                action="{{ route('products.destroy', $product->id) }}"
                                                style="display:inline;">
                                                @csrf
                                                @method('DELETE')
                                                <button onclick="return confirm('Are you sure?')" type="submit"
                                                    class="btn btn-sm btn-danger">
                                                    Delete
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>

                    </div>
                </div>
            </div>
        </div>
    </div>

</x-app-layout>