<?php
use App\Http\Controllers\Api\Appointment\PaymentController;
use App\Http\Controllers\Api\BookAppointmentOnlineController;
use App\Http\Controllers\Api\CustomersController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AppointmentController;
use App\Http\Controllers\Api\AppointmentImages;
use App\Http\Controllers\Api\Company\CompanyServicesController;
use App\Http\Controllers\Api\Company\CompanyTechController;
use App\Http\Controllers\Api\InvoiceController;
use App\Http\Controllers\Api\Auth\AuthController;
use App\Http\Controllers\Api\Company\BookAppointmentController;
use App\Http\Controllers\Api\DashboardController;
use App\Http\Controllers\Api\EmployeeController;
use App\Http\Controllers\Api\Job\JobNotesController;
use App\Http\Controllers\Api\Job\ExpenseController;
use App\Http\Controllers\Api\Job\JobServicesController;
use App\Http\Controllers\Api\ProfileController;
use App\Http\Controllers\Api\ReviewFeedbackController;
use App\Http\Controllers\Api\StorageItemsController;

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


Route::prefix('v1')->group(function () {
    Route::post('/signin', [AuthController::class, 'login']);

    Route::group(['middleware' => ['auth:sanctum']], function () {


        // Profile
        Route::get('user', [ProfileController::class, 'show']);
        Route::post('user/update-password', [ProfileController::class, 'updatePassword']);

        //Dashboard
        Route::get('dashboard', [DashboardController::class, 'dashboard']);

        // Customers
        Route::prefix('customers')->group(function () {
            Route::get("/", [CustomersController::class, 'index']);
            Route::get('/{id}', [CustomersController::class, 'show'])->where('id', '[0-9]+');
            Route::post("/", [CustomersController::class, 'store']);
            Route::put('/{id}', [CustomersController::class, 'update']);
            Route::put('/{customer_id}/address/{address_id}', [CustomersController::class, 'updateAddress']);
            Route::post('/{customer_id}/address', [CustomersController::class, 'storeAddress']);
            Route::delete('/{customer_id}/address/{address_id}', [CustomersController::class, 'deleteAddress']);
        });


        // Company settings
        Route::prefix('company/settings')->group(function () {

            //Services
            Route::prefix('services')->group(function () {
                Route::get('/', [CompanyServicesController::class, 'index']);
                Route::post('/', [CompanyServicesController::class, 'store']);
                Route::delete('/{id}', [CompanyServicesController::class, 'delete']);
                Route::put('/{id}', [CompanyServicesController::class, 'update']);
            });

            // Book Appointment online
            Route::prefix('book-appointment')->group(function () {
                Route::get('/', [BookAppointmentController::class, 'index']);
                Route::post('/working-time', [BookAppointmentController::class, 'workingTime']);
                Route::post('/update', [BookAppointmentController::class, 'update']);
                Route::post('/update-services', [BookAppointmentController::class, 'updateServices']);
            });

        });

        // Comopany Techs
        Route::prefix('company/techs')->group(function () {
            Route::get('/', [CompanyTechController::class, 'index']);
        });


        Route::prefix('appointment')->group(function () {

            // All appointments for calendar
            Route::get('/', [AppointmentController::class, 'view']);

            // Base appointment API
            Route::get('/{id}', [AppointmentController::class, 'index']);
            Route::post('/', [AppointmentController::class, 'store']);
            Route::delete('/{id}', [AppointmentController::class, 'delete']);
            Route::put('/{id}', [AppointmentController::class, 'update']);

            // Update Appointment Status
            Route::put('/{id}/status', [AppointmentController::class, 'updateStatus']);
            //Appointment Techs
            Route::delete('tech/{appointment_id}/{tech_id}', [AppointmentController::class, 'removeTech']);
            Route::post('tech/{appointment_id}', [AppointmentController::class, 'addTech']);

            // Job notes
            Route::post('notes/{jobId}', [JobNotesController::class, 'store']);
            Route::delete('notes/{noteId}', [JobNotesController::class, 'delete']);

            // job services
            Route::post('service/{job_id}', [JobServicesController::class, 'store']);
            Route::delete('service/{job_id}/{service_id}', [JobServicesController::class, 'destroy']);
            Route::put('service/{job_id}/{service_id}', [JobServicesController::class, 'update']);

            // job payments
            Route::post('payment/{job_id}', [PaymentController::class, 'store']);

            // Appointment invoice
            Route::get('invoice/{appointment_id}', [InvoiceController::class, 'create']);
            Route::post('{appointment_id}/invoice-send', [InvoiceController::class, 'send']);

            // Appointment images
            Route::post('images/{appointment_id}', [AppointmentImages::class, 'store']);
            Route::get('images/{appointment_id}', [AppointmentImages::class, 'index']);

            // Job expances
            Route::post('expense/{job_id}', [ExpenseController::class, 'store']);
            Route::delete('expense/{expense_id}', [ExpenseController::class, 'delete']);
        });

        // Invoices
        Route::prefix('invoice')->group(function () {
            Route::get('/', [InvoiceController::class, 'index']);
            Route::get('/{id}', [InvoiceController::class, 'show']);
            Route::get('/download/{appointment_id}', [InvoiceController::class, 'download']);
        });

        // Employees
        Route::prefix('employees')->group(function () {
            Route::get('/', [EmployeeController::class, 'index']);
            Route::post('/', [EmployeeController::class, 'store']);
            Route::put('/{id}', [EmployeeController::class, 'update']);
        });

        // Payments
        Route::prefix('payments')->group(function () {
            Route::get('/', [PaymentController::class, 'index']);
            Route::delete('/{id}', [PaymentController::class, 'delete']);
        });

        // Storage
        Route::prefix('storage')->group(function () {
            Route::get('/', [StorageItemsController::class, 'index']);
            Route::post('/', [StorageItemsController::class, 'store']);
            Route::put('/{id}', [StorageItemsController::class, 'update']);
            Route::delete('/{id}', [StorageItemsController::class, 'destroy']);
        });
        
    });

    Route::prefix('appointment/book')->group(function () {
        Route::get('/{key}', [BookAppointmentOnlineController::class, 'index']);
        Route::post('/{key}', [BookAppointmentOnlineController::class, 'store']);
        Route::get('/view/{providerkey}', [BookAppointmentOnlineController::class, 'view']);
        Route::get('/remove/{providerkey}', [BookAppointmentOnlineController::class, 'remove']);
    });

    Route::prefix('review-feedback')->group(function () {
        Route::get('/{key}', [ReviewFeedbackController::class, 'view']);
        Route::post('/{key}', [ReviewFeedbackController::class, 'store']);
    });
});
