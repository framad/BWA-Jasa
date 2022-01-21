<?php

use Illuminate\Support\Facades\Route;
//manggil controller landingcontroller
use App\Http\Controllers\Landing\LandingController;

//manggil controller di controller dashboard
use App\Http\Controllers\Dashboard\MemberController;
use App\Http\Controllers\Dashboard\MyOrderController;
use App\Http\Controllers\Dashboard\ProfileController;
use App\Http\Controllers\Dashboard\RequestController;
use App\Http\Controllers\Dashboard\ServiceController;
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

//bikin route untuk landing pages yang bisa diakses tanpa login
//kenapa get karena untuk mendapatkan halaman
//kenapa untuk booking dll ada {id} nya, karena untuk akses halaman booking/detail/detail/booking
//diperlukan id dari si apa apa yang dimunculkan tersebut
Route::get('explore', LandingController::class, 'explore')->name('explore.landing');
Route::get('booking/{id}', LandingController::class, 'booking')->name('booking.landing');
Route::get('detail/{id}', LandingController::class, 'detail')->name('detail.landing');
Route::get('detail_booking/{id}', LandingController::class, 'detail_booking')->name('detail.booking.landing');
Route::resource('/', LandingController::class);

//bikin route untuk dashboard yang hanya bisa diakses jika sudah login
Route::group(['prefix' => 'member', 'as' => 'member', 'middleware' => ['auth:sanctum', 'verified']],
function(){
    //dashboard
    Route::resource('dashboard', MemberController::class);

    //service
    Route::resource('service', ServiceController::class);

    //request
    Route::get('approve_request/{id}', RequestController::class, 'approve')->name('approve_request');
    Route::resource('request', RequestController::class);

    //my order
    Route::get('accept/order/{id}', MyOrderController::class, 'accepted')->name('accept.order');
    Route::get('reject/order/{id}', MyOrderController::class, 'rejected')->name('reject.order');
    Route::resource('order', MyOrderController::class);

    //profile
    Route::get('delete_photo', ProfileController::class, 'delete')->name('delete.photo.profile');
    Route::resource('profile', ProfileController::class);
});


// Route::get('/', function () {
//     return view('welcome');
// });

// Route::middleware(['auth:sanctum', 'verified'])->get('/dashboard', function () {
//     return view('dashboard');
// })->name('dashboard');
