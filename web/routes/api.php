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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
//php artisan make:controller api/UserAPI --resource
Route::namespace("App\Http\Controllers\api")->group(function(){
    Route::get('/reservas', 'ReservaAPI@getAll')->name('reservas');
    Route::post('/reservas/{id}', [
        'uses' => 'ReservaAPI@getOne',
        'as' => 'get_reserva'
    ]);

    Route::get('/users', 'UserAPI@getAll')->name('users');
    Route::post('/user/{rfid}', [
        'uses' => 'UserAPI@getOne',
        'as' => 'get_user'
    ]);

    Route::get('/baias', 'BaiaAPI@getAll')->name('baias');
    Route::post('/baia/{baia}', [
        'uses' => 'BaiaAPI@getOne',
        'as' => 'get_baia'
    ]);
    Route::post('/baia/abrir/{num_baia}/{rfid}', [
        'uses' => 'BaiaAPI@abrir',
        'as' => 'abrir_baia'
    ]);

    Route::post('/baia/fechar/{num_baia}/{equipamentos?}', [
        'uses' => 'BaiaAPI@fechar',
        'as' => 'fechar_baia'
    ]);

    Route::post('/baia/reserva/{rfid}/{baiaId}', [
        'uses' => 'BaiaAPI@checkReserva',
        'as' => 'reserva_baia'
    ]);

    Route::get('/baia/email', 'BaiaAPI@sendEmail')->name('enviarEmail');

    Route::get('/database/all', 'DatabasesAPI@getAllData')->name('getAllData');

    Route::post('/database/update/{table}/{date}', [
        'uses' => 'DatabasesAPI@selectTableByDate',
        'as' => 'select_data'
    ]);
});