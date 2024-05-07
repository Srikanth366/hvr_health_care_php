<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Mail\Mailable;
use Illuminate\Support\Facades\Mail;
use App\Mail\OrderShippedMail;
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

Route::get('/', function () {
   //return view('recoverypassword');
  // Mail::to('ramthahvr@gmail.com')->send(new OrderShippedMail());
  return view('welcome');
  //return view('welcomeemail');
});

Route::get('/create-storage-link', function () {
    Artisan::call('key:generate');
    Artisan::call('config:clear');
    Artisan::call('cache:clear');
    Artisan::call('route:clear');
    Artisan::call('storage:link');
    return 'Storage link created successfully!';
});