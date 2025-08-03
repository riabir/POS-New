<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Edit Brand: ') }} <span class="text-indigo-600">{{ $brand->name }}</span>
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">

                    @if ($errors->any())
                        <div class="mb-4 bg-red-100 border-l-4 border-red-500 text-red-700 p-4" role="alert">
                            <p class="font-bold">Please correct the errors below:</p>
                            <ul class="mt-2 list-disc list-inside text-sm">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    {{-- Note the updated action and method --}}
                    <form method="post" action="{{ route('brands.update', $brand->id) }}" class="space-y-6">
                        @csrf
                        @method('PATCH')

                        {{-- Category --}}
                        <div>
                            <label for="category_id" class="block font-medium text-sm">Category</label>
                            <select id="category_id" name="category_id" class="mt-1 block w-full rounded-md shadow-sm" required>
                                {{-- Loop through all available categories --}}
                                @foreach($categories as $category)
                                    {{-- The 'old()' helper handles failed validation, otherwise use the existing brand's category --}}
                                    <option value="{{ $category->id }}" {{ old('category_id', $brand->category_id) == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Product Type --}}
                        <div>
                            <label for="product_type_id" class="block font-medium text-sm">Product Type</label>
                            <select id="product_type_id" name="product_type_id" class="mt-1 block w-full rounded-md shadow-sm" required>
                                {{-- Loop through the product types for the currently selected category --}}
                                @foreach($productTypes as $type)
                                    <option value="{{ $type->id }}" {{ old('product_type_id', $brand->product_type_id) == $type->id ? 'selected' : '' }}>
                                        {{ $type->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Brand Name --}}
                        <div>
                            <label for="name" class="block font-medium text-sm">Brand Name</label>
                            <input type="text" id="name" name="name" value="{{ old('name', $brand->name) }}" class="mt-1 block w-full rounded-md shadow-sm" required>
                        </div>

                        {{-- Form Actions --}}
                        <div class="flex items-center gap-4 border-t pt-6">
                            <button type="submit" class="btn btn-primary">Update Brand</button>
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
                    // Use the route helper for the AJAX URL
                    fetch("{{ route('brands.getProductTypes') }}?id=" + categoryId)
                        .then(response => response.json())
                        .then(data => {
                            productTypeSelect.innerHTML = '<option value="">Select Product Type</option>';
                            data.forEach(type => {
                                productTypeSelect.innerHTML += `<option value="${type.id}">${type.name}</option>`;
                            });
                        })
                        .catch(error => {
                            console.error('Error fetching product types:', error);
                            productTypeSelect.innerHTML = '<option value="">Could not load types</option>';
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