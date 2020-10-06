<?php

namespace App\Http\Controllers\Api\V1\Channel;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Channel;
use Illuminate\Support\Str;
use App\Repositories\ChannelRepository;
use Symfony\Component\HttpFoundation\Response;

class ChannelController extends Controller
{
	/*
	*get all channel list
	*/
    public function all()
    {
        $channels = resolve(ChannelRepository::class)->all();
        
    	return response()->json($channels, Response::HTTP_OK);
    }

    /*
    * create a new channel
    */
    public function createChannel(Request $request)
    {
    	$this->validate($request, [
    		'name' => 'required'
    	]);

    	resolve(ChannelRepository::class)->create($request->name);

    	return response()->json([
    		'message' => 'channel created successfully'
    	], Response::HTTP_CREATED);
    }

    /*
    *
    */
    public function update(Request $request)
    {
    	$request->validate(['name' => 'required']);

    	resolve(ChannelRepository::class)->update($request->id, $request->name);

    	return response()->json(['message' => 'updated successfully'], Response::HTTP_OK);
    }

    /*
    *
    */
    public function destroy(Channel $channel)
    {
    	resolve(ChannelRepository::class)->delete($channel);

    	return response()->json(['message' => 'deleted successfully'], Response::HTTP_OK);
    }
}
