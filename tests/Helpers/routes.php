<?php

use Illuminate\Support\Facades\Route;
use Larapress\Honeypot\Tests\Helpers\HttpController;

Route::get('/', [HttpController::class, 'testView']);
Route::post('/post', [HttpController::class, 'post'])->name('honeypot.post');
Route::get('/redirect', [HttpController::class, 'redirectedTo'])->name('honeypot.redirect');
