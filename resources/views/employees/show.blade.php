<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                Employee Profile
            </h2>
            <div class="flex items-center space-x-4">
                 <a href="{{ route('employees.index') }}" class="text-sm text-gray-500 hover:text-gray-700">
                    ← Back to All Employees
                </a>                  
            </div>

        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">

            <!-- Profile Header Card -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 flex items-center space-x-6">
                    <div class="flex-shrink-0">
                        <img class="h-24 w-24 rounded-full object-cover" 
                             src="{{ $employee->photo ? asset('storage/' . $employee->photo) : 'https://ui-avatars.com/api/?name='.urlencode($employee->fullName).'&color=7F9CF5&background=EBF4FF&size=128' }}" 
                             alt="{{ $employee->fullName }}">
                    </div>
                    <div>
                        <h3 class="text-2xl font-bold text-gray-900 dark:text-white">{{ $employee->fullName }}</h3>
                        <p class="text-md text-gray-600 dark:text-gray-400">{{ $employee->designation ?? 'No designation' }} · {{ $employee->department ?? 'No department' }}</p>
                        @if($employee->status)
                            <span class="mt-2 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">Active</span>
                        @else
                            <span class="mt-2 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">Inactive</span>
                        @endif
                    </div>
                </div>
            </div>

            <!-- NEW: Employee Information Card with Table-like Design -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h4 class="text-lg font-semibold text-gray-900 dark:text-white mb-6 border-b border-gray-200 dark:border-gray-700 pb-4">
                        Employee Information
                    </h4>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                        <!-- Column 1 -->
                        <div class="space-y-6">
                            <div>
                                <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Name</p>
                                <p class="mt-1 text-md text-gray-900 dark:text-white">{{ $employee->fullName }}</p>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Reporting Manager</p>
                                <p class="mt-1 text-md text-gray-900 dark:text-white">{{ $employee->reporting_manager ?? 'N/A' }}</p>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Branch</p>
                                <p class="mt-1 text-md text-gray-900 dark:text-white">{{ $employee->branches ?? 'N/A' }}</p>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Bank</p>
                                <p class="mt-1 text-md text-gray-900 dark:text-white">{{ $employee->bank_name ?? 'N/A' }}</p>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-500 dark:text-gray-400">NID</p>
                                <p class="mt-1 text-md text-gray-900 dark:text-white">{{ $employee->nid_number ?? 'N/A' }}</p>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Father's Name</p>
                                <p class="mt-1 text-md text-gray-900 dark:text-white">{{ $employee->fathers_name ?? 'N/A' }}</p>
                            </div>
                        </div>

                        <!-- Column 2 -->
                        <div class="space-y-6">
                            <div>
                                <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Phone</p>
                                <p class="mt-1 text-md text-gray-900 dark:text-white">{{ $employee->personal_phone_number }}</p>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Designation</p>
                                <p class="mt-1 text-md text-gray-900 dark:text-white">{{ $employee->designation ?? 'N/A' }}</p>
                            </div>
                             <div>
                                <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Bank Branch</p>
                                <p class="mt-1 text-md text-gray-900 dark:text-white">{{ $employee->bank_branch ?? 'N/A' }}</p>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Passport</p>
                                <p class="mt-1 text-md text-gray-900 dark:text-white">{{ $employee->passport_number ?? 'N/A' }}</p>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Mother's Name</p>
                                <p class="mt-1 text-md text-gray-900 dark:text-white">{{ $employee->mothers_name ?? 'N/A' }}</p>
                            </div>
                        </div>

                        <!-- Column 3 -->
                        <div class="space-y-6">
                            <div>
                                <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Email</p>
                                <p class="mt-1 text-md text-gray-900 dark:text-white">{{ $employee->email }}</p>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Employee ID</p>
                                <p class="mt-1 text-md text-gray-900 dark:text-white">{{ $employee->emp_id }}</p>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Department</p>
                                <p class="mt-1 text-md text-gray-900 dark:text-white">{{ $employee->department ?? 'N/A' }}</p>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Bank Account Number</p>
                                <p class="mt-1 text-md text-gray-900 dark:text-white">{{ $employee->bank_account_number ?? 'N/A' }}</p>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-500 dark:text-gray-400">TIN</p>
                                <p class="mt-1 text-md text-gray-900 dark:text-white">{{ $employee->tin_number ?? 'N/A' }}</p>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Emergency Contact</p>
                                <p class="mt-1 text-md text-gray-900 dark:text-white">{{ $employee->emergency_contact ?? 'N/A' }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Salary Structure Card -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h4 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Salary Structure</h4>
                    
                    <!-- Current Salary Display -->
                    @if($currentStructure = $employee->currentSalaryStructure())
                    <div class="mb-6 p-4 bg-blue-50 dark:bg-gray-700/50 rounded-lg">
                        <h5 class="font-semibold text-blue-800 dark:text-blue-300">Current Gross Salary: ${{ number_format($currentStructure->total_gross_salary, 2) }}</h5>
                        <p class="text-sm text-gray-600 dark:text-gray-400">(Effective since: {{ $currentStructure->effective_date->format('M d, Y') }})</p>
                    </div>
                    @else
                    <div class="mb-6 p-4 bg-yellow-50 dark:bg-gray-700/50 rounded-lg text-yellow-700 dark:text-yellow-300">
                        No current salary structure found.
                    </div>
                    @endif
                    
                    <h5 class="text-md font-semibold text-gray-800 dark:text-gray-200 mb-2 mt-6">History</h5>
                    <div class="overflow-x-auto border rounded-lg">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead class="bg-gray-50 dark:bg-gray-700">
                                <tr>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Effective Date</th>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Basic</th>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">House Rent</th>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Medical</th>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Conveyance</th>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Gross Total</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                @forelse($employee->salaryStructures as $structure)
                                <tr>
                                    <td class="px-4 py-3 text-sm">{{ $structure->effective_date->format('M d, Y') }}</td>
                                    <td class="px-4 py-3 text-sm">${{ number_format($structure->basic_salary, 2) }}</td>
                                    <td class="px-4 py-3 text-sm">${{ number_format($structure->house_rent_allowance, 2) }}</td>
                                    <td class="px-4 py-3 text-sm">${{ number_format($structure->medical_allowance, 2) }}</td>
                                    <td class="px-4 py-3 text-sm">${{ number_format($structure->conveyance_allowance, 2) }}</td>
                                    <td class="px-4 py-3 text-sm font-bold">${{ number_format($structure->total_gross_salary, 2) }}</td>
                                </tr>
                                @empty
                                <tr><td colspan="6" class="text-center py-4 text-sm text-gray-500">No salary history.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            
            <!-- Attachments Card -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h4 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Documents & Attachments</h4>
                    <ul class="space-y-3">
                        @if($employee->cv_pdf)
                        <li class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-700/50 rounded-md">
                            <span class="text-sm font-medium">Curriculum Vitae (CV)</span>
                            <a href="{{ asset('storage/' . $employee->cv_pdf) }}" target="_blank" class="text-sm text-indigo-600 dark:text-indigo-400 hover:underline">Download</a>
                        </li>
                        @endif
                        @if($employee->nid_attachment)
                        <li class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-700/50 rounded-md">
                            <span class="text-sm font-medium">NID Attachment</span>
                            <a href="{{ asset('storage/' . $employee->nid_attachment) }}" target="_blank" class="text-sm text-indigo-600 dark:text-indigo-400 hover:underline">Download</a>
                        </li>
                        @endif
                         @if($employee->certificate_attachment)
                        <li class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-700/50 rounded-md">
                            <span class="text-sm font-medium">Certificate Attachment</span>
                            <a href="{{ asset('storage/' . $employee->certificate_attachment) }}" target="_blank" class="text-sm text-indigo-600 dark:text-indigo-400 hover:underline">Download</a>
                        </li>
                        @endif
                        <!-- Add other attachments similarly -->
                    </ul>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
