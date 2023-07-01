<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Services\DuckService;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::get('/status', function (Request $request, DuckService $duckService) {    
    $status = $duckService->getStatus();    
    unset($status['_lastTime']);
    
    return $status;
});
