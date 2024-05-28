<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PackingListController;

Route::get('/', function () {
    return view('welcome');
});

Route::prefix('admin')->group(function () {
    Route::get('/packing-list/{orderId}', [PackingListController::class, 'generatePackingList'])->name('packing-list.generate');
});
