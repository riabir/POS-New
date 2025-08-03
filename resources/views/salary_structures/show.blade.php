<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Salary Structure Details') }}
            </h2>
            <a href="{{ route('salary_structures.index') }}" class="text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100">
                ← Back to List
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 md:p-8 text-gray-900 dark:text-gray-100">
                    
                    <div class="flex justify-between items-start">
                        <div>
                            <h3 class="text-lg font-medium">For: <span class="text-indigo-600 dark:text-indigo-400">{{ $salaryStructure->employee->fullName }}</span></h3>
                            <p class="text-sm text-gray-500">{{ $salaryStructure->employee->designation }} | ID: {{ $salaryStructure->employee->emp_id }}</p>
                        </div>
                        <div class="text-right">
                            <p class="text-sm font-medium">Effective Date</p>
                            <p class="text-gray-700 dark:text-gray-300">{{ $salaryStructure->effective_date->format('F j, Y') }}</p>
                        </div>
                    </div>

                    <div class="mt-6 border-t border-gray-200 dark:border-gray-700">
                        <dl class="divide-y divide-gray-200 dark:divide-gray-700">
                            <div class="py-4 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4">
                                <dt class="text-sm font-medium text-gray-500">Basic Salary</dt>
                                <dd class="mt-1 text-sm sm:mt-0 sm:col-span-2">${{ number_format($salaryStructure->basic_salary, 2) }}</dd>
                            </div>
                            <div class="py-4 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4">
                                <dt class="text-sm font-medium text-gray-500">House Rent Allowance</dt>
                                <dd class="mt-1 text-sm sm:mt-0 sm:col-span-2">${{ number_format($salaryStructure->house_rent_allowance, 2) }}</dd>
                            </div>
                            <div class="py-4 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4">
                                <dt class="text-sm font-medium text-gray-500">Medical Allowance</dt>
                                <dd class="mt-1 text-sm sm:mt-0 sm:col-span-2">${{ number_format($salaryStructure->medical_allowance, 2) }}</dd>
                            </div>
                            <div class="py-4 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4">
                                <dt class="text-sm font-medium text-gray-500">Conveyance Allowance</dt>
                                <dd class="mt-1 text-sm sm:mt-0 sm:col-span-2">${{ number_format($salaryStructure->conveyance_allowance, 2) }}</dd>
                            </div>
                            <div class="py-4 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 bg-gray-50 dark:bg-gray-700/50 font-bold">
                                <dt class="text-sm font-medium">Total Gross Salary</dt>
                                <dd class="mt-1 text-sm sm:mt-0 sm:col-span-2 text-green-600 dark:text-green-400">${{ number_format($salaryStructure->total_gross_salary, 2) }}</dd>
                            </div>
                            @if($salaryStructure->notes)
                            <div class="py-4 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4">
                                <dt class="text-sm font-medium text-gray-500">Notes</dt>
                                <dd class="mt-1 text-sm sm:mt-0 sm:col-span-2 whitespace-pre-wrap">{{ $salaryStructure->notes }}</dd>
                            </div>
                            @endif
                        </dl>
                    </div>

                    <div class="mt-6 flex justify-end gap-4">
                        <a href="{{ route('salary_structures.edit', $salaryStructure->id) }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-500 active:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                            Edit
                        </a>
                         @if (auth()->user()->role === 'admin')
                        <form method="POST" action="{{ route('salary_structures.destroy', $salaryStructure->id) }}" onsubmit="return confirm('Are you sure?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-500 active:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                                Delete
                            </button>
                        </form>
                         @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>