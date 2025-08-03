@csrf
<div class="grid grid-cols-1 md:grid-cols-2 gap-6">
    <div>
        <x-input-label for="employee_id" :value="__('Employee')" />
        <select id="employee_id" name="employee_id" class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm" {{ isset($payout) ? 'disabled' : 'required' }}>
            <option value="">Select an Employee</option>
            @foreach($employees as $employee)
                <option value="{{ $employee->id }}" {{ old('employee_id', $payout->employee_id ?? '') == $employee->id ? 'selected' : '' }}>
                    {{ $employee->fullName }} ({{ $employee->emp_id }})
                </option>
            @endforeach
        </select>
        @if(isset($payout))
            <input type="hidden" name="employee_id" value="{{ $payout->employee_id }}">
            <p class="text-sm text-gray-500 mt-1">Employee cannot be changed during an edit.</p>
        @endif
        <x-input-error :messages="$errors->get('employee_id')" class="mt-2" />
    </div>
    <div>
        <x-input-label for="payout_date" :value="__('Payout Date')" />
        <x-text-input id="payout_date" class="block mt-1 w-full" type="date" name="payout_date" :value="old('payout_date', isset($payout) ? $payout->payout_date->format('Y-m-d') : '')" required />
        <x-input-error :messages="$errors->get('payout_date')" class="mt-2" />
    </div>
    <div class="md:col-span-2">
        <x-input-label for="payout_type" :value="__('Payout Type')" />
        <select id="payout_type" name="payout_type" class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm" required>
            <option value="">Select a Type</option>
            @foreach($payoutTypes as $type)
                <option value="{{ $type }}" {{ old('payout_type', $payout->payout_type ?? '') == $type ? 'selected' : '' }}>
                    {{ $type }}
                </option>
            @endforeach
        </select>
        <x-input-error :messages="$errors->get('payout_type')" class="mt-2" />
    </div>
</div>

<div x-data="{ mode: '{{ old('calculation_mode', 'fixed') }}' }" class="mt-6 border-t border-gray-200 dark:border-gray-700 pt-6">
    <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-2">Amount Calculation</h3>
    <div class="flex items-center space-x-4 mb-4">
        <label class="flex items-center">
            <input type="radio" x-model="mode" name="calculation_mode" value="fixed" class="form-radio text-indigo-600">
            <span class="ml-2">Fixed Amount</span>
        </label>
        <label class="flex items-center">
            <input type="radio" x-model="mode" name="calculation_mode" value="percentage" class="form-radio text-indigo-600">
            <span class="ml-2">Percentage of Basic Salary</span>
        </label>
    </div>
    <div x-show="mode === 'fixed'">
        <x-input-label for="amount" :value="__('Amount')" />
        <x-text-input id="amount" class="block mt-1 w-full" type="number" step="0.01" name="amount" :value="old('amount', $payout->amount ?? '')" />
        <x-input-error :messages="$errors->get('amount')" class="mt-2" />
    </div>
    <div x-show="mode === 'percentage'" style="display: none;">
        <x-input-label for="percentage" :value="__('Percentage (%)')" />
        <x-text-input id="percentage" class="block mt-1 w-full" type="number" step="0.01" name="percentage" :value="old('percentage')" />
        <x-input-error :messages="$errors->get('percentage')" class="mt-2" />
    </div>
</div>

<div class="mt-6">
    <x-input-label for="notes" :value="__('Notes')" />
    <textarea id="notes" name="notes" rows="3" class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">{{ old('notes', $payout->notes ?? '') }}</textarea>
    <x-input-error :messages="$errors->get('notes')" class="mt-2" />
</div>

<div class="flex items-center justify-end mt-6">
    <a href="{{ route('payouts.index') }}" class="text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800">
        {{ __('Cancel') }}
    </a>
    <x-primary-button class="ml-4">
        {{ $submitButtonText ?? 'Save' }}
    </x-primary-button>
</div>