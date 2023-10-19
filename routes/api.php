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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
Route::apiResource('user', \App\Http\Controllers\API\V1\UserController::class);
Route::apiResource('department', \App\Http\Controllers\API\V1\DepartmentController::class);
Route::apiResource('subject', \App\Http\Controllers\API\V1\SubjectController::class);
Route::apiResource('book', \App\Http\Controllers\API\V1\BookController::class);
Route::apiResource('rent_book', \App\Http\Controllers\API\V1\RentBookController::class);

Route::post('login', [\App\Http\Controllers\API\V1\AuthController::class, 'loginEmail']);
Route::get('get-book', [\App\Http\Controllers\API\V1\OrderContoller::class, 'getBook']);
Route::get('get-book/{id}', [\App\Http\Controllers\API\V1\OrderContoller::class, 'show']);
Route::get('read-book', [\App\Http\Controllers\API\V1\OrderContoller::class, 'readBook']);
Route::get('existing-book', [\App\Http\Controllers\API\V1\OrderContoller::class, 'existingBook']);
Route::post('qr-code', [\App\Http\Controllers\API\V1\OrderContoller::class, 'qrCode']);


Route::get('get-student', [\App\Http\Controllers\API\V1\Admin\StudentController::class, 'index']);
Route::post('scanner', [\App\Http\Controllers\API\V1\Admin\ScannerController::class, 'check']);

