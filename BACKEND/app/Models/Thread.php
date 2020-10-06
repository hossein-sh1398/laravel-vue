<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Thread extends Model
{
    use HasFactory;
    protected $fillable = [
    	'title',
    	'slug',
    	'content',
    	'answer_id',
    	'channel_id',
    	'user_id',
    	'flag',
    ];

    public function user()
    {
    	return $this->belongsTo(User::class);
    }

    public function channel()
    {
    	return $this->belongsTo(Channel::class);
    }

    public function bestAnswer()
    {
    	return $this->belongsTo(Answer::class, 'id', 'answer_id');
    }

    public function subscribes()
    {
    	return $this->hasMany(Subscribe::class);
    }

    public function answers()
    {
    	return $this->hasMany(Answer::class, 'thread_id', 'id');
    }
}
