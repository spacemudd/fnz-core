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
        foreach ($this->wr->items as $item) {
            $item->available_qty = $item->required_qty;
            $item->price = round(rand(1,10), 2);
            $item->save();
        }
        $this->wr->priced_at = now();
        $this->wr->status = 'completed';
        $this->wr->save();

        if ($this->wr->webhook_url_at) {
            Http::get($this->wr->webhook_url_at, $this->wr->refresh());
        }
    }
}
