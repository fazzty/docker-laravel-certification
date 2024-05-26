<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\Admin\BookController;

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

Route::get('messages', [MessageController::class, 'index']);
Route::post('messages', [MessageController::class, 'store']);

Route::prefix('admin/books')
    ->name('book.')
    ->controller(BookController::class)
    ->group(function() {
        Route::get('', 'index')->name('index');
        Route::get('{id}', 'show')
                ->whereNumber('id')
                ->name('show');
        Route::get('create', 'create')->name('create');
        Route::post('', 'store')->name('store');     
    });