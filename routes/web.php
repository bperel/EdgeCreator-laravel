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

use App\Helpers\DmClient;
use App\Helpers\Publications;
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

Route::middleware(['dmserver_auth'])->group(function() {
    Route::get('/logout', 'UserController@logout');

    Route::get('/tranchesencours/load', function () {
        /** @var DmClient $dmClient */
        $dmClient = resolve(DmClient::class);

        /** @var Publications $publicationsHelper */
        $publicationsHelper = resolve(Publications::class);
        $ongoingModels = $dmClient->getServiceResults('GET', "/edgecreator/v2/model", [], 'edgecreator');

        $publicationsHelper->assignPublicationNames($ongoingModels);

        return [
            'tranches_en_cours' => $ongoingModels
        ];
    });
});
