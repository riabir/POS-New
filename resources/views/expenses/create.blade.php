<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Apply for New Expense') }}
        </h2>
    </x-slot>
    
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/choices.js/public/assets/styles/choices.min.css" />

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <form method="POST" action="{{ route('expenses.store') }}" enctype="multipart/form-data" class="space-y-6">
                        @csrf
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            {{-- Employee --}}
                            <div>
                                <label for="employee_id" class="block font-medium text-sm">Employee*</label>
                                <select name="employee_id" id="employee_id" class="mt-1 block w-full rounded-md" required>
                                    <option value="">Select an employee</option>
                                    @foreach($employees as $employee)
                                        <option value="{{ $employee->id }}">{{ $employee->full_name }} ({{ $employee->designation }})</option>
                                    @endforeach
                                </select>
                            </div>
                            {{-- Cost Head (Expense Type) --}}
                            <div>
                                <label for="expense_type_id" class="block font-medium text-sm">Cost Head*</label>
                                <select name="expense_type_id" id="expense_type_id" class="mt-1 block w-full rounded-md" required>
                                    <option value="">Select an option</option>
                                     @foreach($expenseTypes as $type)
                                        <option value="{{ $type->id }}">{{ $type->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            {{-- From Date --}}
                            <div>
                                <label for="from_date" class="block font-medium text-sm">From date*</label>
                                <input type="date" name="from_date" id="from_date" class="mt-1 block w-full rounded-md" required>
                            </div>
                             {{-- To Date --}}
                            <div>
                                <label for="to_date" class="block font-medium text-sm">To date*</label>
                                <input type="date" name="to_date" id="to_date" class="mt-1 block w-full rounded-md" required>
                            </div>
                             {{-- Amount --}}
                            <div>
                                <label for="amount" class="block font-medium text-sm">Amount*</label>
                                <input type="number" step="0.01" name="amount" id="amount" class="mt-1 block w-full rounded-md" required>
                            </div>
                             {{-- Days --}}
                            <div>
                                <label for="days" class="block font-medium text-sm">Days*</label>
                                <input type="number" name="days" id="days" class="mt-1 block w-full rounded-md" required>
                            </div>
                            {{-- Total --}}
                            <div class="md:col-span-2">
                                <label for="total" class="block font-medium text-sm">Total*</label>
                                <input type="number" step="0.01" name="total" id="total" class="mt-1 block w-full rounded-md" readonly required>
                            </div>
                            {{-- Particulars --}}
                            <div class="md:col-span-2">
                                <label for="particulars" class="block font-medium text-sm">Particulars of Expense</label>
                                <textarea name="particulars" id="particulars" rows="4" class="mt-1 block w-full rounded-md"></textarea>
                            </div>
                            {{-- Voucher --}}
                             <div class="md:col-span-2">
                                <label for="voucher" class="block font-medium text-sm">Voucher</label>
                                <input type="file" name="voucher" id="voucher" class="mt-1 block w-full">
                            </div>
                        </div>
                        <div class="flex items-center gap-4 pt-4 border-t dark:border-gray-700">
                            <button type="submit" name="action" value="create" class="btn btn-primary">Create Expense</button>
                            <button type="submit" name="action" value="create_and_another" class="btn btn-secondary">Create Expense and Another</button>
                            <a href="{{ route('expenses.index') }}" class="btn">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/choices.js/public/assets/scripts/choices.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize searchable dropdowns
            new Choices(document.getElementById('employee_id'));
            new Choices(document.getElementById('expense_type_id'));
            
            // Auto-calculate total
            const amountInput = document.getElementById('amount');
            const daysInput = document.getElementById('days');
            const totalInput = document.getElementById('total');
            
            function calculateTotal() {
                const amount = parseFloat(amountInput.value) || 0;
                const days = parseInt(daysInput.value) || 0;
                totalInput.value = (amount * days).toFixed(2);
            }
            
            amountInput.addEventListener('input', calculateTotal);
            daysInput.addEventListener('input', calculateTotal);
        });
    </script>
    @endpush
</x-app-layout>