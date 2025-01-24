<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CompanyController;
use Illuminate\Support\Facades\Auth;

Route::get('/', function () {
    return view('welcome');
});


Route::get('/test', function () {
   return Auth::attempt(['email' => "user@example.com", 'password' => 'password']);
});

//Route::get('/companies', [CompanyController::class, 'index']);

Route::resource('companies', CompanyController::class);

// Route::get('/companies', function () {
//     return 'Hello World';
// });