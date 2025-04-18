<?php

use App\Livewire\Admin\Brand\ManageBrand;
use App\Livewire\Admin\Category\ManageCategory;
use App\Livewire\Admin\Product\ManageProduct;

use App\Livewire\Admin\Product\MultipleImages;
use App\Livewire\Public\Home;
use Illuminate\Support\Facades\Route;

// Route::get('/', function () {
//     return view('welcome');
// });

Route::get('/',Home::class)->name('public.home');
Route::prefix('admin')->group(function () {
    Route::get('categories',ManageCategory::class)->name('admin.categories');
    Route::get('brands',ManageBrand::class)->name('admin.brands');
    Route::get('products',ManageProduct::class)->name('admin.products');
    Route::get('productImage',MultipleImages::class)->name('admin.product-image');
});