<?php

use App\Http\Controllers\FileServeController;
use Illuminate\Support\Facades\Route;
use Stancl\Tenancy\Middleware\InitializeTenancyBySubdomain;
use Stancl\Tenancy\Middleware\PreventAccessFromCentralDomains;

Route::get('/', function () {
    return redirect('/admin');
});

Route::middleware([
    'web',
    InitializeTenancyBySubdomain::class,
    PreventAccessFromCentralDomains::class,
])->group(function () {

    Route::get('/secure-files/{file}', [FileServeController::class, 'show'])
        ->name('files.secure.show')
        ->middleware('signed');

});

Route::get('clear', function () {

    Artisan::call('view:clear');
    Artisan::call('route:clear');
    Artisan::call('config:clear');
    Artisan::call('cache:clear');
    Artisan::call('config:cache');

    return redirect('/#clear');

})->name('clear');
