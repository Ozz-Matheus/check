<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect('/dashboard');
});

Route::get('clear', function () {

    Artisan::call('config:cache');
    Artisan::call('view:clear');
    Artisan::call('route:clear');
    Artisan::call('cache:clear');
    Artisan::call('config:clear');

    return redirect('/#clear');
});

Route::get('/salir', function () {
    Auth::logout();

    // Invalidar la sesión y regenerar el token CSRF por seguridad
    request()->session()->invalidate();
    request()->session()->regenerateToken();

    return redirect('/'); // o a donde quieras redirigir
})->name('salir');
