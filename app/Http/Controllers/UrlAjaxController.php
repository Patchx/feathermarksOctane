<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UrlAjaxController extends Controller
{
    public function getTitle($url)
    {
    	if (!preg_match("~^(?:f|ht)tps?://~i", $url)) {
    	    $url = "http://" . $url;
    	}

	    $page = file_get_contents($url);
	    
	    $title = preg_match(
	    	'/<title[^>]*>(.*?)<\/title>/ims', 
	    	$page, 
	    	$match
	    ) ? $match[1] : null;
    	
	    if ($title === null) {
	    	$status = 'title_not_found';
	    } else {
	    	$status = 'success';
	    }

    	return json_encode([
    		'status' => $status,
    		'title' => $title,
    	]);
    }
}
