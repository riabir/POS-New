@extends('layouts.clean')

@section('content')
    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            {{-- You can add your expense details display here if needed --}}
            {{-- <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-sm"> ... details ... </div> --}}

            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mt-6">
                <div class="p-6">
                     <h3 class="text-lg font-semibold">Process Expense Payment</h3>
                     <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">
                        You are about to pay an expense for <strong>{{ $expense->employee->full_name }}</strong> totaling <strong>৳{{ number_format($expense->total, 2) }}</strong>.
                     </p>
                     <form method="POST" action="{{ route('expenses.pay.process', $expense) }}" class="mt-4 space-y-4">
                        @csrf
                         <div>
                            <label for="payment_remarks" class="block font-medium text-sm text-gray-700 dark:text-gray-300">Payment Remarks / Transaction ID</label>
                            <textarea name="payment_remarks" id="payment_remarks" rows="3" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600"></textarea>
                         </div>
                         <div class="flex items-center gap-4 pt-4 border-t border-gray-200 dark:border-gray-700">
                            <button type="submit" class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-500 active:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150" onclick="return confirm('Confirm payment of ৳{{ number_format($expense->total, 2) }}?')">
                                Mark as Paid
                            </button>
                            <a href="{{ url()->previous(route('expenses.index', ['status' => 'approved'])) }}" class="inline-flex items-center px-4 py-2 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-500 rounded-md font-semibold text-xs text-gray-700 dark:text-gray-300 uppercase tracking-widest shadow-sm hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 disabled:opacity-25 transition ease-in-out duration-150">Cancel</a>
                         </div>
                     </form>
                </div>
            </div>
        </div>
    </div>
@endsection