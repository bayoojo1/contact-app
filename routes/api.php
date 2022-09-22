<?php

use App\Http\Controllers\API\ContactController;
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


//Define an api route like below:
Route::apiResource('/contacts', ContactController::class);

//You can check the route with the command:
//=>php artisan route:list --path=api

//We can also register some apiResources as one, by defining the apiResources method, and passing an associative array containing our controllers along with the paths like so:
Route::apiResources([
    '/contacts' => ContactController::class
    // another controller...
]);


