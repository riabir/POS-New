<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard-Expense') }}
        </h2>
    </x-slot>

    </form>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <div class="container">

                        <!-- <a href="{{ route('employees.create') }}" class="btn btn-primary">Add New Expense</a>
                            <br><br><br> -->

                        <!-- pop up from -->

                        <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                            data-bs-target="#registerModal">
                            Expense
                        </button>

                        <div class="modal fade" id="registerModal" tabindex="-1" aria-labelledby="registerModalLabel"
                            aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="registerModalLabel">Expense</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                            aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <!-- Expense Form -->
                                        <form id="expenseForm" method="post" action="{{route('expenses.store')}}">
                                            @csrf

                                            <!-- Date -->
                                            <div class="mb-3">
                                                <label for="date" class="form-label">Date</label>
                                                <input type="date" class="form-control" id="date" name="date"
                                                    value="{{ date('Y-m-d') }}" required>

                                            </div>

                                            <!-- Expense By -->
                                            <div class="mb-3">
                                                <label for="expense_by" class="form-label">Expense By</label>
                                                <select type="text" class="form-select" id="expense_by"
                                                    name="expense_by" required>
                                                    <option value="">Select</option>
                                                    <option value="Abir">Abir</option>
                                                    <option value="Pear">Pear</option>
                                                    <option value="Nibir">Nibir</option>
                                                    <option value="Jim">Jim</option>
                                                    <option value="Sakib">Sakib</option>
                                                    <!-- Add more options as needed -->
                                                </select>
                                            </div>

                                            <!-- Expense Type -->
                                            <div class="mb-3">
                                                <label for="expense_type" class="form-label">Expense Type</label>
                                                <select type="text" class="form-select" id="expense_type"
                                                    name="expense_type" required>
                                                    <option value="">Select</option>
                                                    <option value="delivery">Delivery </option>
                                                    <option value="npsb charge">NPSB Charge</option>
                                                    <!-- Add more if needed -->
                                                </select>
                                            </div>


                                            <!-- Remarks -->
                                            <div class="mb-3">
                                                <label for="remarks" class="form-label">Remarks</label>
                                                <textarea type="text" class="form-control" id="remarks" name="remarks"
                                                    rows="3"></textarea>
                                            </div>

                                            <!-- Amount -->
                                            <div class="mb-3">
                                                <label for="amount" class="form-label">Amount</label>
                                                <input type="number" class="form-control" id="amount" name="amount"
                                                    step="0.01" placeholder="0.00" required>
                                            </div>

                                            <div class="modal-footer">
                                                <button type="submit" class="btn btn-primary">Save Expense</button>
                                                <button type="button" class="btn btn-secondary"
                                                    onclick="clearForm()">Clear</button>


                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <br><br><br>


                        <h1>Expense List</h1>
                        <br>
                        @if(session('success'))
                            <div class="alert alert-success">
                                {{ session('success') }}
                            </div>
                        @endif

                        <table class="table">
                            <thead>
                                <tr>

                                    <th>Date</th>
                                    <th>Expense By</th>
                                    <th>Expense Type</th>
                                    <th>Remarks</th>
                                    <th>Amount</th>
                                    <th>Action</th>
                                    <th></th>
                                </tr>
                            </thead>

                            <tbody>
                                @foreach($expenses as $expense)
                                    <tr>
                                        <td>{{ $expense->date }} </td>
                                        <td>{{ $expense->expense_by}}</td>
                                        <td>{{ $expense->expense_type }}</td>
                                        <td>{{ $expense->remarks }}</td>
                                        <td>{{ $expense->amount }}</td>

                                        <td>
                                            <a href="{{ route('expenses.edit', $expense->id) }}"
                                                class="btn btn-sm btn-warning me-2">Edit</a>

                                            <form method="POST"
                                                action="{{ route('expenses.destroy', $expense->id) }}"
                                                style="display:inline;">
                                                @csrf
                                                @method('DELETE')
                                                <button onclick="return confirm('Are you sure?')" type="submit"
                                                    class="btn btn-sm btn-danger">
                                                    Delete
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>

                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function clearForm() {
            document.getElementById("expenseForm").reset();
        }
    </script>
</x-app-layout>