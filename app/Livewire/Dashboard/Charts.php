<?php

namespace App\Livewire\Dashboard;

use Livewire\Component;
use App\Services\FinanceService;

class Charts extends Component
{
    public array $monthlyData = [];
    public array $b2bB2cData = [];

    protected $listeners = ['dataUpdated' => 'refreshData'];

    public function mount(): void
    {
        $this->refreshData();
    }

    public function refreshData(): void
    {
        $financeService = app(FinanceService::class);
        $this->monthlyData = $financeService->getMonthlyData();
        $this->b2bB2cData = $financeService->getB2BvsB2CData();
    }

    public function render()
    {
        return view('livewire.dashboard.charts');
    }
}
