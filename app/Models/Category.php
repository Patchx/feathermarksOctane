<?php

namespace App\Models;

class Category extends AbstractModel
{
    // --------------
    // - Attributes -
    // --------------

    protected $fillable = [
    	'created_at',
    	'updated_at',
        'custom_id',
        'user_id',
        'name',
    ];

    // -----------------
    // - Relationships -
    // -----------------

    public function user()
    {
        return User::where('custom_id', $this->user_id)->first();
    }
}
