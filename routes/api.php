<?php

use Illuminate\Http\Request;

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

/**
 * User Auth Routes
 */
Route::post('login',    'Api\Auth\LoginController@login');
Route::post('logout',   'Api\Auth\LoginController@logout');
Route::post('refresh',  'Api\Auth\LoginController@refresh');
Route::post('me',       'Api\Auth\LoginController@me');
// Route::post('register', 'Api\Auth\RegisterController@register');
Route::post('sendPasswordResetLink', 'Api\Auth\ResetPasswordController@sendEmail');
Route::post('resetPassword',         'Api\Auth\ChangePasswordController@process');


/**
 * Usuarios Routes
 */
Route::get('usuarios',         'Api\UsuariosController@index');
Route::post('usuarios',        'Api\UsuariosController@store');
Route::get('usuarios/{id}',    'Api\UsuariosController@show');
Route::put('usuarios/{id}',    'Api\UsuariosController@update');
Route::delete('usuarios/{id}', 'Api\UsuariosController@destroy');


/**
 * Contactos Routes
 */
Route::get('contactos',         'Api\ContactosController@index');
Route::post('contactos',        'Api\ContactosController@store');
Route::get('contactos/{id}',    'Api\ContactosController@show');
Route::put('contactos/{id}',    'Api\ContactosController@update');
Route::delete('contactos/{id}', 'Api\ContactosController@destroy');

/**
 * Funcionarios Routes
 */
Route::get('funcionarios',         'Api\FuncionariosController@index');
Route::post('funcionarios',        'Api\FuncionariosController@store');
Route::get('funcionarios/{id}',    'Api\FuncionariosController@show');
Route::put('funcionarios/{id}',    'Api\FuncionariosController@update');
Route::delete('funcionarios/{id}', 'Api\FuncionariosController@destroy');

/**
 * Vans Routes
 */
Route::get('vans',         'Api\VansController@index');
Route::post('vans',        'Api\VansController@store');
Route::get('vans/{id}',    'Api\VansController@show');
Route::put('vans/{id}',    'Api\VansController@update');
Route::delete('vans/{id}', 'Api\VansController@destroy');

/**
 * Escala Routes
 */
Route::get('escalas',         'Api\EscalasController@index');
Route::post('escalas',        'Api\EscalasController@store');
Route::get('escalas/{id}',    'Api\EscalasController@show');
Route::put('escalas/{id}',    'Api\EscalasController@update');
Route::delete('escalas/{id}', 'Api\EscalasController@destroy');

/**
 * Funcionarios Escala Routes
 */
Route::get('funcionarios-escalas',        'Api\FuncionarioEscalaController@index');
Route::post('funcionarios-escalas',        'Api\FuncionarioEscalaController@store');
Route::get('funcionarios-escalas/{id}',    'Api\FuncionarioEscalaController@show');
Route::put('funcionarios-escalas/{id}',    'Api\FuncionarioEscalaController@update');
Route::delete('funcionarios-escalas/{id}', 'Api\FuncionarioEscalaController@destroy');

Route::get('funcionarios-escalas/escala/dia', 'Api\FuncionarioEscalaController@escala_do_dia');


/**
 * Feedbacks Routes
 */
Route::get('feedbacks',         'Api\FeedBacksController@index');
Route::post('feedbacks',        'Api\FeedBacksController@store');
Route::get('feedbacks/{id}',    'Api\FeedBacksController@show');
Route::put('feedbacks/{id}',    'Api\FeedBacksController@update');
Route::delete('feedbacks/{id}', 'Api\FeedBacksController@destroy');

/**
 * Localizações Routes
 */
Route::get('localizacoes',         'Api\LocalizacoesController@index');
Route::post('localizacoes',        'Api\LocalizacoesController@store');
Route::get('localizacoes/{id}',    'Api\LocalizacoesController@show');
Route::put('localizacoes/{id}',    'Api\LocalizacoesController@update');
Route::delete('localizacoes/{id}', 'Api\LocalizacoesController@destroy');

/**
 * Permissões
 */

Route::resource('permissoes', 'Api\Authorization\PermissionController');
Route::resource('papeis', 'Api\Authorization\RoleController');