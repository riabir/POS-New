<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Add New Employee') }}
        </h2>
    </x-slot>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 md:p-12 text-gray-900 dark:text-gray-100">
                    <form id="employeeForm" method="post" action="{{ route('employees.store') }}" enctype="multipart/form-data">
                        @csrf
                        <!-- PROFILE SECTION -->
                        <h3 class="text-lg font-bold border-b border-gray-200 dark:border-gray-700 pb-2 mb-6">Profile Uploads</h3>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                            <div>
                                <label for="photo" class="block text-sm font-medium">Photo</label>
                                <input type="file" id="photo" name="photo"
                                    class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                                @error('photo') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label for="nid_attachment" class="block text-sm font-medium">NID Photo/PDF</label>
                                <input type="file" id="nid_attachment" name="nid_attachment"
                                    class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                                @error('nid_attachment') <span class="text-red-500 text-sm">{{ $message }}</span>
                                @enderror
                            </div>
                            <div>
                                <label for="cv_pdf" class="block text-sm font-medium">CV PDF</label>
                                <input type="file" id="cv_pdf" name="cv_pdf"
                                    class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                                @error('cv_pdf') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                          </div>                        
                        </div>
                        <!-- MAIN DETAILS SECTION -->
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-x-6 gap-y-4">
                            <!-- Column 1 Fields -->
                            <div>
                                <label for="first_name">First Name <span class="text-red-500">*</span></label>
                                <input type="text" id="first_name" name="first_name" value="{{ old('first_name') }}" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                                @error('first_name') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label for="last_name">Last Name <span class="text-red-500">*</span></label>
                                <input type="text" id="last_name" name="last_name" value="{{ old('last_name') }}" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                                @error('last_name') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label for="email">Email <span class="text-red-500">*</span></label>
                                <input type="email" id="email" name="email" value="{{ old('email') }}" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                                @error('email') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            </div>
                             <div>
                                <label for="personal_phone_number">Phone <span class="text-red-500">*</span></label>
                                <input type="text" id="personal_phone_number" name="personal_phone_number" value="{{ old('personal_phone_number') }}" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                                @error('personal_phone_number') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label for="emp_id">Employee ID</label>
                                <input type="text" id="emp_id" name="emp_id" readonly placeholder="Auto-Generated" class="mt-1 block w-full bg-gray-100 dark:bg-gray-700 border-gray-300 rounded-md shadow-sm cursor-not-allowed">
                            </div>
                            <!-- UPDATED: Reporting Manager Dropdown -->
                            <div>
                                <label for="reporting_manager">Reporting Manager</label>
                                <select id="reporting_manager" name="reporting_manager" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                                    <option value="">Select Manager</option>
                                    @foreach($activeEmployees as $employee)
                                        <option value="{{ $employee->id }}" {{ old('reporting_manager') == $employee->id ? 'selected' : '' }}>
                                            {{ $employee->full_name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('reporting_manager') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label for="designation">Designation</label>
                                <input type="text" id="designation" name="designation" value="{{ old('designation') }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                            </div>
                            <div>
                                <label for="department">Department</label>
                                <input type="text" id="department" name="department" value="{{ old('department') }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                            </div>
                             <div>
                                <label for="branches">Branch</label>
                                <input type="text" id="branches" name="branches" value="{{ old('branches') }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                            </div>
                            <div>
                                <label for="bank_name">Bank Name</label>
                                <input type="text" id="bank_name" name="bank_name" value="{{ old('bank_name') }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                            </div>
                            <div>
                                <label for="bank_branch">Bank Branch</label>
                                <input type="text" id="bank_branch" name="bank_branch" value="{{ old('bank_branch') }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                            </div>
                            <div>
                                <label for="bank_account_number">Bank Account Number</label>
                                <input type="text" id="bank_account_number" name="bank_account_number" value="{{ old('bank_account_number') }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                            </div>
                            <div>
                                <label for="nid_number">NID Number</label>
                                <input type="text" id="nid_number" name="nid_number" value="{{ old('nid_number') }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                                @error('nid_number') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            </div>
                             <div>
                                <label for="passport_number">Passport Number</label>
                                <input type="text" id="passport_number" name="passport_number" value="{{ old('passport_number') }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                                @error('passport_number') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label for="tin_number">TIN Number</label>
                                <input type="text" id="tin_number" name="tin_number" value="{{ old('tin_number') }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                                @error('tin_number') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            </div>
                             <div>
                                <label for="fathers_name">Father's Name</label>
                                <input type="text" id="fathers_name" name="fathers_name" value="{{ old('fathers_name') }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                            </div>
                            <div>
                                <label for="mothers_name">Mother's Name</label>
                                <input type="text" id="mothers_name" name="mothers_name" value="{{ old('mothers_name') }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                            </div>
                            <div>
                                <label for="emergency_contact">Emergency Contact</label>
                                <input type="text" id="emergency_contact" name="emergency_contact" value="{{ old('emergency_contact') }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                            </div>
                            <!-- NEW: Reference Fields -->
                            <div>
                                <label for="reference_name">Reference Name</label>
                                <input type="text" id="reference_name" name="reference_name" value="{{ old('reference_name') }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                                @error('reference_name') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label for="reference_phone_number">Reference Phone</label>
                                <input type="text" id="reference_phone_number" name="reference_phone_number" value="{{ old('reference_phone_number') }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                                @error('reference_phone_number') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label for="date_of_birth">Date of Birth</label>
                                <input type="date" id="date_of_birth" name="date_of_birth" value="{{ old('date_of_birth') }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                            </div>
                            <div>
                                <label for="date_of_join">Date of Join</label>
                                <input type="date" id="date_of_join" name="date_of_join" value="{{ old('date_of_join') }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                            </div>
                            <div>
                                <label for="date_of_probation">Date Of Probation</label>
                                <input type="date" id="date_of_probation" name="date_of_probation" value="{{ old('date_of_probation') }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                            </div>
                            <!-- Status Toggle -->
                            <div class="md:col-span-3">
                                <label for="status" class="mt-4 block mb-2">Employee Status</label>
                                <label for="status-toggle" class="inline-flex items-center cursor-pointer">
                                    <input type="hidden" name="status" value="0">
                                    <input id="status-toggle" type="checkbox" name="status" value="1" class="sr-only peer" {{ old('status', 1) == 1 ? 'checked' : '' }}>
                                    <div class="relative w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 dark:peer-focus:ring-blue-800 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-green-600"></div>
                                    <span class="ms-3 text-sm font-medium text-gray-900 dark:text-gray-300">Active</span>
                                </label>
                            </div>
                        </div>
                        <!-- OTHER INFO SECTION -->
                        <h3 class="text-lg font-bold border-b border-gray-200 dark:border-gray-700 pb-2 mb-6 mt-8">Other Information</h3>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div>
                                <label for="marital_status">Marital Status</label>
                                <select id="marital_status" name="marital_status" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                                    <option value="">Select Status</option>
                                    <option value="Unmarried" @selected(old('marital_status') == 'Unmarried')>Unmarried</option>
                                    <option value="Married" @selected(old('marital_status') == 'Married')>Married</option>
                                    <option value="Divorced" @selected(old('marital_status') == 'Divorced')>Divorced</option>
                                </select>
                            </div>
                            <div>
                                <label for="blood_group">Blood Group</label>
                                <input type="text" id="blood_group" name="blood_group" value="{{ old('blood_group') }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                            </div>
                            <div>
                                <label for="religion">Religion</label>
                                <input type="text" id="religion" name="religion" value="{{ old('religion') }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                            </div>
                        </div>
                        <!-- ADDRESS SECTION -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
                            <div>
                                <label for="present_address">Present Address</label>
                                <textarea id="present_address" name="present_address" rows="3" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">{{ old('present_address') }}</textarea>
                            </div>
                            <div>
                                <label for="permanent_address">Permanent Address</label>
                                <textarea id="permanent_address" name="permanent_address" rows="3" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">{{ old('permanent_address') }}</textarea>
                            </div>
                        </div>
                        <!-- EDUCATION SECTION -->
                        <h3 class="text-lg font-bold border-b border-gray-200 dark:border-gray-700 pb-2 mb-6 mt-8">Education Details</h3>
                        <div id="education_fields" class="space-y-4">
                            <div class="grid grid-cols-1 md:grid-cols-[1fr,1fr,auto,auto,auto] gap-4 items-end">
                                <div><label>Degree</label><input type="text" name="education_details[0][degree]" placeholder="e.g., B.Sc in CSE" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm"></div>
                                <div><label>Institute</label><input type="text" name="education_details[0][institute]" placeholder="e.g., University of Dhaka" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm"></div>
                                <div><label>Year</label><input type="text" name="education_details[0][year]" placeholder="e.g., 2022" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm"></div>
                                <div><label>Result</label><input type="text" name="education_details[0][result]" placeholder="e.g., 3.80" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm"></div>
                                <div></div> <!-- Spacer for alignment -->
                            </div>
                        </div>
                        @error('education_details.*') <span class="text-red-500 text-sm">Please check the education fields for errors.</span> @enderror
                        <button type="button" id="add_education" class="mt-4 px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">Add More Education</button>
                        
                        <div class="mt-4">
                            <label for="certificate_attachment">Certificate Attachments (PDF, JPG)</label>
                            <input type="file" id="certificate_attachment" name="certificate_attachment" class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                             @error('certificate_attachment') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>
                        <div class="mt-4">
                            <label for="certificate_attachment2">Certificate Attachments 2 (PDF, JPG)</label>
                            <input type="file" id="certificate_attachment2" name="certificate_attachment2" class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                             @error('certificate_attachment2') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>
                        <!-- Form Actions -->
                        <div class="mt-4">
                            <button type="submit" class="btn btn-primary">Submit</button>
                            <button type="button" class="btn btn-secondary"
                                onclick="clearForm()">Clear</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <script>
        function clearForm() {
            document.getElementById("employeeForm").reset();
        }
        document.getElementById('add_education').addEventListener('click', function () {
            const educationFields = document.getElementById('education_fields');
            const newIndex = document.querySelectorAll('#education_fields > div').length;
            const newFields = document.createElement('div');
            newFields.className = 'grid grid-cols-1 md:grid-cols-[1fr,1fr,auto,auto,auto] gap-4 items-end mt-4';
            newFields.innerHTML = `
                <div><input type="text" name="education_details[${newIndex}][degree]" placeholder="e.g., M.Sc in CSE" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm"></div>
                <div><input type="text" name="education_details[${newIndex}][institute]" placeholder="e.g., Stanford University" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm"></div>
                <div><input type="text" name="education_details[${newIndex}][year]" placeholder="e.g., 2024" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm"></div>
                <div><input type="text" name="education_details[${newIndex}][result]" placeholder="e.g., 4.00" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm"></div>
                <button type="button" class="bg-red-500 text-white px-3 py-2 rounded-md" onclick="this.parentElement.remove()">Remove</button>
            `;
            educationFields.appendChild(newFields);
        });
    </script>
</x-app-layout>