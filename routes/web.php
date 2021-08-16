<?php

use App\Http\Controllers\ChatController;
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

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth'])->name('dashboard');

Route::get('/chat', [ChatController::class, 'show'])->middleware(['auth']);
Route::post('sendmessage', [ChatController::class, 'sendMessage']);
Route::post('/removeMessage', [ChatController::class, 'remove']);

Route::get('/messages',[ChatController::class,'fetchMessages'])->middleware('auth');
require __DIR__.'/auth.php';
