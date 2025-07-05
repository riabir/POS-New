<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard-Update Customer Info') }}
        </h2>
    </x-slot>
    </form>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <div class="container">

                        <form method="post" action="{{route('customers.update',$customer->id)}}">
                            @csrf
                            @method('put')

                            <div class="mb-3">
                                <label for="name" class="form-label">Customer Name</label>
                                <input type="text" class="form-control" id="name" name="name" value="{{$customer->name}}"><br>
                            </div>
                            <div class="mb-3">
                                <label for="phone" class="form-label">Phone Number</label>
                                <input type="text" class="form-control" id="phone" name="phone"
                                    maxlength="11" value="{{$customer->phone}}">
                            </div>

                            <div class="mb-3">
                                <label for="email" class="form-label">Email Address</label>
                                <input type="text" class="form-control" id="email" name="email"
                                    value="{{$customer->email}}">
                            </div>


                            <div class="mb-3">
                                <label for="address" class="form-label">Address</label>
                                <input type="text" class="form-control" id="addresss" name="address"
                                    value="{{$customer->address}}">
                            </div>

                            <div class="mb-3">
                                <label for="concern" class="form-label">Concern Person</label>
                                <input type="text" class="form-control" id="concern" name="concern"
                                    value="{{$customer->concern}}">
                            </div>

                            <!-- Expense Type -->
                            <div class="mb-3">
                                <label for="designation" class="form-label">Designation</label>
                                <select class="form-select" id="designation" name="designation" required>
                                    <option value="">Select</option>
                                    <option value="CEO" {{ $customer->designation == 'CEO' ? 'selected' : '' }}>CEO</option>
                                    <option value="Manager-IT" {{ $customer->designation == 'Manager-IT' ? 'selected' : '' }}>Manager-IT</option>
                                    <option value="Senior Executive-IT" {{ $customer->designation == 'Senior Executive-IT' ? 'selected' : '' }}>Senior Executive-IT</option>
                                    <option value="Executive-IT" {{ $customer->designation == 'Executive-IT' ? 'selected' : '' }}>Executive-IT</option>
                                    <option value="Senior Executive-Proc" {{ $customer->designation == 'Senior Executive-Proc' ? 'selected' : '' }}>Senior Executive-Proc</option>
                                    <option value="Executive-Proc" {{ $customer->designation == 'Executive-Proc' ? 'selected' : '' }}>Executive-Proc</option>
                                    <option value="Admin" {{ $customer->designation == 'Admin' ? 'selected' : '' }}>Admin</option>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label for="opening_balance" class="form-label">Opening Balance</label>
                                <input type="number" class="form-control" id="opening_balance" name="opening_balance"
                                    step="0.01" value="{{$customer->opening_balance}}">
                            </div>

                            <div class="mb-3">
                                <label for="notes" class="form-label">Notes</label>
                                <textarea class="form-control" id="notes" name="notes" rows="3">{{ old('notes', $customer->notes) }}</textarea>
                            </div>

                            <button type="submit" class="btn btn-primary">Submit</button>
                        </form>

                    </div>
                </div>
            </div>
        </div>
</x-app-layout>