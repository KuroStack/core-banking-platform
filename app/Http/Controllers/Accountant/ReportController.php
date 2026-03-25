<?php
namespace App\Http\Controllers\Accountant;
use App\Http\Controllers\Controller;
use App\Models\{Loan, BankAccountTransaction, LoanTransaction, Customer};
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ReportController extends Controller
{
    public function loanOutstanding(Request $request)
    {
        $loans = $this->getLoanOutstandingQuery();

        if ($request->has('export') && $request->export === 'csv') {
            return $this->exportCsv('loan-outstanding.csv', ['Loan #', 'Customer', 'Type', 'Amount', 'Outstanding', 'Rate', 'EMI'], $loans->map(fn($l) => [
                $l->loan_number, $l->customer?->full_name, $l->loanType?->name,
                $l->amount, $l->outstanding_balance, $l->interest_rate, $l->installment_amount,
            ]));
        }

        return view('accountant.reports.loan-outstanding', compact('loans'));
    }

    public function transactionStatement(Request $request)
    {
        $branchId = auth()->user()->branch_id;

        if (!$request->has('from_date')) {
            return view('accountant.reports.transaction-statement', ['transactions' => null, 'request' => $request]);
        }

        $request->validate(['from_date' => 'required|date', 'to_date' => 'required|date|after_or_equal:from_date', 'account_number' => 'nullable']);
        $transactions = BankAccountTransaction::where('branch_id', $branchId)
            ->when($request->account_number, fn($q) => $q->where('account_number', $request->account_number))
            ->whereBetween('transaction_date', [$request->from_date, $request->to_date . ' 23:59:59'])
            ->with('customer')
            ->orderBy('transaction_date')
            ->get();

        if ($request->has('export') && $request->export === 'csv') {
            return $this->exportCsv('transaction-statement.csv', ['Date', 'Account #', 'Customer', 'Type', 'Amount', 'Balance After'], $transactions->map(fn($tx) => [
                $tx->transaction_date?->format('Y-m-d H:i'), $tx->account_number, $tx->customer?->full_name,
                $tx->transaction_type, $tx->amount, $tx->balance_after,
            ]));
        }

        return view('accountant.reports.transaction-statement', compact('transactions', 'request'));
    }

    public function loanDemand(Request $request)
    {
        $loans = $this->getLoanDemandQuery();

        if ($request->has('export') && $request->export === 'csv') {
            return $this->exportCsv('loan-demand.csv', ['Loan #', 'Customer', 'Type', 'EMI', 'Outstanding', 'Last Payment'], $loans->map(fn($l) => [
                $l->loan_number, $l->customer?->full_name, $l->loanType?->name,
                $l->installment_amount, $l->outstanding_balance,
                $l->transactions->first()?->payment_date?->format('Y-m-d') ?? 'Never',
            ]));
        }

        return view('accountant.reports.loan-demand', compact('loans'));
    }

    // ── API CSV Export ───────────────────────────────────────────

    private function getLoanOutstandingQuery()
    {
        $branchId = auth()->user()->branch_id;
        return Loan::where('branch_id', $branchId)
            ->where('status', 'active')
            ->with(['customer', 'loanType'])
            ->get();
    }

    private function getLoanDemandQuery()
    {
        $branchId = auth()->user()->branch_id;
        return Loan::where('branch_id', $branchId)
            ->where('status', 'active')
            ->with(['customer', 'loanType', 'transactions' => fn($q) => $q->latest()->limit(1)])
            ->get();
    }

    private function exportCsv(string $filename, array $headers, $rows): StreamedResponse
    {
        return response()->streamDownload(function () use ($headers, $rows) {
            $out = fopen('php://output', 'w');
            fputcsv($out, $headers);
            foreach ($rows as $row) {
                fputcsv($out, is_array($row) ? $row : $row->toArray());
            }
            fclose($out);
        }, $filename, ['Content-Type' => 'text/csv']);
    }
}
