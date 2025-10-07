<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Houses;

class ResetMonthlyRent extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'rent:reset';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Resets the isPaid status for all houses at the start of the month.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // Reset isPaid status for all houses
        $affectedRows = Houses::query()->update(['isPaid' => 0]);

        $this->info("Monthly rent status has been reset for {$affectedRows} houses.");

        return Command::SUCCESS;
    }
}
