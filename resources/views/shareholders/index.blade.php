<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Shareholder Management') }}
            </h2>
            <a href="{{ route('shareholders.create') }}" class="btn btn-primary">
                Add New Shareholder
            </a>
        </div>
    </x-slot>

    <style>
        .status-badge {
            display: inline-block;
            padding: .25em .6em;
            font-size: 75%;
            font-weight: 700;
            line-height: 1;
            text-align: center;
            white-space: nowrap;
            vertical-align: baseline;
            border-radius: .25rem;
            color: #fff;
        }

        .status-paid {
            background-color: #28a745;
        }

        .status-due {
            background-color: #dc3545;
        }

        .styled-table {
            width: 100%;
            border-collapse: collapse;
        }

        .styled-table thead tr {
            background-color: #f8f9fa;
        }

        .styled-table th {
            color: #333;
            text-align: left;
            font-weight: 600;
        }

        .styled-table th,
        .styled-table td {
            padding: 12px 15px;
            border: 1px solid #ddd;
        }

        .dark .styled-table thead tr {
            background-color: #374151;
        }

        .dark .styled-table th {
            color: #f3f4f6;
        }

        .dark .styled-table th,
        .dark .styled-table td {
            border-color: #4b5563;
        }

        .clickable-row {
            cursor: pointer;
        }

        .clickable-row:hover {
            background-color: #e9ecef;
        }

        .dark .clickable-row:hover {
            background-color: #4a5568;
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
                        {{ session('success') }}
                    </div>
                    @endif

                    <!-- ================================== -->
                    <!-- NEW: FILTER FORM START -->
                    <!-- ================================== -->
                    <div class="filter-form">
                        <h3 class="text-lg font-semibold mb-3">Filter Shareholders</h3>
                        <form action="{{ route('shareholders.index') }}" method="GET">
                            <div class="filter-grid">
                                <div>
                                    <label for="id" class="block text-sm font-medium">Shareholder ID</label>
                                    <input type="number" name="id" id="id" value="{{ request('id') }}" class="mt-1 block w-full rounded-md shadow-sm">
                                </div>
                                <div>
                                    <label for="name" class="block text-sm font-medium">Name</label>
                                    <input type="text" name="name" id="name" value="{{ request('name') }}" class="mt-1 block w-full rounded-md shadow-sm">
                                </div>
                                <div>
                                    <label for="phone" class="block text-sm font-medium">Phone</label>
                                    <input type="text" name="phone" id="phone" value="{{ request('phone') }}" class="mt-1 block w-full rounded-md shadow-sm">
                                </div>
                                <div class="filter-buttons">
                                    <button type="submit" class="btn btn-primary">Filter</button>
                                    <a href="{{ route('shareholders.index') }}" class="btn btn-secondary">Clear</a>
                                </div>
                            </div>
                        </form>
                    </div>
                    <!-- ================================== -->
                    <!-- FILTER FORM END -->
                    <!-- ================================== -->

                    <div class="overflow-x-auto">
                        <table class="styled-table">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Contact</th>
                                    <th>Join Date</th>
                                    <th>Current Balance</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($shareholders as $shareholder)
                                <tr class="clickable-row" data-href="{{ route('shareholders.show', $shareholder) }}" title="Click to view shareholder ledger">
                                    <td>
                                        <div class="font-medium text-gray-900 dark:text-white">{{ $shareholder->name }}</div>
                                        <div class="text-xs text-gray-500">ID: {{ $shareholder->id }}</div>
                                    </td>
                                    <td>
                                        <div class="text-sm text-gray-600 dark:text-gray-400">{{ $shareholder->email }}</div>
                                        <div class="text-sm text-gray-600 dark:text-gray-400">{{ $shareholder->phone }}</div>
                                    </td>
                                    <td>{{ $shareholder->join_date->format('d M, Y') }}</td>
                                    <td>
                                        <span class="font-semibold {{ $shareholder->current_balance < 0 ? 'text-red-500' : 'text-green-600' }}">
                                            ৳{{ number_format($shareholder->current_balance, 2) }}
                                        </span>
                                    </td>
                                    <td>
                                        @if($shareholder->is_active)
                                        <span class="status-badge status-paid">Active</span>
                                        @else
                                        <span class="status-badge status-due">Inactive</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="flex items-center space-x-4">
                                            <a href="{{ route('shareholders.edit', $shareholder) }}" onclick="event.stopPropagation();" class="text-indigo-600 dark:text-indigo-400 hover:underline">Edit</a>
                                            
                                            @if (auth()->user()->role === 'admin')
                                            <form method="POST" action="{{ route('shareholders.destroy', $shareholder) }}" onsubmit="return confirm('Are you sure?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" onclick="event.stopPropagation();" class="text-red-600 dark:text-red-400 hover:underline">Delete</button>
                                            </form>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="text-center py-12">No shareholders found matching your criteria.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-6">{{ $shareholders->links() }}</div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('.clickable-row').forEach(row => {
                row.addEventListener('click', function(e) {
                    if (e.target.closest('a, button, form')) {
                        return;
                    }
                    if (this.dataset.href) {
                        window.location.href = this.dataset.href;
                    }
                });
            });
        });
    </script>
    @endpush
</x-app-layout>