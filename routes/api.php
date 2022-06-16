<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\ProjectController;

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

Route::controller(AuthController::class)->group(function () {
    Route::post('login', 'login');
    Route::post('register', 'register');
    Route::post('logout', 'logout');
    Route::post('refresh', 'refresh');

});
        
Route::group(['middleware' => 'jwt.verify'], function ($router) {

    //Project
    Route::post('projects', 'ProjectController@store');
    Route::get('projects', 'ProjectController@index');
    Route::get('projects/select/{id}', 'ProjectController@select');
    Route::get('projects/{id}', 'ProjectController@get');
    Route::put('projects/update/{id}', 'ProjectController@update');

    //Countries
    Route::get('countries', 'CountryController@countries');

    //Currencies
    Route::get('currencies', 'CurrencyController@currencies');

    //Timezones
    Route::get('timezones', 'TimezoneController@timezones');

    //Product Import
    Route::post('xml-import', 'ProductController@xml');
    Route::post('save-import-data', 'ProductController@save');

    //XmlFields Import
    Route::get('xml-fields', 'XmlFieldController@xmlFields');

});
