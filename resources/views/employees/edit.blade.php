<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                Edit Employee: {{ $employee->fullName }}
            </h2>
            <a href="{{ route('employees.show', $employee->id) }}" class="">
                ← Back to Profile
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 md:p-12 text-gray-900 dark:text-gray-100">

                    <form id="employeeForm" method="post" action="{{ route('employees.update', $employee->id) }}" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <!-- PROFILE SECTION -->
                        <h3 class="text-lg font-bold border-b border-gray-200 dark:border-gray-700 pb-2 mb-6">Profile Uploads</h3>
                        <p class="text-sm text-gray-600 dark:text-gray-400 mb-6">Only upload a new file if you want to replace the existing one.</p>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                            <div>
                                <label for="photo" class="block text-sm font-medium">Photo</label>
                                <input type="file" id="photo" name="photo" class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                                @if($employee->photo)
                                    <div class="mt-2 text-sm">Current: <a href="{{ asset('storage/' . $employee->photo) }}" target="_blank" class="text-indigo-600 hover:underline">View Photo</a></div>
                                @endif
                                @error('photo') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label for="nid_attachment" class="block text-sm font-medium">NID Photo/PDF</label>
                                <input type="file" id="nid_attachment" name="nid_attachment" class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                                @if($employee->nid_attachment)
                                    <div class="mt-2 text-sm">Current: <a href="{{ asset('storage/' . $employee->nid_attachment) }}" target="_blank" class="text-indigo-600 hover:underline">View NID</a></div>
                                @endif
                                @error('nid_attachment') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label for="cv_pdf" class="block text-sm font-medium">CV PDF</label>
                                <input type="file" id="cv_pdf" name="cv_pdf" class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                                @if($employee->cv_pdf)
                                    <div class="mt-2 text-sm">Current: <a href="{{ asset('storage/' . $employee->cv_pdf) }}" target="_blank" class="text-indigo-600 hover:underline">View CV</a></div>
                                @endif
                                @error('cv_pdf') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <!-- MAIN DETAILS SECTION -->
                        <h3 class="text-lg font-bold border-b border-gray-200 dark:border-gray-700 pb-2 mb-6">Main Details</h3>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-x-6 gap-y-4">
                             <div>
                                <label for="first_name">First Name <span class="text-red-500">*</span></label>
                                <input type="text" id="first_name" name="first_name" value="{{ old('first_name', $employee->first_name) }}" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                                @error('first_name') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label for="last_name">Last Name <span class="text-red-500">*</span></label>
                                <input type="text" id="last_name" name="last_name" value="{{ old('last_name', $employee->last_name) }}" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                                @error('last_name') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label for="email">Email <span class="text-red-500">*</span></label>
                                <input type="email" id="email" name="email" value="{{ old('email', $employee->email) }}" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                                @error('email') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label for="personal_phone_number">Phone <span class="text-red-500">*</span></label>
                                <input type="text" id="personal_phone_number" name="personal_phone_number" value="{{ old('personal_phone_number', $employee->personal_phone_number) }}" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                                @error('personal_phone_number') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label for="emp_id">Employee ID</label>
                                <input type="text" id="emp_id" name="emp_id" value="{{ $employee->emp_id }}" readonly class="mt-1 block w-full bg-gray-100 dark:bg-gray-700 border-gray-300 rounded-md shadow-sm cursor-not-allowed">
                            </div>
                             <!-- ... all other text fields ... -->
                            <div>
                                <label for="reporting_manager">Reporting Manager</label>
                                <input type="text" id="reporting_manager" name="reporting_manager" value="{{ old('reporting_manager', $employee->reporting_manager) }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                            </div>
                             <div>
                                <label for="date_of_birth">Date of Birth</label>
                                <input type="date" id="date_of_birth" name="date_of_birth" value="{{ old('date_of_birth', $employee->date_of_birth?->format('Y-m-d')) }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                            </div>
                             <div>
                                <label for="date_of_join">Date of Join</label>
                                <input type="date" id="date_of_join" name="date_of_join" value="{{ old('date_of_join', $employee->date_of_join?->format('Y-m-d')) }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                            </div>
                            <div>
                                <label for="date_of_probation">Date Of Probation</label>
                                <input type="date" id="date_of_probation" name="date_of_probation" value="{{ old('date_of_probation', $employee->date_of_probation?->format('Y-m-d')) }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                            </div>

                            <!-- EMPLOYEE STATUS TOGGLE -->
                            <div class="md:col-span-3">
                                <label for="status" class="mt-4 block mb-2 font-medium">Employee Status</label>
                                <p class="text-sm text-gray-600 dark:text-gray-400 mb-2">Use this to mark an employee as Inactive if they have been terminated or have resigned.</p>
                                <label for="status-toggle" class="inline-flex items-center cursor-pointer">
                                  <input type="hidden" name="status" value="0">
                                  <input id="status-toggle" type="checkbox" name="status" value="1" class="sr-only peer" 
                                        {{ old('status', $employee->status) ? 'checked' : '' }}>
                                  <div class="relative w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 dark:peer-focus:ring-blue-800 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-green-600"></div>
                                  <span id="status-label" class="ms-3 text-sm font-medium text-gray-900 dark:text-gray-300">
                                      {{ old('status', $employee->status) ? 'Active' : 'Inactive' }}
                                  </span>
                                </label>
                            </div>
                        </div>

                        <!-- OTHER INFO, ADDRESS, EDUCATION SECTIONS... -->
                        
                        <div class="mt-8 flex justify-end">
                            <a href="{{ route('employees.show', $employee->id) }}" class="px-6 py-2 text-gray-800 dark:text-gray-200 rounded-md hover:bg-gray-200 dark:hover:bg-gray-700 mr-4">Cancel</a>
                            <button type="submit" class="btn btn-primary"> Update Employee</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Script to dynamically update the "Active/Inactive" label next to the toggle
        const statusToggle = document.getElementById('status-toggle');
        const statusLabel = document.getElementById('status-label');
        if (statusToggle) {
            statusToggle.addEventListener('change', function() {
                statusLabel.textContent = this.checked ? 'Active' : 'Inactive';
            });
        }
        
        // Script to add/remove education fields
        document.getElementById('add_education')?.addEventListener('click', function() {
            // ... (add education logic from create form)
        });
    </script>
</x-app-layout>