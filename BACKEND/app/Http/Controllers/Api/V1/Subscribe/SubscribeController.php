<?php

namespace App\Http\Controllers\Api\V1\Subscribe;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Thread;
use Symfony\Component\HttpFoundation\Response;
use App\Repositories\SubscribeRepository;

class SubscribeController extends Controller
{
    public function subscribe(Thread $thread)
    {
    	$boolean = resolve(SubscribeRepository::class)->subscribe($thread);

    	if ($boolean) {
	    	return response()->json([
	    		'message' => 'subscribe created successfully'
	    	], Response::HTTP_CREATED);
    	}
    	
		return response()->json([
    		'message' => 'subscribe deleted successfully'
    	], Response::HTTP_OK);
    }
}
