<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Manage Expense Types
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">

                    {{-- Header and "Add New" Button --}}
                    <div class="flex justify-between items-center mb-4">
                        <h1 class="text-2xl font-semibold">Expense Type List</h1>
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#expenseTypeModal">
                            + Add New Expense Type(s)
                        </button>
                    </div>

                    {{-- Session Success Message --}}
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif
                    
                    {{-- ================================== --}}
                    {{-- FILTER FORM --}}
                    {{-- ================================== --}}
                    <div class="card mb-4 dark:bg-gray-700">
                        <div class="card-body">
                            <h5 class="card-title">Filter Expense Types</h5>
                            <form action="{{ route('expense_types.index') }}" method="GET">
                                <div class="row g-3 align-items-end">
                                    <div class="col-md-3">
                                        <label for="filter_id" class="form-label">Type ID</label>
                                        <input type="number" name="id" id="filter_id" value="{{ request('id') }}" class="form-control" placeholder="e.g., 5">
                                    </div>
                                    <div class="col-md-5">
                                        <label for="filter_name" class="form-label">Type Name</label>
                                        <input type="text" name="name" id="filter_name" value="{{ request('name') }}" class="form-control" placeholder="Search by name...">
                                    </div>
                                    <div class="col-md-4">
                                        <button type="submit" class="btn btn-primary">Filter</button>
                                        <a href="{{ route('expense_types.index') }}" class="btn btn-secondary">Clear</a>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>

                    {{-- ================================== --}}
                    {{-- MAIN DATA TABLE --}}
                    {{-- ================================== --}}
                    <div class="table-responsive">
                        <table class="table table-striped table-hover table-bordered">
                            <thead class="table-light">
                                <tr>
                                    <th scope="col" style="width: 5%;">#</th>
                                    <th scope="col" style="width: 10%;">ID</th>
                                    <th scope="col">Expense Type Name</th>
                                    <th scope="col" style="width: 15%;" class="text-center">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($expense_types as $expense_type)
                                    <tr>
                                        <th scope="row">{{ $loop->iteration + $expense_types->firstItem() - 1 }}</th>
                                        <td>{{ $expense_type->id }}</td>
                                        <td>{{ $expense_type->name }}</td>
                                        <td>
                                            <div class="d-flex justify-content-center gap-2">
                                                <a href="{{ route('expense_types.edit', $expense_type->id) }}" class="btn btn-sm btn-warning">Edit</a>
                                                <form method="POST" action="{{ route('expense_types.destroy', $expense_type->id) }}" onsubmit="return confirm('Are you sure?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center">No expense types found matching your criteria.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-4">
                        {{ $expense_types->links() }}
                    </div>

                </div>
            </div>
        </div>
    </div>

    <!-- Add Multiple Expense Types Modal -->
    <div class="modal fade" id="expenseTypeModal" tabindex="-1" aria-labelledby="expenseTypeModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content dark:bg-gray-700">
                <div class="modal-header">
                    <h5 class="modal-title" id="expenseTypeModalLabel">Add New Expense Type(s)</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form method="post" action="{{ route('expense_types.store') }}">
                    @csrf
                    <div class="modal-body">
                        
                        <div id="expense-type-inputs-container">
                            <div class="input-group mb-2">
                                <input type="text" class="form-control" name="names[]" placeholder="Expense Type Name" required>
                                <button type="button" class="btn btn-danger remove-expense-type-input" disabled>X</button>
                            </div>
                        </div>

                        <button type="button" id="add-expense-type-input" class="btn btn-outline-success mt-2">
                            + Add Another
                        </button>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Save Types</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const container = document.getElementById('expense-type-inputs-container');
                const addButton = document.getElementById('add-expense-type-input');

                const addInput = () => {
                    const inputGroup = document.createElement('div');
                    inputGroup.className = 'input-group mb-2';
                    inputGroup.innerHTML = `
                        <input type="text" class="form-control" name="names[]" placeholder="Another Expense Type Name" required>
                        <button type="button" class="btn btn-danger remove-expense-type-input">X</button>
                    `;
                    container.appendChild(inputGroup);
                    updateRemoveButtons();
                };

                const updateRemoveButtons = () => {
                    const removeButtons = container.querySelectorAll('.remove-expense-type-input');
                    removeButtons.forEach(button => {
                        button.disabled = (removeButtons.length === 1);
                    });
                };

                addButton.addEventListener('click', addInput);

                container.addEventListener('click', function(event) {
                    if (event.target.classList.contains('remove-expense-type-input')) {
                        event.target.closest('.input-group').remove();
                        updateRemoveButtons();
                    }
                });
                
                updateRemoveButtons();
            });
        </script>
    @endpush
</x-app-layout>