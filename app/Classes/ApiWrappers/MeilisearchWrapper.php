<?php

namespace App\Classes\ApiWrappers;

use App\Models\Link;

// * Ran into issues with Laravel Scout, so just using curl and Meilisearch directly. (RA, Mar 2022)
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
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, 'http://127.0.0.1:7700/indexes/links/documents');
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_POST, 1);
				
		curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode([
			[
				'id' => $link->custom_id,
				'name' => $link->name,
				'search_phrase' => $link->search_phrase,
				'user_id' => $link->user_id,
				'category_id' => $link->category_id,
			],
		]));

		curl_setopt($ch, CURLOPT_HTTPHEADER, [
			'Authorization: Bearer ' . env('MEILISEARCH_MASTER_KEY'),
			'Content-Type: application/json',
		]);

		$result = curl_exec($ch);

		if (curl_errno($ch)) {
		    echo 'Error:' . curl_error($ch);
		}

		curl_close($ch);
	}

	public function searchLinks($inputs)
	{
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, 'http://127.0.0.1:7700/indexes/links/search');
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_POST, 1);
		
		curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode([
			'q' => $inputs['query'],
			'filter' => [
				'user_id = ' . $inputs['user_id']
				. ' AND category_id = ' . $inputs['category_id']
			],
		]));

		curl_setopt($ch, CURLOPT_HTTPHEADER, [
			'Authorization: Bearer ' . env('MEILISEARCH_MASTER_KEY'),
			'Content-Type: application/json',
		]);

		$result = curl_exec($ch);

		if (curl_errno($ch)) {
		    echo 'Error:' . curl_error($ch);
		}

		curl_close($ch);

		return json_decode($result);
	}
}
