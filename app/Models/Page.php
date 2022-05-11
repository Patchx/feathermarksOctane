<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;

class Page extends AbstractModel
{
    use SoftDeletes;

    // --------------
    // - Attributes -
    // --------------

    protected $fillable = [
        'created_at',
        'updated_at',
        'custom_id',
        'user_id',
        'link_id',
        'html',
        'visibility',
    ];
}
