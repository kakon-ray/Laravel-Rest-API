<?php

use App\Http\Controllers\User\UserGuest\UserGuestController;
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

Route::post('store-data', [UserGuestController::class, 'store']);
Route::delete('delete/{id}', [UserGuestController::class, 'delete']);
Route::get('get-data', [UserGuestController::class, 'get_data']);


Route::put('update-user-all-data', [UserGuestController::class, 'update_data']);
Route::patch('password-change', [UserGuestController::class, 'password_change']);

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
