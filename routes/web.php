<?php

use App\Livewire\Admin\Brand\ManageBrand;
use App\Livewire\Admin\Brand\CreateBrand;
use App\Livewire\Admin\Category\ManageCategory;
use App\Livewire\Admin\ManageCoupon;
use App\Livewire\Admin\Dashboard;
use App\Livewire\Admin\Product\ManageProduct;
use App\Livewire\Admin\Product\MultipleImages;
use App\Livewire\Admin\Product\ListProduct;
use App\Livewire\Admin\Product\CreateProduct;
use App\Livewire\Admin\Product\UpdateProduct;
use App\Livewire\Auth\Login;
use App\Livewire\Auth\Register;
use App\Livewire\Public\Cart;
use App\Livewire\Public\Section\LandingPage;
use App\Livewire\Public\Section\ViewProduct;
use App\Livewire\Public\AllProduct;
use App\Livewire\Admin\Category\ListCategory;
use App\Livewire\Admin\Category\CreateCategory;
use App\Livewire\Admin\Category\UpdateCategory;
use Illuminate\Support\Facades\Route;

Route::get('login', Login::class)->name('login');
Route::get('register', Register::class)->name('register');
Route::get('/',LandingPage::class)->name('home');
Route::get('/product/{slug}', ViewProduct::class)->name('public.product.detail');

Route::prefix('admin')->group(function () {
    // Categories
    Route::get('/categories/list', ListCategory::class)->name('categories.index');
    Route::get('/categories/create', CreateCategory::class)->name('categories.create');
    Route::get('/categories/{slug}', App\Livewire\Admin\Category\ViewCategory::class)->name('categories.view');
    Route::get('/categories/{slug}/edit', UpdateCategory::class)->name('categories.edit');
    // Products
    Route::get('/products', ListProduct::class)->name('products.index');
    Route::get('/products/create', CreateProduct::class)->name('products.create');
    Route::get('/products/{product:slug}/edit', UpdateProduct::class)->name('products.edit');
 // Dashboard and other admin routes
    Route::get('/', Dashboard::class)->name('admin.dashboard');
    Route::get('categories', ManageCategory::class)->name('admin.categories');
    Route::get('brands', ManageBrand::class)->name('admin.brands');
    Route::get('products-old', ManageProduct::class)->name('admin.products');
    Route::get('productImage', MultipleImages::class)->name('admin.product-image');
    Route::get('coupon', ManageCoupon::class)->name('admin.coupon');
    Route::get('brand-add', CreateBrand::class)->name('brand-add');
});

Route::middleware('auth')->group(function () {
    Route::get('/cart', Cart::class)->name('public.cart');
//  Route::post('/cart/add', [ProductDetail::class, 'addToCart'])->name('cart.add');
});
// Route::get('/product/{slug}', ProductDetail::class)->name('public.product.detail');

Route::get('/dashboard', function () {
    return view('admin.dashboard');
})->name('dashboard');
Route::get('/product-list', function () {
    return view('admin.products.list');
})->name('product-list');
Route::get('/product-add', function () {
    return view('admin.products.create');
})->name('product-add');
Route::get('/brand-list', function () {
    return view('admin.brand.list');
})->name('brand-list');
Route::get('/manage-brand', ManageBrand::class)->name('admin.brand.manage');
Route::get('/category-add', function () {
    return view('admin.category.create');
})->name('category-add');
Route::get('/category-list', function () {
    return view('admin.category.list');
})->name('category-list');


//NEw Routes
Route::get('/our-products', AllProduct::class)->name('public.product.all');

