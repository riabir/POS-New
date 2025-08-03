<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Financial Summary Report
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            {{-- Unified Filter Section --}}
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h3 class="text-lg font-medium mb-4">Generate Report</h3>
                    
                    <form action="{{ route('reports.summary') }}" method="GET">
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4 items-end">
                            
                            {{-- Date From --}}
                            <div>
                                <label for="date_from" class="block text-sm font-medium">From</label>
                                <input type="date" name="date_from" id="date_from" value="{{ request('date_from') }}" class="mt-1 block w-full rounded-md shadow-sm text-sm">
                            </div>

                            {{-- Date To --}}
                            <div>
                                <label for="date_to" class="block text-sm font-medium">To</label>
                                <input type="date" name="date_to" id="date_to" value="{{ request('date_to') }}" class="mt-1 block w-full rounded-md shadow-sm text-sm">
                            </div>
                            
                            {{-- Year --}}
                            <div>
                                <label for="year" class="block text-sm font-medium">Or Year</label>
                                <select name="year" id="year" class="mt-1 block w-full rounded-md shadow-sm text-sm">
                                    @forelse($availableYears as $year)
                                        <option value="{{ $year }}" @selected($year == request('year', date('Y')))>{{ $year }}</option>
                                    @empty
                                        <option value="{{ date('Y') }}">{{ date('Y') }}</option>
                                    @endforelse
                                </select>
                            </div>
                            
                            {{-- Month --}}
                            <div>
                                <label for="month" class="block text-sm font-medium">And Month</label>
                                <select name="month" id="month" class="mt-1 block w-full rounded-md shadow-sm text-sm">
                                    <option value="">(Whole Year)</option> {{-- Added option for yearly report --}}
                                    @for ($m = 1; $m <= 12; $m++)
                                        <option value="{{ $m }}" @selected($m == request('month', date('n')))>
                                            {{ date('F', mktime(0, 0, 0, $m, 1)) }}
                                        </option>
                                    @endfor
                                </select>
                            </div>

                            {{-- Buttons --}}
                            <div class="flex gap-2">
                                <button type="submit" class="w-full btn btn-primary text-sm">Generate</button>
                                <a href="{{ route('reports.summary') }}" class="w-full btn btn-secondary text-sm">Reset</a>
                            </div>
                        </div>
                        <p class="text-xs text-gray-500 mt-2">Note: Custom date range takes priority over Month/Year selection.</p>
                    </form>
                </div>
            </div>

            {{-- Report Display Card --}}
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <div class="border-b pb-4 mb-6 dark:border-gray-700">
                        <h3 class="text-xl font-semibold">
                           Summary For: <span class="text-blue-600 dark:text-blue-400">{{ $reportTitle }}</span>
                        </h3>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        
                        {{-- Total Sales Card --}}
                        <div class="flex items-start p-4 bg-gray-50 dark:bg-gray-700/50 rounded-lg space-x-4">
                            <div class="flex-shrink-0 h-12 w-12 flex items-center justify-center bg-blue-100 dark:bg-blue-900/50 rounded-full">
                                <svg class="h-6 w-6 text-blue-600 dark:text-blue-300" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/></svg>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Sales</dt>
                                <dd class="mt-1 text-2xl font-bold text-gray-900 dark:text-gray-100">৳{{ number_format($totalSales, 2) }}</dd>
                            </div>
                        </div>

                        {{-- Total Purchases Card --}}
                        <div class="flex items-start p-4 bg-gray-50 dark:bg-gray-700/50 rounded-lg space-x-4">
                            <div class="flex-shrink-0 h-12 w-12 flex items-center justify-center bg-orange-100 dark:bg-orange-900/50 rounded-full">
                                <svg class="h-6 w-6 text-orange-600 dark:text-orange-300" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" /></svg>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Purchases</dt>
                                <dd class="mt-1 text-2xl font-bold text-gray-900 dark:text-gray-100">৳{{ number_format($totalPurchases, 2) }}</dd>
                            </div>
                        </div>

                        {{-- Net Profit Card --}}
                        <div class="flex items-start p-4 bg-gray-50 dark:bg-gray-700/50 rounded-lg space-x-4">
                            <div class="flex-shrink-0 h-12 w-12 flex items-center justify-center rounded-full {{ $totalProfit >= 0 ? 'bg-green-100 dark:bg-green-900/50' : 'bg-red-100 dark:bg-red-900/50' }}">
                                <svg class="h-6 w-6 {{ $totalProfit >= 0 ? 'text-green-600 dark:text-green-300' : 'text-red-600 dark:text-red-300' }}" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v.01" /></svg>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Net Profit</dt>
                                <dd class="mt-1 text-2xl font-bold {{ $totalProfit >= 0 ? 'text-green-600' : 'text-red-500' }}">৳{{ number_format($totalProfit, 2) }}</dd>
                            </div>
                        </div>

                    </div>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>