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

Route::get('/confession', 'PaymentController@displayPaymentWindow');
Route::get('/confession/fine/{id?}', 'PaymentController@displayPaymentWindow');
Route::get('/process-payment', 'PaymentController@processPaymentRequest');
