<?php

namespace App\Livewire\Dashboard;

use Livewire\Component;
use App\Services\FinanceService;
use App\Services\HealthScoreService;
use Carbon\Carbon;

class Overview extends Component
{
    public array $summary = [];
    public array $healthScore = [];
    public array $insights = [];
    public $recentTransactions;
    public string $period = 'this_month'; // this_month, 3_months, this_year, all

    protected $listeners = ['dataUpdated' => 'refreshData'];

    protected $queryString = ['period'];

    public function mount(): void
    {
        $this->refreshData();
    }

    public function updatedPeriod(): void
    {
        $this->refreshData();
    }

    public function refreshData(): void
    {
        $financeService = app(FinanceService::class);
        $healthService = app(HealthScoreService::class);

        $startDate = null;
        $endDate = Carbon::now()->toDateString();
        $now = Carbon::now();

        if ($this->period === 'this_month') {
            $startDate = $now->startOfMonth()->toDateString();
        } elseif ($this->period === '3_months') {
            $startDate = $now->subMonths(2)->startOfMonth()->toDateString();
        } elseif ($this->period === 'this_year') {
            $startDate = $now->startOfYear()->toDateString();
        }

        $this->summary = $financeService->getDashboardSummary($startDate, $endDate);
        $this->healthScore = $healthService->calculate();
        $this->insights = $healthService->generateInsights();
        $this->recentTransactions = $financeService->getRecentTransactions(5);
    }

    public function formatCurrencyShort($value): string
    {
        $value = (float) $value;
        $abs = abs($value);

        if ($abs >= 1000000) {
            return 'Rp' . number_format($value / 1000000, 1, ',', '.') . 'jt';
        }

        if ($abs >= 1000) {
            return 'Rp' . number_format($value / 1000, 0, ',', '.') . 'rb';
        }

        return 'Rp' . number_format($value, 0, ',', '.');
    }

    public function render()
    {
        return view('livewire.dashboard.overview');
    }
}
