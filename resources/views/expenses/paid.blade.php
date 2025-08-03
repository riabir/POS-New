<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl">Paid Expenses History</h2>
    </x-slot>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            {{-- NEW: Download Report Form --}}
            <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-sm mb-6">
                <h3 class="font-semibold mb-3">Download Report</h3>
                <form action="{{ route('expenses.paid.download') }}" method="GET" class="grid grid-cols-1 sm:grid-cols-3 gap-4 items-end">
                    <div>
                        <label for="date_from" class="block text-sm">From</label>
                        <input type="date" name="date_from" id="date_from" class="mt-1 w-full rounded-md" required>
                    </div>
                     <div>
                        <label for="date_to" class="block text-sm">To</label>
                        <input type="date" name="date_to" id="date_to" class="mt-1 w-full rounded-md" required>
                    </div>
                    <div>
                        <button type="submit" class="btn btn-primary w-full">Download PDF</button>
                    </div>
                </form>
            </div>
            
             <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="overflow-x-auto">
                        {{-- ... your existing paid expenses table ... --}}
                    </div>
                    <div class="mt-4">{{ $paidExpenses->links() }}</div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>