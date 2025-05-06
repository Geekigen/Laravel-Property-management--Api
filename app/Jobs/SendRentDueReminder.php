<?php

namespace App\Jobs;

use App\Models\Payments;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class SendRentDueReminder implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(public Payments $payment)
    {
        // Constructor property promotion used for $payment
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        Log::info("Rent due reminder sent for Payment ID: {$this->payment->id}");
    }
}
