<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Jobs\SendRentDueReminder;
use App\Models\Payments;

class SendRentDueReminders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:send-rent-due-reminders';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send rent due reminders to tenants with active payments';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $upcomingPayments = Payments::where('status', "paid")
            ->get();

        $count = 0;
        foreach ($upcomingPayments as $payment) {
            SendRentDueReminder::dispatch($payment);
            $count++;
        }

        $this->info("$count rent due reminders dispatched.");
    }
}
