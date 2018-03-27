<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::get('/login', 'UserController@login');

Route::middleware(['dmserver_auth'])->group(function() {
    Route::get('/check_logged_in', function () {
        return "OK";
    });

    Route::get('/logout', 'UserController@logout');
});
