<?php

namespace App\Jobs;

use App\Models\Link;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class CalcLinkUsageScore implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $link;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Link $link)
    {
        $this->link = $link;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $recent_uses = json_decode($this->link->recent_uses);

        $coefficients = [
            10, 9, 7, 6, 4
        ];

        $score = 0;

        for ($i = 0; $i < 5; $i++) {
            $unix_time = intval(array_shift($recent_uses));
            $coefficient = $coefficients[$i];
            $score += $unix_time * $coefficient;
        }

        $score = $score / 2000000000; // scaling factor
        $this->link->recent_usage_score = $score;
        $this->link->usage_score_calculated_on = now();
        $this->link->save();
    }
}
