<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Employee Management') }}
            </h2>
            
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-6" role="alert">
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif

            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    
                    <!-- Responsive table container -->
                    <div class="overflow-x-auto">
                        <table class="styled-table">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Designation</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($employees as $employee)
                                    <tr class="clickable-row" data-href="{{ route('employees.show', $employee->id) }}">
                                        <td>
                                            <div class="flex items-center">
                                                <div class="flex-shrink-0 h-10 w-10">
                                                    <img class="h-10 w-10 rounded-full object-cover" 
                                                         src="{{ $employee->photo ? asset('storage/' . $employee->photo) : 'https://ui-avatars.com/api/?name='.urlencode($employee->fullName).'&color=7F9CF5&background=EBF4FF' }}" 
                                                         alt="{{ $employee->fullName }}">
                                                </div>
                                                <div class="ml-4">
                                                    <div class="font-medium text-gray-900 dark:text-white">
                                                        {{ $employee->fullName }}
                                                    </div>
                                                    <div class="text-sm text-gray-500 dark:text-gray-400">
                                                        {{ $employee->email }}
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="text-gray-900 dark:text-white">{{ $employee->designation ?? 'N/A' }}</div>
                                            <div class="text-sm text-gray-500 dark:text-gray-400">{{ $employee->department ?? 'N/A' }}</div>
                                        </td>
                                        <td>
                                            @if($employee->status)
                                                <span class="status-badge status-paid">
                                                    Active
                                                </span>
                                            @else
                                                <span class="status-badge status-due">
                                                    Inactive
                                                </span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="actions-container action-buttons">
                                                <a href="{{ route('employees.edit', $employee->id) }}" class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-200">Edit</a>
                                                <form method="POST" action="{{ route('employees.destroy', $employee->id) }}" onsubmit="return confirm('Are you sure you want to delete this employee?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-200">
                                                        Delete
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center py-12 text-gray-500">
                                            No employees found. <a href="{{ route('employees.create') }}" class="text-indigo-600 hover:underline">Add the first one!</a>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination Links -->
                    <div class="mt-6">
                        {{ $employees->links() }}
                    </div>

                </div>
            </div>
        </div>
    </div>

    <style>
        .status-badge { display: inline-block; padding: .25em .6em; font-size: 75%; font-weight: 700; line-height: 1; text-align: center; white-space: nowrap; vertical-align: baseline; border-radius: .25rem; color: #fff; }
        .status-paid { background-color: #28a745; }
        .status-due { background-color: #dc3545; }
        .styled-table { width: 100%; border-collapse: collapse; }
        .styled-table thead tr { background-color: #f8f9fa; color: #333; text-align: left; }
        .styled-table th, .styled-table td { padding: 12px 15px; border: 1px solid #ddd; }
        .styled-table tbody tr:nth-of-type(even) { background-color: #f3f3f3; }
        .dark .styled-table thead tr { background-color: #374151; color: #f3f4f6; }
        .dark .styled-table th, .dark .styled-table td { border-color: #4b5563; }
        .dark .styled-table tbody tr:nth-of-type(even) { background-color: #4b5563; }
        .dark .styled-table, .dark .styled-table tbody tr:nth-of-type(odd) { background-color: #374151; }
        .dark .styled-table td { color: #f3f4f6; }
        .actions-container { display: flex; gap: 0.5rem; align-items: center; }
        .clickable-row { cursor: pointer; transition: background-color 0.2s ease-in-out; }
        .clickable-row:hover { background-color: #e9ecef; }
        .dark .clickable-row:hover { background-color: #4a5568; }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Find all table rows with the data-href attribute
            const rows = document.querySelectorAll('tr[data-href]');

            rows.forEach(row => {
                row.addEventListener('click', function (event) {
                    // Make sure the click is not on an action button or link inside the actions container
                    if (!event.target.closest('.action-buttons')) {
                        window.location.href = this.dataset.href;
                    }
                });
            });

            // Stop propagation on action buttons to prevent the row click
            const actions = document.querySelectorAll('.action-buttons a, .action-buttons button');
            actions.forEach(action => {
                action.addEventListener('click', function (event) {
                    event.stopPropagation();
                });
            });
        });
    </script>
</x-app-layout>