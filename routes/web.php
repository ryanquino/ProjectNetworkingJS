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

Route::get('/', function () {
    return view('welcome');
});

Route::group(['middleware' => ['auth:sanctum', 'verified']], function ()
{
	Route::get('/dashboard', function () {
	    return view('dashboard');
	})->name('dashboard');

	Route::get('/payin', function () {
    	return view('user.payin');
	})->name('payin');

	Route::get('/payouts', function () {
		return view('user.payout');
	})->name('payout');

	Route::get('/help', function () {
		return view('user.help');
	})->name('help');

	Route::get('/testimonials', function () {
		return view('user.testimonials');
	})->name('testimonials');

	Route::get('/referrals', function () {
		return view('user.referrals');
	})->name('referrals');

	Route::get('/about', function () {
	    return view('user.about');
	})->name('about');
	
});
