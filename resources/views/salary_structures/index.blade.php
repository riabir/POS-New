<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Salary Structures') }}
            </h2>
            <a href="{{ route('salary_structures.create') }}" class="inline-flex items-center px-4 py-2 bg-gray-800 dark:bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-white dark:text-gray-800 uppercase tracking-widest hover:bg-gray-700 dark:hover:bg-white focus:bg-gray-700 dark:focus:bg-white active:bg-gray-900 dark:active:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                Add New
            </a>
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
                    
                    <div class="overflow-x-auto">
                        <table class="styled-table">
                            <thead>
                                <tr>
                                    <th>Employee</th>
                                    <th>Basic Salary</th>
                                    <th>Allowances (H+M+C)</th>
                                    <th>Gross Salary</th>
                                    <th>Effective Date</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($salaryStructures as $structure)
                                    <tr class="clickable-row" data-href="{{ route('salary_structures.show', $structure->id) }}">
                                        <td>
                                            <div class="font-medium text-gray-900 dark:text-white">
                                                {{ $structure->employee->fullName ?? 'N/A' }}
                                            </div>
                                            <div class="text-sm text-gray-500 dark:text-gray-400">
                                                {{ $structure->employee->emp_id ?? 'N/A' }}
                                            </div>
                                        </td>
                                        <td>${{ number_format($structure->basic_salary, 2) }}</td>
                                        <td>${{ number_format($structure->house_rent_allowance + $structure->medical_allowance + $structure->conveyance_allowance, 2) }}</td>
                                        <td>
                                            <div class="font-semibold text-green-600 dark:text-green-400">
                                                ${{ number_format($structure->total_gross_salary, 2) }}
                                            </div>
                                        </td>
                                        <td>{{ $structure->effective_date->format('d M, Y') }}</td>
                                        <td>
                                            <div class="actions-container action-buttons">
                                                <a href="{{ route('salary_structures.edit', $structure->id) }}" class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-200">Edit</a>
                                                 @if (auth()->user()->role === 'admin')
                                                <form method="POST" action="{{ route('salary_structures.destroy', $structure->id) }}" onsubmit="return confirm('Are you sure?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-200">
                                                        Delete
                                                    </button>
                                                </form>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center py-12 text-gray-500">
                                            No salary structures found. <a href="{{ route('salary_structures.create') }}" class="text-indigo-600 hover:underline">Create the first one!</a>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-6">
                        {{ $salaryStructures->links() }}
                    </div>

                </div>
            </div>
        </div>
    </div>
    
    {{-- You can move these styles to your main app.css to avoid repetition --}}
    <style>
        .styled-table { width: 100%; border-collapse: collapse; } .styled-table thead tr { background-color: #f8f9fa; color: #333; text-align: left; } .styled-table th, .styled-table td { padding: 12px 15px; border: 1px solid #ddd; } .styled-table tbody tr:nth-of-type(even) { background-color: #f3f3f3; } .dark .styled-table thead tr { background-color: #374151; color: #f3f4f6; } .dark .styled-table th, .dark .styled-table td { border-color: #4b5563; } .dark .styled-table tbody tr:nth-of-type(even) { background-color: #4b5563; } .dark .styled-table, .dark .styled-table tbody tr:nth-of-type(odd) { background-color: #374151; } .dark .styled-table td { color: #f3f4f6; } .actions-container { display: flex; gap: 0.5rem; align-items: center; } .clickable-row { cursor: pointer; transition: background-color 0.2s ease-in-out; } .clickable-row:hover { background-color: #e9ecef; } .dark .clickable-row:hover { background-color: #4a5568; }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            document.querySelectorAll('tr[data-href]').forEach(row => {
                row.addEventListener('click', function (event) {
                    if (!event.target.closest('.action-buttons')) {
                        window.location.href = this.dataset.href;
                    }
                });
            });
            document.querySelectorAll('.action-buttons a, .action-buttons button').forEach(action => {
                action.addEventListener('click', event => event.stopPropagation());
            });
        });
    </script>
</x-app-layout>