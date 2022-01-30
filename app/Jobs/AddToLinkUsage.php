<?php

namespace App\Jobs;

use App\Models\Link;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class AddToLinkUsage implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $link;
    private $usage_unix_time;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Link $link, $usage_unix_time)
    {
        $this->link = $link;
        $this->usage_unix_time = $usage_unix_time;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $this->addUsageToLink();
        $this->calcLinkUsageScore();
    }

    // -------------------
    // - Private Methods -
    // -------------------

    private function addUsageToLink()
    {
        $recent_uses = json_decode($this->link->recent_uses);

        if (count($recent_uses) > 0
            && $this->usage_unix_time - $recent_uses[0] < 200
        ) {
            return ['status' => 'clicked_too_recently'];
        }

        while (count($recent_uses) > 4) {
            array_pop($recent_uses);
        }

        array_unshift($recent_uses, $this->usage_unix_time);
        $this->link->recent_uses = json_encode($recent_uses);
        $this->link->save();
    }

    private function calcLinkUsageScore()
    {
        $recent_uses = json_decode($this->link->recent_uses);

        $coefficients = [
            10, 9, 8, 6, 5
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
