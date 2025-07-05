<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard-Customers') }}
        </h2>
    </x-slot>
    </form>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <div class="container">

                        <a href="{{ route('customers.create') }}" class="btn btn-primary">Add New Customer</a>
                        <br><br><br>

                        <h1>Customer List</h1>
                        <br>
                        @if(session('success'))
                            <div class="alert alert-success">
                                {{ session('success') }}
                            </div>
                        @endif

                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Name </th>
                                    <th>Phone</th>
                                    <th>Email</th>
                                    <th>Address </th>
                                    <th>Concern</th>
                                    <th>Desig.</th>
                                    <th>OP_B</th>
                                    <th>B_T</th>
                                    <th>Note</th>
                                    <!-- <th>Status</th> -->
                                    <th>Cr. By.</th>
                                    <th>Action</th>
                                </tr>
                            </thead>

                            <tbody>
                                @foreach($customers as $customer)
                                    <tr>
                                        <td>{{ $customer->name }} </td>
                                        <td>{{ $customer->phone }}</td>
                                        <td>{{ $customer->email }}</td>
                                        <td>{{ $customer->address }}</td>
                                        <td>{{ $customer->concern }}</td>
                                        <td>{{ $customer->designation }}</td>
                                        <td>{{ $customer->opening_balance }}</td>
                                        <td>{{ $customer->balance_type }}</td>
                                        <td>{{ $customer->notes }}</td>
                                        <!-- <td>{{ $customer->is_active }}</td> -->
                                        <td>{{$customer->created_by }}</td>
                                   
                                       <td>
                                            <div class="btn-group" role="group">
                                                <a href="{{ route('customers.edit', $customer->id) }}"
                                                    class="btn btn-sm btn-warning">Edit</a>

                                                <form method="POST"
                                                    action="{{ route('customers.destroy', $customer->id) }}">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button onclick="return confirm('Are you sure?')" type="submit"
                                                        class="btn btn-sm btn-danger">
                                                        Delete
                                                    </button>
                                                </form>
                                            </div>
                                        </td>

                                    </tr>
                                @endforeach
                            </tbody>
                        </table>

                    </div>
                </div>
            </div>
        </div>
    </div>

</x-app-layout>