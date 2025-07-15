<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard-Add New Customer') }}
        </h2>
    </x-slot>
    </form>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <div class="container">
                        @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                        @endif

                        <form id="Form" method="post" action="{{route('customers.store')}}">
                            @csrf

                            <div class="mb-3">
                                <label for="customer_name" class="form-label">Customer Name</label>
                                <input type="text" class="form-control" id="customer_name" name="customer_name"
                                    placeholder="Enter Company Name" required>
                            </div>


                            <div class="mb-3">
                                <label for="phone" class="form-label">Phone Number</label>
                                <input type="text" class="form-control" id="phone" name="phone"
                                    placeholder="Enter Unique Phone Number" required maxlength="11">
                            </div>

                            <div class="mb-3">
                                <label for="email" class="form-label">Email Address</label>
                                <input type="text" class="form-control" id="email" name="email"
                                    placeholder="Enter Unique Email Address" required>
                            </div>


                            <div class="mb-3">
                                <label for="address" class="form-label">Address</label>
                                <input type="text" class="form-control" id="addresss" name="address"
                                    placeholder="House#1064, Road#01, 3rd Floor Mirpur Dhaka" required>
                            </div>

                            <div class="mb-3">
                                <label for="concern" class="form-label">Concern Person</label>
                                <input type="text" class="form-control" id="concern" name="concern"
                                    placeholder="Enter Concern Person Name" required>
                            </div>

                            <!-- Expense Type -->
                            <div class="mb-3">
                                <label for="designation" class="form-label">Designation</label>
                                <select type="text" class="form-select" id="designation" name="designation" required>
                                    <option value="">Select</option>
                                    <option value="CEO">CEO </option>
                                    <option value="Manager-IT">Manager-IT</option>
                                    <option value="Senior Executive-IT">Senior Executive-IT </option>
                                    <option value="Executive-IT">Executive-IT</option>
                                    <option value="Senior Executive-Proc">Senior Executive-IT </option>
                                    <option value="Senior Executive-Proc">Senior Executive-Proc.</option>
                                    <option value="Executive-Proc">Executive-Proc.</option>
                                    <option value="Admin">Admin</option>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label for="opening_balance" class="form-label">Opening Balance</label>
                                <input type="number" class="form-control" id="opening_balance" name="opening_balance"
                                    step="0.01" value="0.00">
                            </div>

                            <div class="mb-3">
                                <label for="notes" class="form-label">Notes</label>
                                <textarea class="form-control" id="notes" name="notes" rows="3"></textarea>
                            </div>

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
                document.getElementById("Form").reset();
            }
        </script>

</x-app-layout>