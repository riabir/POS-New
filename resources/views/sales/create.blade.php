    <x-app-layout>
        <x-slot name="header">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Dashboard-Sales') }}
            </h2>
        </x-slot>
        </form>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900 dark:text-gray-100">
                        <div class="container">


                            <form method="POST" action="{{ route('products.store') }}">
                                @csrf
                                <table id="productTable" class="table">
                                    <thead>
                                        <tr>
                                            <th>Serial No.</th>
                                            <th>Item Name</th>
                                            <th>Unit Price</th>
                                            <th>Quantity</th>
                                            <th>Total Price</th>
                                            <th>Part Numbers</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <!-- Dynamic rows will be added here -->
                                    </tbody>
                                </table>
                                <button type="button" id="addRow" class="btn btn-primary">Add Product</button>
                                <button type="submit" class="btn btn-success">Save Products</button>
                            </form>

                        </div>
                    </div>
                </div>
            </div>

    </x-app-layout>