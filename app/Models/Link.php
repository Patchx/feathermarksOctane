<?php

namespace App\Models;

use App\Classes\ApiWrappers\MeilisearchWrapper;

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
        'page_id',
    ];

    // --------------------
    // - Parent Overrides -
    // --------------------

    public function save(array $options = [])
    {
        $saved = parent::save($options);

        (new MeilisearchWrapper)->indexLink($this);

        return $saved;
    }

    // -----------------
    // - Relationships -
    // -----------------

    public function page()
    {
        return Page::where('custom_id', $this->page_id)->first();
    }

    public function user()
    {
        return User::where('custom_id', $this->user_id)->first();
    }
}
