<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Vendors') }}
        </h2>
    </x-slot>

    {{-- Add some basic styling for the form and table for better presentation --}}
    <style>
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
    </style>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">

                    <a href="{{ route('vendors.create') }}" class="btn btn-primary mb-4 inline-block">Add New Vendor</a>

                    @if(session('success'))
                    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                        <span class="block sm:inline">{{ session('success') }}</span>
                    </div>
                    @endif

                    {{-- ================================== --}}
                    {{-- NEW: FILTER FORM START --}}
                    {{-- ================================== --}}
                    <div class="filter-form">
                        <h3 class="text-lg font-semibold mb-3">Filter Vendors</h3>
                        <form action="{{ route('vendors.index') }}" method="GET">
                            <div class="filter-grid">
                                {{-- Filter by Vendor ID --}}
                                <div>
                                    <label for="id" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Vendor ID</label>
                                    <input type="number" name="id" id="id" value="{{ request('id') }}" class="mt-1 block w-full rounded-md shadow-sm" placeholder="e.g., 5">
                                </div>
                                {{-- Filter by Vendor Name --}}
                                <div>
                                    <label for="vendor_name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Vendor Name</label>
                                    <input type="text" name="vendor_name" id="vendor_name" value="{{ request('vendor_name') }}" class="mt-1 block w-full rounded-md shadow-sm" placeholder="e.g., Acme Corp">
                                </div>
                                {{-- Filter by Phone --}}
                                <div>
                                    <label for="phone" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Phone Number</label>
                                    <input type="text" name="phone" id="phone" value="{{ request('phone') }}" class="mt-1 block w-full rounded-md shadow-sm" placeholder="e.g., 017...">
                                </div>
                                {{-- Filter and Clear Buttons --}}
                                <div class="filter-buttons">
                                    <button type="submit" class="btn btn-primary">Filter</button>
                                    <a href="{{ route('vendors.index') }}" class="btn btn-secondary">Clear</a>
                                </div>
                            </div>
                        </form>
                    </div>
                    {{-- ================================== --}}
                    {{-- FILTER FORM END --}}
                    {{-- ================================== --}}


                    <div class="overflow-x-auto">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th scope="col" style="width: 5%;">#</th>
                                    <th>ID</th>
                                    <th>Vendor Name</th>
                                    <th>Phone</th>
                                    <th>Email</th>
                                    <th>Address</th>
                                    <th>Concern Person</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($vendors as $vendor)
                                {{-- MODIFIED: Added a class and an onclick event to the <tr> --}}
                                <tr class="clickable-row"data-href="{{ route('vendors.show', $vendor->id) }}"
                                    onclick="window.location=this.dataset.href;">
                                    <th scope="row">{{ $loop->iteration }}</th>
                                    <td>{{ $vendor->id }}</td>
                                    <td>{{ $vendor->vendor_name }}</td>
                                    <td>{{ $vendor->phone }}</td>
                                    <td>{{ $vendor->email }}</td>
                                    <td>{{ $vendor->address }}</td>
                                    {{-- Make sure the concern person column is correct --}}
                                    <td>{{ $vendor->concern_person }}</td>
                                    <td>
                                           @if (auth()->user()->role === 'admin')
                                        <div class="btn-group" role="group">
                                            {{-- MODIFIED: Stop the click from bubbling up from the buttons --}}
                                            <a href="{{ route('vendors.edit', $vendor->id) }}" onclick="event.stopPropagation();" class="btn btn-sm btn-warning">Edit</a>
                                            <form method="POST" action="{{ route('vendors.destroy', $vendor->id) }}" onsubmit="return confirm('Are you sure you want to delete this vendor?');">
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
                                {{-- ... --}}
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    {{-- Add pagination links --}}
                    <div class="mt-4">
                        {{ $vendors->links() }}
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>