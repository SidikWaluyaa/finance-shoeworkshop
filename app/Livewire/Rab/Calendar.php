<?php

namespace App\Livewire\Rab;

use Livewire\Component;
use App\Models\Rab;
use Carbon\Carbon;

class Calendar extends Component
{
    public int $currentMonth;
    public int $currentYear;
    public int $daysInMonth;
    public int $firstDayOfMonth;
    public string $monthName;

    public $rabs = [];
    public $payables = [];

    protected $listeners = ['dataUpdated' => 'loadRabs'];

    public function mount()
    {
        $this->currentMonth = now()->month;
        $this->currentYear = now()->year;
        $this->updateCalendar();
    }

    public function updateCalendar()
    {
        $date = Carbon::createFromDate($this->currentYear, $this->currentMonth, 1);
        $this->daysInMonth = $date->daysInMonth;
        $this->firstDayOfMonth = $date->dayOfWeek; // 0 (Sun) to 6 (Sat)
        $this->monthName = $date->translatedFormat('F');
        
        $this->loadRabs();
    }

    public function loadRabs()
    {
        $startOfMonth = Carbon::createFromDate($this->currentYear, $this->currentMonth, 1)->startOfMonth();
        $endOfMonth = $startOfMonth->copy()->endOfMonth();

        $this->rabs = Rab::where(function ($query) use ($startOfMonth, $endOfMonth) {
            $query->whereBetween('start_date', [$startOfMonth, $endOfMonth])
                  ->orWhereBetween('end_date', [$startOfMonth, $endOfMonth])
                  ->orWhere(function ($q) use ($startOfMonth, $endOfMonth) {
                      $q->where('start_date', '<', $startOfMonth)
                        ->where('end_date', '>', $endOfMonth);
                  });
        })->get();

        $this->payables = \App\Models\Payable::where(function($q) use ($startOfMonth, $endOfMonth) {
                $q->whereBetween('promise_to_pay_date', [$startOfMonth, $endOfMonth])
                  ->orWhere(function($sq) use ($startOfMonth, $endOfMonth) {
                      $sq->whereNull('promise_to_pay_date')
                         ->whereBetween('due_date', [$startOfMonth, $endOfMonth]);
                  });
            })->get();
    }

    public function previousMonth()
    {
        if ($this->currentMonth == 1) {
            $this->currentMonth = 12;
            $this->currentYear--;
        } else {
            $this->currentMonth--;
        }
        $this->updateCalendar();
    }

    public function nextMonth()
    {
        if ($this->currentMonth == 12) {
            $this->currentMonth = 1;
            $this->currentYear++;
        } else {
            $this->currentMonth++;
        }
        $this->updateCalendar();
    }

    public function render()
    {
        return view('livewire.rab.calendar');
    }
}
