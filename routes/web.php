<?php

use Illuminate\Support\Facades\Route;



Route::get('/', function() {
    //return "ok";
    return redirect()->route('admin.login.form');
    return view('admin.home');
});

Route::get('storage-link', function () {
    Artisan::call('storage:link');
    return 'Storage link created!';
});


Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
