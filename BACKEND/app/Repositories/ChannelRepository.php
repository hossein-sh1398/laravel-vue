<?php
namespace App\Repositories;

use Illuminate\Support\Str;
use App\Models\Channel;

class ChannelRepository
{
    /*
    *
    */
	public function all()
    {
         return Channel::all();
    }
    /*
	*
	*/
	public function create($name)
    {
    	Channel::create([
    		'name' => $name,
    		'slug' => Str::slug($name)
    	]);
    }

    /*
    *
    */
    public function update($id, $name) 
    {
        $channel = Channel::find($id);
    	$channel->name = $name;
    	$channel->slug = Str::slug($name);
    	$channel->update();
    }

    /*
    *
    */
    public function delete(Channel $channel)
    {
    	$channel->delete();
    }
}