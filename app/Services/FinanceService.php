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
     * Get today's financial pulse — income & expense with comparison and trends.
     */
    public function getTodaySummary(): array
    {
        $today = Carbon::today()->toDateString();
        $yesterday = Carbon::yesterday()->toDateString();

        $incomeToday = $this->getTotalIncome($today, $today);
        $expenseToday = $this->getTotalExpense($today, $today);
        $incomeYesterday = $this->getTotalIncome($yesterday, $yesterday);
        $expenseYesterday = $this->getTotalExpense($yesterday, $yesterday);

        return [
            'income_today' => $incomeToday,
            'expense_today' => $expenseToday,
            'income_yesterday' => $incomeYesterday,
            'expense_yesterday' => $expenseYesterday,
            'income_change' => $this->calculateGrowth($incomeYesterday, $incomeToday),
            'expense_change' => $this->calculateGrowth($expenseYesterday, $expenseToday),
            'net_today' => $incomeToday - $expenseToday,
            'income_trend' => $this->getLast7DaysData('income'),
            'expense_trend' => $this->getLast7DaysData('expense'),
            'top_expense_categories' => $this->getTopExpenseCategoriesForDate($today),
            'transaction_count_today' => Transaction::where('date', $today)->count(),
        ];
    }

    /**
     * Get today's invoices — newly created + due today.
     */
    public function getTodayInvoices(): array
    {
        $today = Carbon::today()->toDateString();

        $createdToday = Invoice::whereDate('created_at', $today)
            ->orderBy('total', 'desc')
            ->get();

        $dueToday = Invoice::whereDate('due_date', $today)
            ->where('status', '!=', 'paid')
            ->orderBy('total', 'desc')
            ->get();

        $overdueInvoices = Invoice::where('status', '!=', 'paid')
            ->whereDate('due_date', '<', $today)
            ->count();

        $allTodayIds = $createdToday->pluck('id')->merge($dueToday->pluck('id'))->unique();

        return [
            'created_today' => $createdToday,
            'due_today' => $dueToday,
            'total_created_amount' => $createdToday->sum('total'),
            'total_due_amount' => $dueToday->sum('total'),
            'count_created' => $createdToday->count(),
            'count_due' => $dueToday->count(),
            'count_overdue' => $overdueInvoices,
            'total_count' => $allTodayIds->count(),
        ];
    }

    /**
     * Get active RABs with budget progress (end_date >= today or no end_date).
     */
    public function getActiveRabs(): array
    {
        $today = Carbon::today();

        $rabs = Rab::withSum(['transactions as used_budget_sum' => function($q) {
                $q->where('type', 'expense');
            }], 'amount')
            ->where(function ($q) use ($today) {
                $q->where('end_date', '>=', $today) // Masih dalam masa aktif
                  ->orWhereNull('end_date')        // Tanpa batas waktu (Evergreen)
                  ->orWhereRaw('total_budget > (SELECT COALESCE(SUM(amount), 0) FROM transactions WHERE rab_id = rabs.id AND type = "expense")'); // Belum lunas
            })
            ->orderBy('start_date', 'desc')
            ->get()
            ->map(function (Rab $rab) {
                $used = (float) ($rab->used_budget_sum ?? 0);
                $total = (float) $rab->total_budget;
                $percent = $total > 0 ? round(($used / $total) * 100, 1) : 0;

                return [
                    'id' => $rab->id,
                    'name' => $rab->name,
                    'total_budget' => $total,
                    'used_budget' => $used,
                    'remaining' => max(0, $total - $used),
                    'percent' => min($percent, 100),
                    'status' => $percent >= 90 ? 'danger' : ($percent >= 70 ? 'warning' : 'safe'),
                    'start_date' => $rab->start_date?->format('d M Y'),
                    'end_date' => $rab->end_date?->format('d M Y'),
                    'is_over_budget' => $used > $total,
                ];
            })
            ->toArray();

        $totalBudget = array_sum(array_column($rabs, 'total_budget'));
        $totalUsed = array_sum(array_column($rabs, 'used_budget'));

        return [
            'items' => $rabs,
            'count' => count($rabs),
            'total_budget' => $totalBudget,
            'total_used' => $totalUsed,
            'total_remaining' => max(0, $totalBudget - $totalUsed),
            'overall_percent' => $totalBudget > 0 ? round(($totalUsed / $totalBudget) * 100, 1) : 0,
        ];
    }

    /**
     * Get priority payables (unpaid, ordered by promise_to_pay_date).
     */
    public function getPriorityPayables(int $limit = 100)
    {
        return Payable::where('status', '!=', 'paid')
            ->orderByRaw('COALESCE(promise_to_pay_date, due_date) ASC')
            ->limit($limit)
            ->get();
    }

    /**
     * Get last 7 days trend data for sparkline charts.
     */
    private function getLast7DaysData(string $type): array
    {
        $data = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::today()->subDays($i)->toDateString();
            $amount = (float) Transaction::where('type', $type)
                ->where('date', $date)
                ->sum('amount');
            $data[] = $amount;
        }
        return $data;
    }

    /**
     * Get top expense categories for a specific date.
     */
    private function getTopExpenseCategoriesForDate(string $date, int $limit = 3): array
    {
        return Transaction::where('type', 'expense')
            ->where('date', $date)
            ->whereNotNull('category_id')
            ->select('category_id', DB::raw('SUM(amount) as total'))
            ->groupBy('category_id')
            ->orderByDesc('total')
            ->limit($limit)
            ->with('category')
            ->get()
            ->map(fn($t) => [
                'name' => $t->category?->name ?? 'Lainnya',
                'total' => (float) $t->total,
            ])
            ->toArray();
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
