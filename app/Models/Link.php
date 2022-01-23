<?php

namespace App\Models;

use App\Jobs\CalcLinkUsageScore;

class Link extends AbstractModel
{
    // --------------
    // - Attributes -
    // --------------

    protected $fillable = [
    	'created_at',
    	'updated_at',
        'custom_id',
        'user_id',
        'category_id',
        'name',
        'url',
        'search_phrase',
        'instaopen_command',
        'recent_uses',
        'recent_usage_score',
        'usage_score_calculated_on',
    ];

    // -----------------
    // - Relationships -
    // -----------------

    public function user()
    {
        return User::where('custom_id', $this->user_id)->first();
    }

    // ------------------
    // - Public Methods -
    // ------------------

    public function addNewUsage($unix_time)
    {
        $recent_uses = json_decode($this->recent_uses);

        if (count($recent_uses) > 0
            && $unix_time - $recent_uses[0] < 200
        ) {
            return ['status' => 'clicked_too_recently'];
        }

        while (count($recent_uses) > 4) {
            array_pop($recent_uses);
        }

        array_unshift($recent_uses, $unix_time);
        $this->recent_uses = json_encode($recent_uses);
        $this->save();

        CalcLinkUsageScore::dispatch($this);

        return ['status' => 'success'];
    }
}
