<?php

use App\Http\Controllers\GeoDataController;
use Illuminate\Support\Facades\Route;


// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');


// Grouped for consistency and potential middleware (like rate limiting)
Route::controller(GeoDataController::class)->group(function () {
    Route::get('/countries', 'getCountries');
    Route::get('/states/{country}', 'getStates'); // Use Country model binding
    Route::get('/cities/{state}', 'getCities');   // Use State model binding
});
