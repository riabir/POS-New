<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Vendor Refund') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if(session('success'))
                <div class="mb-6 bg-green-100 dark:bg-green-800/20 border-l-4 border-green-500 text-green-700 dark:text-green-300 p-4 rounded-md shadow-sm" role="alert">
                    <p class="font-bold">Success</p>
                    <p>{{ session('success') }}</p>
                </div>
            @endif

            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <!-- Search and Filter Form -->
                    <form method="GET" action="{{ route('vendor_refunds.index') }}" class="mb-6">
                        <div class="flex flex-col sm:flex-row items-center space-y-2 sm:space-y-0 sm:space-x-3">
                            <x-text-input type="text" name="search_vendor" class="w-full sm:w-1/3" placeholder="Search by name, company, phone..." value="{{ request('search_vendor') }}" />
                            <x-primary-button>Search</x-primary-button>
                            <a href="{{ route('vendor_refunds.index') }}" class="text-sm text-gray-600 dark:text-gray-400 hover:underline">Clear</a>
                        </div>
                    </form>

                    <!-- Vendors Table -->
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead class="bg-gray-50 dark:bg-gray-700">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Vendor</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Payable Balance (We Owe)</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Available Advance</th>
                                    <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Action</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                @forelse($vendors as $vendor)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="font-medium text-gray-900 dark:text-white">{{ $vendor->vendor_name }}</div>
                                        <div class="text-sm text-gray-500 dark:text-gray-400">{{ $vendor->phone }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-red-600 dark:text-red-400 font-semibold">{{ number_format($vendor->payable_balance, 2) }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-green-600 dark:text-green-400 font-semibold">{{ number_format($vendor->available_advance, 2) }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                        <a href="{{ route('vendor_refunds.create', $vendor) }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:border-indigo-900 focus:ring ring-indigo-300 disabled:opacity-25 transition ease-in-out duration-150">
                                            Process Refund
                                        </a>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="text-center py-12 text-gray-500 dark:text-gray-400">
                                        <p>No vendors found matching your search criteria.</p>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                     <div class="mt-4">
                        {{ $vendors->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>