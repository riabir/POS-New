<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Customers') }}
            </h2>
            <a href="{{ route('customers.create') }}" class="btn btn-primary">Add New Customer</a>
        </div>
    </x-slot>

    <style>
        .table {
            width: 100%;
            border-collapse: collapse;
        }

        .table th,
        .table td {
            padding: 12px 15px;
            border: 1px solid #ddd;
            text-align: left;
        }

        .dark .table th,
        .dark .table td {
            border-color: #4b5563;
        }

        .btn-group {
            display: flex;
            gap: 0.5rem;
        }

        .clickable-row {
            cursor: pointer;
            transition: background-color 0.2s ease-in-out;
        }

        .clickable-row:hover {
            background-color: #f3f4f6;
        }

        .dark .clickable-row:hover {
            background-color: #374151;
        }

        .filter-form {
            background-color: #f9f9f9;
            padding: 1.5rem;
            border-radius: 0.5rem;
            margin-bottom: 1.5rem;
            border: 1px solid #e2e8f0;
        }

        .dark .filter-form {
            background-color: #4a5568;
            border-color: #2d3748;
        }

        .filter-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1rem;
        }

        .filter-buttons {
            display: flex;
            gap: 0.5rem;
            align-items: flex-end;
        }
    </style>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">

                    @if(session('success'))
                    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                        <span class="block sm:inline">{{ session('success') }}</span>
                    </div>
                    @endif

                    <!-- Filter Form -->
                    <div class="filter-form">
                        <h3 class="text-lg font-semibold mb-3">Filter Customers</h3>
                        <form action="{{ route('customers.index') }}" method="GET">
                            <div class="filter-grid">
                                <div>
                                    <label for="id" class="block text-sm font-medium">Customer ID</label>
                                    <input type="number" name="id" id="id" value="{{ request('id') }}" class="mt-1 block w-full rounded-md shadow-sm">
                                </div>
                                <div>
                                    <label for="customer_name" class="block text-sm font-medium">Customer Name</label>
                                    <input type="text" name="customer_name" id="customer_name" value="{{ request('customer_name') }}" class="mt-1 block w-full rounded-md shadow-sm">
                                </div>
                                <div>
                                    <label for="phone" class="block text-sm font-medium">Phone Number</label>
                                    <input type="text" name="phone" id="phone" value="{{ request('phone') }}" class="mt-1 block w-full rounded-md shadow-sm">
                                </div>
                                <div class="filter-buttons">
                                    <button type="submit" class="btn btn-primary">Filter</button>
                                    <a href="{{ route('customers.index') }}" class="btn btn-secondary">Clear</a>
                                </div>
                            </div>
                        </form>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="table">
                            <thead class="bg-gray-100 dark:bg-gray-700">
                                <tr>
                                    <th>ID</th>
                                    <th>Customer Name</th>
                                    <th>Phone</th>
                                    <th>Email</th>
                                    <th>Concern Person</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($customers as $customer)
                                <tr class="clickable-row" data-href="{{ route('customers.show', $customer->id) }}"
                                onclick="window.location=this.dataset.href;">    
                                    <td>{{ $customer->id }}</td>
                                    <td class="font-medium">{{ $customer->customer_name }}</td>
                                    <td>{{ $customer->phone }}</td>
                                    <td>{{ $customer->email }}</td>
                                    <td>{{ $customer->concern }}</td>
                                    <td>
                                        @if (auth()->user()->role === 'admin')
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('customers.edit', $customer->id) }}" onclick="event.stopPropagation();" class="btn btn-sm btn-warning">Edit</a>
                                            <form method="POST" action="{{ route('customers.destroy', $customer->id) }}" onsubmit="return confirm('Are you sure?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" onclick="event.stopPropagation();" class="btn btn-sm btn-danger">Delete</button>
                                            </form>
                                        </div>
                                        @else
                                        {{-- For non-admin users, you can display a message or leave it empty --}}
                                        <span class="text-xs text-gray-500">No actions available</span>
                                        @endif
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="text-center py-4">No customers found.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-4">
                        {{ $customers->links() }}
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>