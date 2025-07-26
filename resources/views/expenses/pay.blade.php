<x-app-layout>
    <x-slot name="header">
         <h2 class="font-semibold text-xl">Process Expense Payment</h2>
    </x-slot>
    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            {{-- ... Expense Details Display ... --}}
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mt-6">
                <div class="p-6">
                     <h3 class="text-lg font-semibold">Payment Action</h3>
                     <form method="POST" action="{{ route('expenses.pay.process', $expense) }}" class="mt-4 space-y-4">
                        @csrf
                         <div>
                            <label for="payment_remarks" class="block font-medium text-sm">Payment Remarks / Transaction ID</label>
                            <textarea name="payment_remarks" rows="3" class="mt-1 block w-full rounded-md"></textarea>
                         </div>
                         <div class="flex items-center gap-4">
                            <button type="submit" class="btn btn-success" onclick="return confirm('Confirm payment of ৳{{ number_format($expense->total, 2) }}?')">
                                Mark as Paid
                            </button>
                            <a href="{{ route('expenses.index', ['status' => 'approved']) }}" class="btn">Cancel</a>
                         </div>
                     </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>