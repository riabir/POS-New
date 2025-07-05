<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard-Vendor') }}
        </h2>
    </x-slot>
    </form>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <div class="container">

                        <a href="{{ route('vendors.create') }}" class="btn btn-primary">Add New Vendor</a>
                        <br><br><br>

                        <h1>Vendor List</h1>
                        <br>
                        @if(session('success'))
                            <div class="alert alert-success">
                                {{ session('success') }}
                            </div>
                        @endif

                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Id</th>
                                    <th>Vendor Name </th>
                                    <th>Phone Number</th>
                                    <th>Email</th>
                                    <th>Address </th>
                                    <th>Concern Person </th>
                                    <th>Action</th>
                                </tr>
                            </thead>

                            <tbody>
                                @foreach($vendors as $vendor)
                                    <tr>
                                        <td>{{ $vendor->id }}</td>
                                        <td>{{ $vendor->vendor_name }} </td>
                                        <td>{{ $vendor->phone }}</td>
                                        <td>{{ $vendor->email }}</td>
                                        <td>{{ $vendor->address }}</td>
                                        <td>{{ $vendor->concern_person }}</td>

                                        <td>
                                            <div class="btn-group" role="group">
                                                <a href="{{ route('vendors.edit', $vendor->id) }}"
                                                    class="btn btn-sm btn-warning">Edit</a>

                                                <form method="POST"
                                                    action="{{ route('vendors.destroy', $vendor->id) }}">
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