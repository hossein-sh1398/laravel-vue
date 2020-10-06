<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\Thread\ThreadController;
use App\Http\Controllers\Api\V1\Thread\AnswerController;
use App\Http\Controllers\Api\V1\Subscribe\SubscribeController;

Route::resource('thread', ThreadController::class);

Route::prefix('thread')->group(function() {
	Route::resource('answer', AnswerController::class);
	Route::get('subscribe/{thread}', [SubscribeController::class, 'subscribe'])->name('subscribe');
});