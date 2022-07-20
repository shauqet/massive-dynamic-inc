<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\UsersController;
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
    return view('auth.login');
});

Auth::routes(['register' => false]);

Route::group(["prefix" => "admin", "middleware" => "auth"], function () {
    Route::get('/', [AdminController::class, 'index']);
    Route::get("/admin-info", [AdminController::class, 'getAdminInfo']);

    // Users
    Route::resource('users', UsersController::class);
    Route::get('/users/{searchKeyword}/search', [UsersController::class, 'getSearched']);
    Route::get('/all-users', [UsersController::class, 'allUsers']);
    Route::get('/user', [UsersController::class, 'user']);
});
