<?php

namespace App\Services;

use App\Models\Transaction;
use App\Models\Invoice;
use App\Models\Rab;
use App\Models\Payable;
use App\Models\Account;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class FinanceService
{
    /**
     * Get total income for a given period or all time.
     */
    public function getTotalIncome(?string $startDate = null, ?string $endDate = null): float
    {
        $query = Transaction::where('type', 'income');

        if ($startDate) $query->where('date', '>=', $startDate);
        if ($endDate) $query->where('date', '<=', $endDate);

        return (float) $query->sum('amount');
    }

    /**
     * Get total expense for a given period or all time.
     */
    public function getTotalExpense(?string $startDate = null, ?string $endDate = null): float
    {
        $query = Transaction::where('type', 'expense');

        if ($startDate) $query->where('date', '>=', $startDate);
        if ($endDate) $query->where('date', '<=', $endDate);

        return (float) $query->sum('amount');
    }

    /**
     * Get net cashflow (income - expense).
     */
    public function getNetCashflow(?string $startDate = null, ?string $endDate = null): float
    {
        return $this->getTotalIncome($startDate, $endDate) - $this->getTotalExpense($startDate, $endDate);
    }

    /**
     * Get total unpaid receivables (including partially paid).
     */
    public function getTotalReceivables(): float
    {
        return (float) Invoice::where('status', '!=', 'paid')
            ->get()
            ->sum(fn($invoice) => $invoice->remaining_amount);
    }

    /**
     * Get total remaining budget across all RABs.
     */
    public function getRemainingBudget(): float
    {
        $totalBudget = (float) Rab::sum('total_budget');
        $totalExpenseOnRab = (float) Transaction::where('type', 'expense')
            ->whereNotNull('rab_id')
            ->sum('amount');

        return $totalBudget - $totalExpenseOnRab;
    }

    /**
     * Get monthly cashflow data for charts (last 6 months).
     */
    public function getMonthlyData(int $months = 6): array
    {
        $data = ['labels' => [], 'income' => [], 'expense' => []];

        for ($i = $months - 1; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $startOfMonth = $date->copy()->startOfMonth()->toDateString();
            $endOfMonth = $date->copy()->endOfMonth()->toDateString();

            $data['labels'][] = $date->format('M Y');
            $data['income'][] = $this->getTotalIncome($startOfMonth, $endOfMonth);
            $data['expense'][] = $this->getTotalExpense($startOfMonth, $endOfMonth);
        }

        return $data;
    }

    /**
     * Get B2B vs B2C income data for charts.
     */
    public function getB2BvsB2CData(): array
    {
        $b2b = (float) Transaction::where('type', 'income')
            ->where('source_type', 'B2B')
            ->sum('amount');

        $b2c = (float) Transaction::where('type', 'income')
            ->where('source_type', 'B2C')
            ->sum('amount');

        return [
            'labels' => ['B2B', 'B2C'],
            'data' => [$b2b, $b2c],
        ];
    }

    /**
     * Get Month-over-Month (MoM) comparison for key metrics.
     */
    public function getMoMComparison(): array
    {
        $now = Carbon::now();
        $thisMonthStart = $now->copy()->startOfMonth()->toDateString();
        $thisMonthEnd = $now->copy()->endOfMonth()->toDateString();
        
        $lastMonthStart = $now->copy()->subMonth()->startOfMonth()->toDateString();
        $lastMonthEnd = $now->copy()->subMonth()->endOfMonth()->toDateString();

        $thisIncome = $this->getTotalIncome($thisMonthStart, $thisMonthEnd);
        $lastIncome = $this->getTotalIncome($lastMonthStart, $lastMonthEnd);

        $thisExpense = $this->getTotalExpense($thisMonthStart, $thisMonthEnd);
        $lastExpense = $this->getTotalExpense($lastMonthStart, $lastMonthEnd);

        return [
            'income' => $this->calculateGrowth($lastIncome, $thisIncome),
            'expense' => $this->calculateGrowth($lastExpense, $thisExpense),
            'net' => $this->calculateGrowth($lastIncome - $lastExpense, $thisIncome - $thisExpense),
        ];
    }

    private function calculateGrowth(float $old, float $new): array
    {
        if ($old == 0) {
            return ['percent' => $new > 0 ? 100 : 0, 'trend' => $new >= 0 ? 'up' : 'down'];
        }

        $percent = (($new - $old) / abs($old)) * 100;
        
        return [
            'percent' => round(abs($percent), 1),
            'trend' => $percent >= 0 ? 'up' : 'down',
            'is_positive' => $percent >= 0,
        ];
    }

    /**
     * Get recent transactions.
     */
    public function getRecentTransactions(int $limit = 10)
    {
        return Transaction::with(['account', 'category'])
            ->orderBy('date', 'desc')
            ->orderBy('created_at', 'desc')
            ->limit($limit)
            ->get();
    }

    /**
     * Get dashboard summary with optional date filtering.
     */
    public function getDashboardSummary(?string $startDate = null, ?string $endDate = null): array
    {
        $accounts = Account::all();
        $totalBalance = 0;
        $accountBalances = [];

        foreach ($accounts as $account) {
            $balance = $account->realtime_balance;
            $totalBalance += $balance;
            $accountBalances[] = [
                'name' => $account->name,
                'type' => $account->type,
                'balance' => $balance,
            ];
        }

        return [
            'total_income' => $this->getTotalIncome($startDate, $endDate),
            'total_expense' => $this->getTotalExpense($startDate, $endDate),
            'net_cashflow' => $this->getNetCashflow($startDate, $endDate),
            'total_receivables' => $this->getTotalReceivables(),
            'remaining_budget' => $this->getRemainingBudget(),
            'total_payables' => (float) Payable::where('status', '!=', 'paid')
                ->get()
                ->sum(fn($p) => $p->remaining_amount),
            'paid_payables' => (float) Payable::sum('total') - (float) Payable::where('status', '!=', 'paid')
                ->get()
                ->sum(fn($p) => $p->remaining_amount),
            'total_balance' => $totalBalance,
            'account_balances' => $accountBalances,
            'mom' => $this->getMoMComparison(),
        ];
    }
}
