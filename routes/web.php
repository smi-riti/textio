<?php

use App\Livewire\Admin\Brand\ManageBrand;
use App\Livewire\Admin\Category\ManageCategory;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::prefix('admin')->group(function () {
    Route::get('categories',ManageCategory::class)->name('admin.categories');
    Route::get('brands',ManageBrand::class)->name('admin.brands');
});