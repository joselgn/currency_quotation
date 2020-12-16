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
*/

Route::get('/', 'HomeController@index');

Route::post('currency-quote', ['as' => 'currency-quote', 'uses' => 'HomeController@getQuotation']);

Route::post('currency-quote-period', ['as' => 'currency-period',  'uses' => 'HomeController@getQuotationByPeriods']);
