<?php

use App\Http\Controllers\Api\Appointment\PaymentController;
use App\Http\Controllers\Api\BookAppointmentOnlineController;
use App\Http\Controllers\Api\CustomersController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth; 
use App\Http\Controllers\Api\AppointmentController;
use App\Http\Controllers\Api\Company\CompanyServicesController;
use App\Http\Controllers\Api\Company\CompanyTechController;

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
            $user = $request->user();
            $user->rolesArray = $user->roles->pluck('role');
            return response()->json(['user' => $user], 200);
        });

        Route::prefix('customers')->group(function(){
            Route::get("/",[CustomersController::class,'index']);
            Route::get('/{id}',[CustomersController::class,'show']);
            Route::post("/",[CustomersController::class,'store']);
            Route::put('/{id}',[CustomersController::class,'update']);
            Route::put('/{customer_id}/address/{address_id}',[CustomersController::class,'updateAddress']);
            Route::post('/{customer_id}/address',[CustomersController::class,'storeAddress']);
            Route::delete('/{customer_id}/address/{address_id}',[CustomersController::class,'deleteAddress']);
        });

        // Company Services
        Route::prefix('company/settings/services')->group(function(){
            Route::get('/',[CompanyServicesController::class,'index']);
            Route::post('/',[CompanyServicesController::class,'store']);
            Route::delete('/{id}',[CompanyServicesController::class,'delete']);
            Route::put('/{id}',[CompanyServicesController::class,'update']);
        });

        // Comopany Techs
        Route::prefix('company/techs')->group(function(){
            Route::get('/',[CompanyTechController::class,'index']);
            
        });

        Route::prefix('appointment')->group(function(){

            // All appointments for calendar
            Route::get('/',[AppointmentController::class,'view']);

            Route::get('/{id}',[AppointmentController::class,'index']);
            Route::put('/{id}/status',[AppointmentController::class,'updateStatus']);
            //Appointment Techs
            Route::delete('tech/{appointment_id}/{tech_id}',[AppointmentController::class,'removeTech']);
            Route::post('tech/{appointment_id}',[AppointmentController::class,'addTech']);
            
            // Appointment notes
            Route::post('notes/{appointment_id}',[AppointmentController::class,'addNote']);
            Route::delete('notes/{appointment_id}/{note_id}',[AppointmentController::class,'removeNote']);

            // Appointment services
            Route::post('service/{appointment_id}',[AppointmentController::class,'addService']);
            Route::delete('service/{appointment_id}/{service_id}',[AppointmentController::class,'removeService']);
            Route::put('service/{appointment_id}/{service_id}',[AppointmentController::class,'updateService']);

            // Appointment payments
            Route::post('payment/{appointment_id}',[PaymentController::class,'store']);
        });
    });

    Route::prefix('appointment/book')->group(function(){
        Route::get('/{key}',[BookAppointmentOnlineController::class,'index']);
        Route::post('/{key}',[BookAppointmentOnlineController::class,'store']);
        Route::get('/view/{providerkey}',[BookAppointmentOnlineController::class,'view']);
        Route::get('/remove/{providerkey}',[BookAppointmentOnlineController::class,'remove']);
    });
});

// Route::middleware('auth:sanctum')->get('/v1/user', function (Request $request) {
//     return $request->user();
// });
