<?php

namespace App\Services;

use App\Models\Transaction;
use App\Models\Invoice;
use App\Models\Rab;
use App\Models\Payable;
use Illuminate\Support\Facades\Cache;


class HealthScoreService
{
    /**
     * Calculate the overall Financial Health Score (0–100).
     *
     * Formula:
     * (35% × Cashflow Ratio) +
     * (15% × Receivable Ratio) +
     * (15% × RAB Accuracy) +
     * (15% × Stability) +
     * (20% × Payable Ratio)
     */
    public function calculate(): array
    {
        return Cache::remember('finance_health_score', 3600, function () {
            $cashflowScore = $this->getCashflowScore();
            $receivableScore = $this->getReceivableScore();
            $rabScore = $this->getRabScore();
            $stabilityScore = $this->getStabilityScore();
            $payableScore = $this->getPayableScore();

            $finalScore = round(
                ($cashflowScore * 0.35) +
                ($receivableScore * 0.15) +
                ($rabScore * 0.15) +
                ($stabilityScore * 0.15) +
                ($payableScore * 0.20),
                1
            );

            return [
                'total' => min(100, max(0, $finalScore)),
                'cashflow' => round($cashflowScore, 1),
                'receivable' => round($receivableScore, 1),
                'rab' => round($rabScore, 1),
                'stability' => round($stabilityScore, 1),
                'payable' => round($payableScore, 1),
            ];
        });
    }


    /**
     * Cashflow Ratio Score.
     * Ideal: income >= expense → 100.
     * Ratio = income / expense. Score = min(ratio * 100, 100).
     */
    private function getCashflowScore(): float
    {
        $income = (float) Transaction::where('type', 'income')->sum('amount');
        $expense = (float) Transaction::where('type', 'expense')->sum('amount');

        if ($expense <= 0) return $income > 0 ? 100 : 50;

        $ratio = $income / $expense;
        return min($ratio * 100, 100);
    }

    /**
     * Receivable Ratio Score.
     * Lower unpaid ratio = better.
     * Score = (1 - (unpaid / total_income)) * 100.
     */
    private function getReceivableScore(): float
    {
        $totalIncome = (float) Transaction::where('type', 'income')->sum('amount');
        
        $unpaid = (float) Invoice::where('status', '!=', 'paid')
            ->get()
            ->sum(fn($invoice) => $invoice->remaining_amount);

        if ($totalIncome <= 0) return $unpaid > 0 ? 0 : 100;

        $ratio = $unpaid / $totalIncome;
        return max(0, min(100, (1 - $ratio) * 100));
    }

    /**
     * RAB Accuracy Score.
     * Ideal: expense stays within budget.
     * Score = (1 - abs(expense/budget - 1)) * 100, capped at 0-100.
     */
    private function getRabScore(): float
    {
        $totalBudget = (float) Rab::sum('total_budget');
        $actualExpense = (float) Transaction::where('type', 'expense')
            ->whereNotNull('rab_id')
            ->sum('amount');

        if ($totalBudget <= 0) return $actualExpense > 0 ? 0 : 100;

        $ratio = $actualExpense / $totalBudget;

        // Ideal ratio is 1.0 (spent exactly budget), but under is better
        if ($ratio <= 1) {
            return $ratio * 100; // 0-100 as used 0%-100%
        }

        // Over budget: penalize
        return max(0, (2 - $ratio) * 100);
    }

    /**
     * Stability Score.
     * Higher B2B ratio = more stable income.
     * Score = (B2B / total_income) * 100.
     */
    private function getStabilityScore(): float
    {
        $totalIncome = (float) Transaction::where('type', 'income')->sum('amount');
        $b2bIncome = (float) Transaction::where('type', 'income')
            ->where('source_type', 'B2B')
            ->sum('amount');

        if ($totalIncome <= 0) return 0;

        return min(100, ($b2bIncome / $totalIncome) * 100);
    }

    /**
     * Payable Ratio Score.
     * Lower unpaid payables relative to expenses = better.
     * Score = (1 - (unpaid_payables / total_expense)) * 100.
     */
    private function getPayableScore(): float
    {
        $totalExpense = (float) Transaction::where('type', 'expense')->sum('amount');
        
        $unpaidPayables = (float) Payable::where('status', '!=', 'paid')
            ->get()
            ->sum(fn($p) => $p->remaining_amount);

        if ($totalExpense <= 0) return $unpaidPayables > 0 ? 0 : 100;

        $ratio = $unpaidPayables / $totalExpense;
        return max(0, min(100, (1 - $ratio) * 100));
    }

    /**
     * Generate auto insights based on scores.
     */
    public function generateInsights(): array
    {
        return Cache::remember('finance_health_insights', 3600, function () {
            $scores = $this->calculate();
            $insights = [];

            // 1. Cashflow insight
            if ($scores['cashflow'] < 50) {
                $insights[] = [
                    'type' => 'danger',
                    'icon' => '🔴',
                    'text' => 'Arus kas negatif! Segera kurangi pengeluaran non-prioritas.',
                ];
            }

            // 2. Actionable Receivable (Piutang)
            if ($scores['receivable'] < 80) {
                $oldestInvoice = Invoice::where('status', 'unpaid')->orderBy('due_date', 'asc')->first();
                if ($oldestInvoice) {
                    $insights[] = [
                        'type' => 'warning',
                        'icon' => '📞',
                        'text' => "Follow up piutang {$oldestInvoice->client_name} (Rp " . number_format($oldestInvoice->total, 0, ',', '.') . ") yang jatuh tempo pada " . $oldestInvoice->due_date->format('d M') . " untuk menambah saldo.",
                    ];
                }
            }

            // 3. Actionable Payable (Utang)
            if ($scores['payable'] < 80) {
                $topPayable = Payable::where('status', 'unpaid')->orderBy('total', 'desc')->first();
                if ($topPayable) {
                    $insights[] = [
                        'type' => 'warning',
                        'icon' => '💸',
                        'text' => "Bayar utang ke {$topPayable->supplier_name} (Rp " . number_format($topPayable->total, 0, ',', '.') . ") untuk membersihkan kewajiban dan menaikkan skor stabilitas.",
                    ];
                }
            }

            // 4. RAB Budget Alerts
            $criticalRabs = Rab::all()->filter(function($rab) {
                $spent = Transaction::where('type', 'expense')->where('rab_id', $rab->id)->sum('amount');
                return ($spent / $rab->total_budget) > 0.9;
            });

            foreach ($criticalRabs as $rab) {
                $spent = Transaction::where('type', 'expense')->where('rab_id', $rab->id)->sum('amount');
                $percent = round(($spent / $rab->total_budget) * 100);
                $insights[] = [
                    'type' => 'danger',
                    'icon' => '⚠️',
                    'text' => "Anggaran RAB '{$rab->name}' sudah terpakai {$percent}%. Kontrol pengeluaran kategori ini!",
                ];
            }

            // 5. Stability insight
            if ($scores['stability'] < 30) {
                $insights[] = [
                    'type' => 'warning',
                    'icon' => '⚡',
                    'text' => 'Pendapatan terlalu bergantung pada B2C. Coba naikkan porsi klien B2B untuk stabilitas jangka panjang.',
                ];
            }

            return $insights;
        });
    }
}
