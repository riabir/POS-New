<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard-Expense Update') }}
        </h2>
    </x-slot>
    </form>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <div class="container">


                        <form method="post" action="{{route('expenses.update', $expense->id)}}">
                            @csrf
                            @method('put')

                            <!-- Date -->
                            <div class="mb-3">
                                <label for="date" class="form-label">Date</label>
                                <input type="date" class="form-control" id="date" name="date"
                                    value="{{ \Carbon\Carbon::parse($expense->date)->format('Y-m-d') }}" required>
                            </div>



                            <!-- Expense By -->
                            <div class="mb-3">
                                <label for="expense_by" class="form-label">Expense By</label>
                                <select class="form-select" id="expense_by" name="expense_by" required>

                                    <option value="{{ $expense->expense_by }}">{{$expense->expense_by}}</option>
                                    <option value="Abir">Abir</option>
                                    <option value="Pear">Pear</option>
                                    <option value="Nibir">Nibir</option>
                                    <option value="Jim">Jim</option>
                                    <option value="Sakib">Sakib</option>
                                </select>
                            </div>

                            <!-- Expense Type -->
                            <div class="mb-3">
                                <label for="expense_type" class="form-label">Expense Type</label>
                                <select type="text" class="form-select" id="expense_type" name="expense_type" required>
                                    <option value="{{ $expense->expense_type }}">{{$expense->expense_type}}</option>
                                    <option value="delivery">Delivery </option>
                                    <option value="npsb charge">NPSB Charge</option>
                                    <!-- Add more if needed -->
                                </select>
                            </div>


                            <!-- Remarks -->
                            <div class="mb-3">
                                <label for="remarks" class="form-label">Remarks</label>
                                <textarea type="text" class="form-control" id="remarks" name="remarks" 
                                     rows="3">{{ $expense->remarks }}</textarea>
                            </div>

                            <!-- Amount -->
                            <div class="mb-3">
                                <label for="amount" class="form-label">Amount</label>
                                <input type="number" class="form-control" id="amount" name="amount" step="0.01" value="{{$expense->amount}}"
                                    required>
                            </div>

                            <input type="submit" class="p-3 bg-red-800 text-white rounded-xl" value="Submit">
                        </form>

                    </div>
                </div>
            </div>
        </div>
</x-app-layout>