<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;

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
    Route::post('update', 'update');
    Route::post('resetpwd', 'resetpwd');
});
        
Route::group([], function ($router) {

    //Project
    Route::post('projects', [App\Http\Controllers\ProjectController::class, 'store'])->name("store_projects");

    Route::get('projects', [App\Http\Controllers\ProjectController::class, 'index'])->name("get_projects");

    Route::get('projects/select/{id}', [App\Http\Controllers\ProjectController::class, 'select'])->name("projects_select");

    Route::get('projects/{id}', [App\Http\Controllers\ProjectController::class, 'get'])->name("projects_get");

    Route::put('projects/update/{id}', [App\Http\Controllers\ProjectController::class, 'update'])->name("projects_update");

    //Countries
    Route::get('countries', [App\Http\Controllers\CountryController::class, 'countries'])->name("countries");

    //Currencies
    Route::get('currencies', [App\Http\Controllers\CurrencyController::class, 'currencies'])->name("currencies");

    //Timezones
    Route::get('timezones', [App\Http\Controllers\TimezoneController::class, 'timezones'])->name("timezones");

    //Product Import
    Route::post('xml-import', [App\Http\Controllers\ProductController::class, 'xml'])->name("xml-import");

    Route::post('save-import-data', [App\Http\Controllers\ProductController::class, 'save'])->name("save-import-data");

    //XmlFields Import
    Route::get('xml-fields', [App\Http\Controllers\XmlFieldController::class, 'xmlFields'])->name("xmlFields");

});
