<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl">Review Expense Application</h2>
    </x-slot>
    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            {{-- ... Expense Details Display ... --}}
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mt-6">
                <div class="p-6">
                     <h3 class="text-lg font-semibold">Approval Action</h3>
                     <form method="POST" action="{{ route('expenses.approve.process', $expense) }}" class="mt-4 space-y-4">
                         @csrf
                         <div>
                             <label class="block font-medium text-sm">Action*</label>
                             <select name="status" class="mt-1 block w-full rounded-md" required>
                                 <option value="approved">Approve</option>
                                 <option value="rejected">Reject</option>
                             </select>
                         </div>
                         <div>
                            <label for="approver_remarks" class="block font-medium text-sm">Remarks</label>
                            <textarea name="approver_remarks" rows="3" class="mt-1 block w-full rounded-md"></textarea>
                         </div>
                         <div class="flex items-center gap-4">
                            <button type="submit" class="btn btn-primary">Submit Action</button>
                            <a href="{{ route('expenses.index') }}" class="btn">Cancel</a>
                         </div>
                     </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>