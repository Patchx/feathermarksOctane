<?php

namespace App\Classes\ApiWrappers;

use App\Models\Link;
use MeiliSearch\Client;

// * Ran into issues with Laravel Scout, so just using the PHP SDK directly. (RA, Mar 2022)
// --
// * If you want to update the filterable fields on an index:
// curl -X POST 'http://localhost:7700/indexes/links/settings' -H 'Content-Type: application/json' --data-binary '{"filterableAttributes": ["user_id","category_id"]}'
// * For more info: https://docs.meilisearch.com/learn/getting_started/filtering_and_sorting.html#settings
// --
class MeilisearchWrapper
{
	public function indexAllLinks()
	{
		$links = Link::all();

		foreach($links as $link) {
			$this->indexLink($link);
		}
	}

	public function indexLink(Link $link)
	{
		$client = new Client('http://127.0.0.1:7700');

		$client->index('links')->addDocuments([
			[
				'id' => $link->custom_id,
				'name' => $link->name,
				'search_phrase' => $link->search_phrase,
				'user_id' => $link->user_id,
				'category_id' => $link->category_id,
			],
		]);
	}

	public function searchLinks($inputs)
	{
		$client = new Client('http://127.0.0.1:7700');
		$index = $client->index('links');

		return $index->search(
			$inputs['query'],
			[
				'filter' => [
					'user_id = ' . $inputs['user_id']
					. ' AND category_id = ' . $inputs['category_id']
				]
			]
		);
	}
}
