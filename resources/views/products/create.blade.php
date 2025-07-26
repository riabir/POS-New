<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Add New Product') }}
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

                    <form method="post" action="{{ route('products.store') }}" class="space-y-6">
                        @csrf
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            {{-- Category --}}
                            <div>
                                <label for="category_id" class="block font-medium text-sm">Category</label>
                                <select id="category_id" name="category_id" class="mt-1 block w-full rounded-md shadow-sm" required>
                                    <option value="">Select Category</option>
                                    @foreach($categories as $category)
                                    <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- Product Type --}}
                            <div>
                                <label for="product_type_id" class="block font-medium text-sm">Product Type</label>
                                <select id="product_type_id" name="product_type_id" class="mt-1 block w-full rounded-md shadow-sm" required disabled>
                                    <option value="">First Select a Category</option>
                                </select>
                            </div>

                            {{-- Brand --}}
                            <div>
                                <label for="brand_id" class="block font-medium text-sm">Brand</label>
                                <select id="brand_id" name="brand_id" class="mt-1 block w-full rounded-md shadow-sm" required>
                                    <option value="">Select Brand</option>
                                    @foreach($brands as $brand)
                                    <option value="{{ $brand->id }}" {{ old('brand_id') == $brand->id ? 'selected' : '' }}>{{ $brand->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                             {{-- Model --}}
                             <div>
                                <label for="model" class="block font-medium text-sm">Model</label>
                                <input type="text" id="model" name="model" value="{{ old('model') }}" class="mt-1 block w-full rounded-md shadow-sm" required>
                            </div>
                        </div>

                        {{-- Header --}}
                        <div>
                            <label for="header" class="block font-medium text-sm">Product Header / Title</label>
                            <textarea id="header" name="header" rows="3" class="mt-1 block w-full rounded-md shadow-sm" required>{{ old('header') }}</textarea>
                        </div>
                        
                        {{-- MRP --}}
                        <div>
                            <label for="mrp" class="block font-medium text-sm">MRP (Selling Price)</label>
                            <input type="number" step="0.01" id="mrp" name="mrp" value="{{ old('mrp', 0) }}" class="mt-1 block w-full rounded-md shadow-sm" required>
                        </div>
                        
                        {{-- Description --}}
                        <div>
                            <label for="description" class="block font-medium text-sm">Description (Marketing Pitch)</label>
                            <textarea id="description" name="description" rows="3" class="mt-1 block w-full rounded-md shadow-sm">{{ old('description') }}</textarea>
                        </div>

                        {{-- DYNAMIC SPECIFICATIONS --}}
                        <div class="border-t pt-6 mt-6">
                            <h3 class="font-semibold text-lg">Specifications</h3>
                            <div id="specifications-container" class="mt-2 space-y-3">
                                {{-- JS will add specification rows here --}}
                            </div>
                            <button type="button" id="add-spec-btn" class="btn btn-secondary mt-2 text-sm">+ Add Specification</button>
                        </div>

                        {{-- Form Actions --}}
                        <div class="flex items-center gap-4 border-t pt-6 mt-6">
                            <button type="submit" class="btn btn-primary">Save Product</button>
                            <a href="{{ route('products.index') }}" class="btn btn-secondary">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    
    {{-- Scripts --}}
    @push('scripts')
    {{-- Make sure you have jQuery loaded in your app layout for this AJAX to work --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Dependent Dropdown Logic
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

            // Dynamic Specifications Logic
            const container = document.getElementById('specifications-container');
            const addBtn = document.getElementById('add-spec-btn');
            let specIndex = 0;

            const addSpecRow = (key = '', value = '') => {
                const newRow = document.createElement('div');
                newRow.classList.add('flex', 'items-center', 'gap-2');
                newRow.innerHTML = `
                    <input type="text" name="specifications[${specIndex}][key]" class="block w-1/3 rounded-md shadow-sm" placeholder="e.g., RAM" value="${key}">
                    <input type="text" name="specifications[${specIndex}][value]" class="block w-2/3 rounded-md shadow-sm" placeholder="e.g., 16GB DDR5" value="${value}">
                    <button type="button" class="btn btn-danger remove-spec-btn p-2 leading-none rounded-md">X</button>
                `;
                container.appendChild(newRow);
                specIndex++;
            };

            addBtn.addEventListener('click', () => addSpecRow());

            container.addEventListener('click', function(e) {
                if (e.target.classList.contains('remove-spec-btn')) {
                    e.target.parentElement.remove();
                }
            });

            // Add an initial empty row
            addSpecRow();
        });
    </script>
    @endpush
</x-app-layout>