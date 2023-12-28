<?php

namespace App\Jobs;

use App\Models\WorkRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;

class Repair360WebhookJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $wr;

    /**
     * Create a new job instance.
     */
    public function __construct(WorkRequest $wr)
    {
        $this->wr = $wr;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        // Remove when in production
        // Simulate a fake price
        $this->wr->fnz_price = rand(1,10);
        $this->wr->fnz_priced_at = now();
        $this->wr->status = 'available';
        $this->wr->is_available_qty = 1;
        $this->wr->save();

        if ($this->wr->webhook_url_at) {
            Http::get($this->wr->webhook_url_at, $this->wr->refresh());
        }
    }
}
