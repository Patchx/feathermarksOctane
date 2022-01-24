<?php

namespace App\Classes\Repositories;

use App\Models\Link;

class LinkRepository
{
	public function frequentlyUsedLinks($category_id)
	{
		return Link::where('category_id', $category_id)
			->orderByDesc('recent_usage_score')
			->where('recent_usage_score', '>', 0)
			->limit(8)
			->get();
	}
}
