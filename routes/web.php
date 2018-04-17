<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    if (!session()->has('username')) {
        return redirect('/login');
    }
    return view('welcome');
});

Route::get('/login', function () {
    return view('auth.login');
});

Route::post('/login', 'UserController@login');

Route::get('/logout', 'UserController@logout');
