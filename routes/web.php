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
Route::get('dashboard', function (){
	return view('dashboard');
})->name('dashboard');
Route::resource('user', \App\Http\Controllers\UserController::class);
Route::resource('department', \App\Http\Controllers\DepartmentController::class);
Route::resource('subject', \App\Http\Controllers\SubjectController::class);
Route::resource('book', \App\Http\Controllers\BookController::class);
Route::resource('rent_book', \App\Http\Controllers\RentBookController::class);
Route::resource('subject_join_book', \App\Http\Controllers\SubjectJoinBookController::class);
