<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Add New Brand') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <form method="post" action="{{ route('brands.store') }}" class="space-y-6">
                        @csrf
                        <div>
                            <label for="category_id" class="block font-medium text-sm">Category</label>
                            <select id="category_id" name="category_id" class="mt-1 block w-full rounded-md shadow-sm" required>
                                <option value="">Select Category</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label for="product_type_id" class="block font-medium text-sm">Product Type</label>
                            <select id="product_type_id" name="product_type_id" class="mt-1 block w-full rounded-md shadow-sm" required disabled>
                                <option value="">First Select a Category</option>
                            </select>
                        </div>
                        <div>
                            <label for="name" class="block font-medium text-sm">Brand Name</label>
                            <input type="text" id="name" name="name" value="{{ old('name') }}" class="mt-1 block w-full rounded-md shadow-sm" required>
                        </div>
                        <div class="flex items-center gap-4 border-t pt-6">
                            <button type="submit" class="btn btn-primary">Save Brand</button>
                            <a href="{{ route('brands.index') }}" class="btn btn-secondary">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    
    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const categorySelect = document.getElementById('category_id');
            const productTypeSelect = document.getElementById('product_type_id');
            categorySelect.addEventListener('change', function() {
                const categoryId = this.value;
                productTypeSelect.innerHTML = '<option value="">Loading...</option>';
                if (categoryId) {
                    productTypeSelect.disabled = false;
                    fetch(`/getproducttypes?id=${categoryId}`)
                        .then(response => response.json())
                        .then(data => {
                            productTypeSelect.innerHTML = '<option value="">Select Product Type</option>';
                            data.forEach(type => {
                                productTypeSelect.innerHTML += `<option value="${type.id}">${type.name}</option>`;
                            });
                        });
                } else {
                    productTypeSelect.innerHTML = '<option value="">First Select a Category</option>';
                    productTypeSelect.disabled = true;
                }
            });
        });
    </script>
    @endpush
</x-app-layout>