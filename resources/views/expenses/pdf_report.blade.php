<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Paid Expenses Report</title>
    <style>
        body { font-family: sans-serif; font-size: 10px; }
        .table { width: 100%; border-collapse: collapse; }
        .table th, .table td { border: 1px solid #ddd; padding: 6px; text-align: left; }
        .table th { background-color: #f2f2f2; }
        .text-right { text-align: right; }
        .header { text-align: center; margin-bottom: 20px; }
        .header h1 { margin: 0; }
        .header p { margin: 0; font-size: 12px; }
    </style>
</head>
<body>
    <div class="header">
        <h1>Paid Expenses Report</h1>
        <p>From: {{ $dateFrom->format('d M, Y') }} To: {{ $dateTo->format('d M, Y') }}</p>
    </div>

    <table class="table">
        <thead>
            <tr>
                <th>Paid Date</th>
                <th>Employee</th>
                <th>Expense Type</th>
                <th>Period</th>
                <th class="text-right">Total Amount</th>
                <th>Approved By</th>
                <th>Paid By</th>
            </tr>
        </thead>
        <tbody>
            @forelse($paidExpenses as $expense)
                <tr>
                    <td>{{ $expense->paid_at->format('d/m/Y') }}</td>
                    <td>{{ $expense->employee->full_name }}</td>
                    <td>{{ $expense->expenseType->name }}</td>
                    <td>{{ $expense->from_date->format('d/m') }} - {{ $expense->to_date->format('d/m') }}</td>
                    <td class="text-right">৳{{ number_format($expense->total, 2) }}</td>
                    <td>{{ $expense->approver->name ?? 'N/A' }}</td>
                    <td>{{ $expense->payer->name ?? 'N/A' }}</td>
                </tr>
            @empty
                <tr><td colspan="7" style="text-align: center;">No paid expenses found for this period.</td></tr>
            @endforelse
        </tbody>
        <tfoot>
            <tr>
                <td colspan="4" class="text-right"><strong>Grand Total:</strong></td>
                <td class="text-right"><strong>৳{{ number_format($totals['total'], 2) }}</strong></td>
                <td colspan="2"></td>
            </tr>
        </tfoot>
    </table>
</body>
</html>