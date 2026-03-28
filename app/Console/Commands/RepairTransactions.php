<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Transaction;
use Illuminate\Support\Carbon;

class RepairTransactions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:repair-transactions {--date= : Filter by date (YYYY-MM-DD)} {--dry-run : Only show what would be changed}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fix transactions with stunted amounts due to previous import parsing error';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $date = $this->option('date') ?: now()->toDateString();
        $dryRun = $this->option('dry-run');

        $this->info("Scanning transactions for date: {$date}...");

        $query = Transaction::whereDate('created_at', $date)
            ->where('amount', '<', 5000); // Most logical stunted values will be low

        $transactions = $query->get();

        if ($transactions->isEmpty()) {
            $this->warn("No suspicious transactions found for this date.");
            return;
        }

        $this->table(
            ['ID', 'Description', 'Old Amount', 'New Amount', 'Old Type', 'New Type'],
            $transactions->map(function ($t) {
                return [
                    $t->id,
                    $t->description,
                    $t->amount,
                    $t->amount * 1000,
                    $t->type,
                    'income'
                ];
            })
        );

        if ($dryRun) {
            $this->info("Dry run complete. No changes made.");
            return;
        }

        if ($this->confirm("Do you want to apply these changes? (Multiplying by 1000 and setting type to 'income')")) {
            foreach ($transactions as $t) {
                $t->update([
                    'amount' => $t->amount * 1000,
                    'type' => 'income'
                ]);
            }
            $this->info("Successfully repaired " . count($transactions) . " transactions.");
        } else {
            $this->info("Operation cancelled.");
        }
    }
}
