<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Models\Role;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
use \App\Http\Controllers\CustomerController;
use App\Http\Controllers\TagController;
use App\Http\Controllers\SettingsConstroller;
use App\Http\Controllers\NoteController;
use App\Http\Controllers\UploadController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\InvoiceController;
use  App\Http\Controllers\ServiceController;
use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\AppointmentNotesController;
use App\Http\Controllers\AppointmentServiceController;
use App\Models\Appointment;
use App\Models\AppointmentService;

Route::prefix("auth")->group(function(){
    Route::get("/register",[RegisterController::class,'create']);
    Route::post("/register", [RegisterController::class,"store"]);
    Route::get("/login", [LoginController::class,'view'])->name('login');
    Route::post("/login", [LoginController::class,'login']);
    Route::get('/logout', [LoginController::class,'destroy']);
});

Route::group(['middleware' => ['auth','active']],function (){
   Route::get('/',function(){
       return redirect()->route('customer.list');
   });

   Route::prefix('profile')->group(function (){
      Route::get('/',[ProfileController::class, 'index'])->name('profile');
      Route::post('/edit',[ProfileController::class, 'update']);
      Route::post('/change/password', [ProfileController::class,'change_password']);
   });

   Route::prefix('users')->group(function (){
       Route::get('/', [UserController::class, 'list'])->name('users');
       Route::get('/create', [UserController::class, 'create']);
       Route::post('/create', [UserController::class, 'store']);
       Route::get('/update/{user}', [UserController::class, 'edit']);
       Route::post('/update/{user}', [UserController::class, 'update']);
       Route::delete('/deactivate/{user}', [UserController::class,'destroy']);
   });

   Route::prefix('customer')->group(function(){
       Route::get('/' , [CustomerController::class, 'index'])->name('customer.list');
       Route::get('/create', [CustomerController::class, 'create'])->name('customer.create');
       Route::post('/store', [CustomerController::class, 'store'])->name('customer.store');
       Route::get('/show/{customer}', [CustomerController::class, 'show'])->name('customer.show');
       Route::get('/edit/{customer}' , [CustomerController::class, 'edit'])->name('customer.edit');
       Route::post('/update/{customer}', [ CustomerController::class, 'update'])->name('customer.update');
   });

   Route::prefix('tag')->group(function (){
       Route::post('store', [TagController::class,'store'])->name('tag.store');
       Route::post('assign/{customer}', [TagController::class,'assign_tag'])->name('tag.assign');
       Route::get('untie/{customer}/{tag}', [TagController::class, 'untie_tag'])->name('tag.untie');
       Route::get('delete/{tag}', [TagController::class, 'delete'])->name('tag.delete');
   });

    Route::get('settings',[SettingsConstroller::class, 'show']);
    // Notes
    Route::post('note/store/{customer}', [NoteController::class, 'store'])->name('note.store');
    // Images
    Route::post('/images/upload/{customer}', [UploadController::class,'store'])->name('image.store');
    Route::get('{image}/storage/images/{filename}', [UploadController::class,'view'])->name('image.view');
    Route::get('/images/delete/{image}', [UploadController::class, 'delete'])->name('image.delete');

    // Company
    Route::get('company/edit/{company}' , [CompanyController::class, 'edit'])->name('company.edit');
    Route::post('company/update/{company}', [CompanyController::class,'update'])->name('company.update');

    // Items
    Route::prefix('services')->group(function (){
        Route::get('index', [ServiceController::class, 'index'])->name('service.index');
        Route::get('create',[ServiceController::class, 'create'])->name('service.create');
        Route::delete('delete/{service}', [ServiceController::class, 'destroy'])->name('service.delete');
        Route::post('update/{service}', [ServiceController::class, 'update'])->name('service.update');
        Route::post('store', [ServiceController::class, 'store'])->name('service.store');
        Route::get('edit/{service}', [ServiceController::class , 'edit'])->name('service.edit');
    });

    //Schedule
    Route::prefix('schedule')->group(function (){
        Route::get('/',[AppointmentController::class,'index'])->name('schedule.index');
        Route::get('create', [AppointmentController::class, 'create'])->name('schedule.create');
        Route::post('store', [AppointmentController::class, 'store'])->name('schedule.store');
        Route::get('appointment/{appointment}', [AppointmentController::class, 'show'])->name('appointment.show');
        Route::get('edit/{appointment}', [AppointmentController::class,'edit'])->name('appointment.edit');
        Route::post('edit/{appointment}', [AppointmentController::class, 'update'])->name('appointment.update');
        Route::get('appointment/tech/remove/{appointment}/{user}', [AppointmentController::class, 'removeTech'])->name('appointment.remove.tech');
        Route::get('viewall/{customer}', [AppointmentController::class, 'viewall'])->name('appointment.viewall');
        Route::post('appointment/note/store/{appointment}', [AppointmentNotesController::class, 'store'])->name('appointment.note.store');
        Route::post('appointment/add-service/{appointment}', [AppointmentServiceController::class, 'store'])->name('appointment.add.serivce');
        Route::get('appointment/remove-service/{appointmentService}', [AppointmentServiceController::class, 'delete'])->name('appointment.service.remove');
        Route::get('appointment/status/{appointment}',[AppointmentController::class,'change_status'])->name('appointment.change_status');
    });

    // Invoices
    Route::prefix('invoice')->group(function(){
        Route::get('/', [InvoiceController::class, 'index'])->name('invoice.index');
        Route::get('create', [InvoiceController::class, 'create'])->name('invoice.create');
        Route::post('store', [InvoiceController::class, 'store'])->name('invoice.store');
        Route::get('/view/{invoice}', [InvoiceController::class,'show'])->name('invoice.show');
        
        Route::post('/resend/{invoice}', [InvoiceController::class,'resend'])->name('invoice.resend');
    });
});
Route::get('invoice/pdf/view/{key}',[InvoiceController::class,'viewPDF'])->name('invoice.view.PDF');
