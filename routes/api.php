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


Route::get('/companies', 'CompaniesController@index');
Route::get('/companies/{id}', 'CompaniesController@show');
Route::post('/companies', 'CompaniesController@store');
Route::delete('/companies/{id}', 'CompaniesController@destroy');

Route::post('/employees', 'EmployeesController@store');
Route::delete('/employees/{id}', 'EmployeesController@destroy');
