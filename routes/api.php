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
Route::post('register', 'Api\Auth\RegisterController@register');
Route::post('sendPasswordResetLink', 'Api\Auth\ResetPasswordController@sendEmail');
Route::post('resetPassword',         'Api\Auth\ChangePasswordController@process');


/**
 * Contactos Routes
 */
Route::get('contactos',         'Api\ContactosController@index');
Route::post('contactos',        'Api\ContactosController@store');
Route::get('contactos/{id}',    'Api\ContactosController@show');
Route::put('contactos/{id}',    'Api\ContactosController@update');
Route::delete('contactos/{id}', 'Api\ContactosController@destroy');

/**
 * Contactos Routes
 */
Route::get('funcionarios',         'Api\FuncionariosController@index');
Route::post('funcionarios',        'Api\FuncionariosController@store');
Route::get('funcionarios/{id}',    'Api\FuncionariosController@show');
Route::put('funcionarios/{id}',    'Api\FuncionariosController@update');
Route::delete('funcionarios/{id}', 'Api\FuncionariosController@destroy');