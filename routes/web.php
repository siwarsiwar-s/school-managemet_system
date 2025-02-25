<?php

use App\Http\Controllers\AdminController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Backend\UserController ;
use Database\Factories\UserFactory;
use App\Http\Controllers\Backend\ProfileController ;

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

Route::get('/', function () {
    return view('auth.login');
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified'
])->group(function () {
    Route::get('/dashboard', function () {
        return view('admin.index');
    })->name('dashboard');
});

Route::post('/admin/logout',[AdminController::class, 'logout'])->name('admin.logout');
//user management
Route::get('/user/view',[UserController::class, 
'UserView'])->name('user.view');

Route::prefix('users')->group(function(){
    Route::get('/view',[UserController::class, 'UserView'])->name('user.view');
    Route::get('/add',[UserController::class, 'UserAdd'])->name('user.add');
    Route::post('/store',[UserController::class,
     'UserStore'])->name('users.store');
     Route::get('/edit/{id}',[UserController::class,
      'UserEdit'])->name('users.edit');
      Route::get('/update/{id}',[UserController::class,
      'UserUpdate'])->name('users.update');
      Route::get('/delete/{id}',[UserController::class,
      'UserDelete'])->name('users.delete');
   
});

//user profile and password


Route::prefix('profile')->group(function(){
    Route::get('/view',[ProfileController::class, 
    'ProfileView'])->name('profile.view');
    Route::get('/edit',[ProfileController::class, 
    'ProfileEdit'])->name('profile.edit');
  
    Route::post('/store',[ProfileController::class, 
    'ProfileStore'])->name('profile.store');

    Route::get('/password/view',[ProfileController::class, 
    'PasswordView'])->name('password.view');

    Route::post('/password/update',[ProfileController::class, 
    'PasswordUpdate'])->name('password.update');
});
