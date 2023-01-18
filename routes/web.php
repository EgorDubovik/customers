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
use  App\Http\Controllers\ServiceController;

Route::prefix("auth")->group(function(){
    Route::get("/register",[RegisterController::class,'create']);
    Route::post("/register", [RegisterController::class,"store"]);
    Route::get("/login", [LoginController::class,'view'])->name('login');
    Route::post("/login", [LoginController::class,'login']);
    Route::get('/logout', [LoginController::class,'destroy']);
});

Route::group(['middleware' => ['auth','active']],function (){
   Route::get('/',function(){
       //return view('dashboard');
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
    });
});
