<?php

use App\Http\Controllers\Api\CustomersController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth; 
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


Route::prefix('v1')->group(function (){
    Route::post('/signin',function(Request $request){
        if(Auth::attempt(['email' => $request->email, 'password' => $request->password])){ 
            $user = Auth::user();
            $user->tokens()->delete();
            $success['token'] =  $user->createToken('API token')->plainTextToken;
            return response()->json(['success' => $success], 200); 
        } 
        else
        { 
            return response()->json(['error'=>'Unauthorised'], 401); 
        } 
    });

    Route::group(['middleware' => ['auth:sanctum']],function (){
        Route::get('user', function(Request $request){
            return $request->user();
        });

        Route::prefix('customers')->group(function(){
            Route::get("/",[CustomersController::class,'index']);
        });
    });

    Route::get('/test', function(){
        return response()->json(['name'=>'test'],200);
    });

});



// Route::middleware('auth:sanctum')->get('/v1/user', function (Request $request) {
//     return $request->user();
// });
