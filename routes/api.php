<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\AppAdminAuthController;
use App\Http\Controllers\Api\Admin\AuthController;
use App\Http\Controllers\Api\User\AuthControllerUser;

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

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

// auth:
Route::group(['middleware'=>['api','checkPassword','checkLanguage']], function () {
    Route::post('get-main-category', [CategoryController::class, 'index']);
    Route::post('get-category-ById', [CategoryController::class,'getCategory']);
    Route::post('change-status', [CategoryController::class,'changeStatus']);
    Route::group(['prefix'=>'admin','namespace'=>'admin'], function(){
        Route::post('login', [AuthController::class,'login']);
        Route::post('logout', [AuthController::class,'logout'])->middleware('auth.guard:admin-api');
    });

    Route::group(['prefix'=>'user','namespace'=>'user'],function(){
        Route::post('login',[AuthControllerUser::class,'login']);

    });

    // Route::group(['prefix' => 'user', 'middleware' => 'auth.guard:user-api'], function(){
    //     Route::post('profile', function(){
    //         return 'only users accessible';
    //     })->middleware('auth.guard:user-api');
    // });

    Route::group(['prefix' => 'user' ,'middleware' => 'auth.guard:user-api'],function (){
        Route::post('profile',function(){
            return  Auth::user(); // return authenticated user data
        }) ;

        Route::post('logout', [AuthControllerUser::class,'logout']);

     });

});




Route::group(['middlewre'=>['api','checkPassword','checkLanguage','checkAdminToken:admin-api']], function(){
    Route::get('offers',[CategoryController::class,'test']);
});


