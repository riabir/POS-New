<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard-Employee') }}
        </h2>
    </x-slot>
    </form>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <div class="container">

                        <a href="{{ route('employees.create') }}" class="btn btn-primary">Add New Employee</a>
                        <br><br><br>

                        <h1>Employee List</h1>
                        <br>
                        @if(session('success'))
                            <div class="alert alert-success">
                                {{ session('success') }}
                            </div>
                        @endif

                        <table class="table text-center">
                            <thead>
                                <tr>
                                    <th>Id</th>
                                    <th>First Name </th>
                                    <th>Last Name </th>
                                    <th>Email</th>
                                    <th>Cell</th>
                                    <th>Address </th>
                                    <th>Action</th>
                                </tr>
                            </thead>

                            <tbody>
                                @foreach($employees as $employee)
                                    <tr>
                                        <td>{{ $employee->emp_id }} </td>
                                        <td>{{ $employee->first_name }} </td>
                                        <td>{{ $employee->last_name }}</td>
                                        <td>{{ $employee->email }}</td>
                                        <td>{{ $employee->phone }}</td>
                                        <td>{{ $employee->address }}</td>                                   
                                        
                                        <td>
                                            <a href="{{ route('employees.edit', $employee->id) }}"
                                                class="btn btn-sm btn-warning me-2">Edit</a>

                                            <form method="POST"
                                                action="{{ route('employees.destroy', $employee->id) }}"
                                                style="display:inline;">
                                                @csrf
                                                @method('DELETE')
                                                <button onclick="return confirm('Are you sure?')" type="submit"
                                                    class="btn btn-sm btn-danger">
                                                    Delete
                                                </button>
                                            </form>
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