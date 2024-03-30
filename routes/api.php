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
use App\Http\Controllers\Api\InvoiceController;
use App\Http\Controllers\Api\Auth\AuthController;
use App\Http\Controllers\Api\EmployeeController;
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
    Route::post('/signin',[AuthController::class,'login']);

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

            // Base appointment API
            Route::get('/{id}',[AppointmentController::class,'index']);
            Route::post('/',[AppointmentController::class,'store']);

            // Update Appointment Status
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

            // Appointment invoice
            Route::get('invoice/{appointment_id}',[InvoiceController::class,'create']);
            Route::post('{appointment_id}/invoice-send',[InvoiceController::class,'send']);
        });

        // Invoices
        Route::prefix('invoice')->group(function(){
            Route::get('/',[InvoiceController::class,'index']);
            Route::get('/{id}',[InvoiceController::class,'show']);
        });

        // Employees
        Route::prefix('employees')->group(function(){
            Route::get('/',[EmployeeController::class,'index']);
            Route::post('/',[EmployeeController::class,'store']);
            Route::put('/{id}',[EmployeeController::class,'update']);
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
