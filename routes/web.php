<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Models\Role;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\InventoryController;
use App\Http\Controllers\InventoryCategoryController;
use \App\Http\Controllers\CustomerController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::prefix("auth")->group(function(){
    Route::get("/register",[RegisterController::class,'create']);
    Route::post("/register", [RegisterController::class,"store"]);
    Route::get("/login", [LoginController::class,'view'])->name('login');
    Route::post("/login", [LoginController::class,'login']);
    Route::get('/logout', [LoginController::class,'destroy']);
});

Route::group(['middleware' => ['auth','active']],function (){
   Route::get('/',function(){
       //return "success";
       return view('dashboard');
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
   });

});
