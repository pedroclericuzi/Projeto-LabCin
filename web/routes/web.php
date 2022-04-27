<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
Route::get('/', function () {
    return view('welcome');
});*/

Route::namespace("App\Http\Controllers\portal")->group(function(){
    Route::get('/', 'LoginController@index')->name('login');
    Route::post('/logar', 'LoginController@login')->name('logar');
    Route::get('/logout', 'LoginController@logout')->name('logout');
    
    Route::get('/cadastrar', 'CadastroController@index')->name('cadastrar');
    //Route::get('/ir_cadastro', 'LoginController@ir_cadastro');

    Route::get('/esqueceu-senha', 'ForgotPassController@index')->name('esqueceu-senha');

    Route::get('/dashboard', 'DashController@index')->name('dashboard');
    /* BAIAS */
    Route::get('/baias', 'BaiaController@index')->name('baias');
    Route::get('/baias/criar', 'BaiaController@create')->name('criar_baia');
    Route::post('/baias/salvar', 'BaiaController@store')->name('salvar_baia');
    Route::get('/baias/{slug}', [
        'uses' => 'BaiaController@show',
        'as' => 'show'
    ]);
    Route::post('/baias/update/{id}', [
        'uses' => 'BaiaController@update',
        'as' => 'update_baia'
    ]);
    Route::get('/baias/{id}/delete', [
        'uses' => 'BaiaController@destroy',
        'as' => 'delete_baia'
    ]);
    /* EQUIPAMENTOS */
    Route::get('/equipamentos', 'EquipamentoController@index')->name('equipamentos');
    Route::get('/equipamentos/adicionar', 'EquipamentoController@create')->name('add_equipamento');
    Route::post('/equipamento/salvar', 'EquipamentoController@store')->name('salvar_equipamento');
    
    Route::get('/equipamento/{id}', [
        'uses' => 'EquipamentoController@show',
        'as' => 'show_equipamento'
    ]);

    Route::post('/equipamento/update/{id}', [
        'uses' => 'EquipamentoController@update',
        'as' => 'update_equipamento'
    ]);

    Route::get('/equipamentos/{id}/delete', [
        'uses' => 'EquipamentoController@destroy',
        'as' => 'delete_equipamento'
    ]);
    
    /* MONITORES */
    Route::get('/monitores', 'MonitorController@index')->name('monitores');
    Route::get('/monitores/adicionar', 'MonitorController@create')->name('add_monitor');
    Route::post('/monitores/adicionar', 'MonitorController@searchMonitor')->name('searchMonitor');
    Route::post('/monitores/salvar', 'MonitorController@store')->name('salvar_monitor');
    Route::get('/monitor/{id}', [
        'uses' => 'MonitorController@show',
        'as' => 'show_monitor'
    ]);

    Route::post('/monitor/update/{id}', [
        'uses' => 'MonitorController@update',
        'as' => 'update_monitor'
    ]);

    Route::get('/monitor/{id}/delete', [
        'uses' => 'MonitorController@destroy',
        'as' => 'delete_monitor'
    ]);
    
    /* RESERVAS */
    Route::get('/reservas', 'ReservaController@index')->name('reservas');
    Route::get('/reservas/criar', 'ReservaController@create')->name('criarReserva');
    Route::post('/reservas/salvar', 'ReservaController@store')->name('agendar');
    Route::post('/verificar-baias', [
        'uses' => 'ReservaController@verificarBaias',
        'as' => 'verificar-baias'
    ]);
    Route::post('/reservas/update/{id}', [
        'uses' => 'ReservaController@update',
        'as' => 'atualizar_reserva'
    ]);

    Route::get('/reservas/{id}', [
        'uses' => 'ReservaController@show',
        'as' => 'show_reserva'
    ]);

    Route::get('/reservas/{id}/excluir', [
        'uses' => 'ReservaController@destroy',
        'as' => 'excluir_reserva'
    ]);

    Route::get('/reservas/{id}/cancelar', [
        'uses' => 'ReservaController@cancelar',
        'as' => 'cancelar_reserva'
    ]);

    Route::post('/rejeitar-reserva', [
        'uses' => 'ReservaController@rejeitar',
        'as' => 'rejeitar-reserva'
    ]);

    Route::post('/aceitar-reserva', [
        'uses' => 'ReservaController@aceitar',
        'as' => 'aceitar-reserva'
    ]);

    /* LOG */
    Route::get('/log', 'LogController@index')->name('log');
    Route::get('/log/{slug}', 'LogController@show');
});