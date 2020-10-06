<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\Channel\ChannelController;

Route::prefix('channel')->middleware('can:channel management')->group(function() {
	Route::get('/', [ChannelController::class, 'all'])->name('channel.all');
	Route::post('/', [ChannelController::class, 'createChannel'])->name('channel.create');
	Route::put('/', [ChannelController::class, 'update'])->name('channel.update');
	Route::delete('/{channel}', [ChannelController::class, 'destroy'])->name('channel.destroy');
});