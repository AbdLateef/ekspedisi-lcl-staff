<?php

use App\Http\Controllers\AwbEntryController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('awb.index');
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', function () {
        return redirect()->route('awb.index');
    })->name('dashboard');

    Route::get('/awb', [AwbEntryController::class, 'index'])->name('awb.index');
    Route::post('/awb', [AwbEntryController::class, 'store'])->name('awb.store');

    Route::get('/customers-search', [AwbEntryController::class, 'searchCustomers'])->name('customers.search');
    Route::get('/containers-search', [AwbEntryController::class, 'searchContainers'])->name('containers.search');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
