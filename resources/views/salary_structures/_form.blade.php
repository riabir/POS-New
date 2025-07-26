@csrf
<div class="grid grid-cols-1 md:grid-cols-2 gap-6">
    <!-- Employee -->
    <div>
        <x-input-label for="employee_id" :value="__('Employee')" />
        <select id="employee_id" name="employee_id" class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm" {{ isset($salaryStructure) ? 'disabled' : 'required' }}>
            <option value="">Select an Employee</option>
            @foreach($employees as $employee)
                <option value="{{ $employee->id }}" {{ old('employee_id', $salaryStructure->employee_id ?? '') == $employee->id ? 'selected' : '' }}>
                    {{ $employee->fullName }} ({{ $employee->emp_id }})
                </option>
            @endforeach
        </select>
        @if(isset($salaryStructure))
            <input type="hidden" name="employee_id" value="{{ $salaryStructure->employee_id }}">
            <p class="text-sm text-gray-500 mt-1">Employee cannot be changed during an edit.</p>
        @endif
        <x-input-error :messages="$errors->get('employee_id')" class="mt-2" />
    </div>

    <!-- Effective Date -->
    <div>
        <x-input-label for="effective_date" :value="__('Effective Date')" />
        <x-text-input id="effective_date" class="block mt-1 w-full" type="date" name="effective_date" :value="old('effective_date', isset($salaryStructure) ? $salaryStructure->effective_date->format('Y-m-d') : '')" required />
        <x-input-error :messages="$errors->get('effective_date')" class="mt-2" />
    </div>

    <!-- Basic Salary -->
    <div>
        <x-input-label for="basic_salary" :value="__('Basic Salary')" />
        <x-text-input id="basic_salary" class="block mt-1 w-full" type="number" step="0.01" name="basic_salary" :value="old('basic_salary', $salaryStructure->basic_salary ?? '')" required />
        <x-input-error :messages="$errors->get('basic_salary')" class="mt-2" />
    </div>

    <!-- House Rent Allowance -->
    <div>
        <x-input-label for="house_rent_allowance" :value="__('House Rent Allowance')" />
        <x-text-input id="house_rent_allowance" class="block mt-1 w-full" type="number" step="0.01" name="house_rent_allowance" :value="old('house_rent_allowance', $salaryStructure->house_rent_allowance ?? '')" required />
        <x-input-error :messages="$errors->get('house_rent_allowance')" class="mt-2" />
    </div>

    <!-- Medical Allowance -->
    <div>
        <x-input-label for="medical_allowance" :value="__('Medical Allowance')" />
        <x-text-input id="medical_allowance" class="block mt-1 w-full" type="number" step="0.01" name="medical_allowance" :value="old('medical_allowance', $salaryStructure->medical_allowance ?? '')" required />
        <x-input-error :messages="$errors->get('medical_allowance')" class="mt-2" />
    </div>

    <!-- Conveyance Allowance -->
    <div>
        <x-input-label for="conveyance_allowance" :value="__('Conveyance Allowance')" />
        <x-text-input id="conveyance_allowance" class="block mt-1 w-full" type="number" step="0.01" name="conveyance_allowance" :value="old('conveyance_allowance', $salaryStructure->conveyance_allowance ?? '')" required />
        <x-input-error :messages="$errors->get('conveyance_allowance')" class="mt-2" />
    </div>
</div>

<!-- Notes -->
<div class="mt-6">
    <x-input-label for="notes" :value="__('Notes')" />
    <textarea id="notes" name="notes" class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">{{ old('notes', $salaryStructure->notes ?? '') }}</textarea>
    <x-input-error :messages="$errors->get('notes')" class="mt-2" />
</div>

<div class="flex items-center justify-end mt-6">
    <a href="{{ route('salary_structures.index') }}" class="text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800">
        {{ __('Cancel') }}
    </a>
    <x-primary-button class="ml-4">
        {{ $submitButtonText ?? 'Save' }}
    </x-primary-button>
</div>