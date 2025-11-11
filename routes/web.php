<?php


use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Http\Middleware\PreventBackHistoryMiddleware;
use App\Http\Controllers\Backend\CategoryController;


//Backend routes
Route::get('/login', [LoginController::class, 'login'])->name('admin.login');
Route::post('/login', [LoginController::class, 'authenticate'])->name('admin.authenticate');
Route::get('/logout', [LoginController::class, 'logout'])->name('admin.logout');
Route::get('/change-password', [LoginController::class, 'change_password'])->name('admin.changepassword');
Route::post('/update-password', [LoginController::class, 'updatePassword'])->name('admin.updatepassword');

Route::get('/register', [LoginController::class, 'register'])->name('admin.register');
Route::post('/register', [LoginController::class, 'authenticate_register'])->name('admin.register.authenticate');
 
Route::prefix('admin')->name('admin.')
        ->middleware(['auth:web', PreventBackHistoryMiddleware::class])
        ->group(function () {
            
            Route::get('/dashboard', function () {
                return view('backend.dashboard');
            })->name('dashboard');

            Route::resource('category', CategoryController::class);
            
        });

//frontend routes
Route::get('/', function () {
    return view('welcome');
});
