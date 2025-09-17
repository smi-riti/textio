<?php

use App\Http\Livewire\User\Product\AddItem;
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
use App\Livewire\Admin\Product\Variant\VariantName;
use App\Livewire\Admin\Product\Variant\VariantValus;
use App\Livewire\Admin\Users\Customer;
use App\Livewire\Auth\Login;
use App\Livewire\Auth\Register;
use App\Livewire\Public\AllProduct;
use App\Livewire\Public\Cart;
use App\Livewire\Public\Section\Accounts\ManageAddress;
use App\Livewire\Public\Section\Accounts\ProfileInformation;
use App\Livewire\Public\Section\LandingPage;
use App\Livewire\Public\Section\MyCart;
use App\Livewire\Public\Section\MyOrder;
use App\Livewire\Public\Section\ViewProduct;
use App\Livewire\Public\Section\WishlistCard;
use Illuminate\Support\Facades\Route;
use App\Livewire\Admin\Category\ListCategory;
use App\Livewire\Admin\Category\CreateCategory;
use App\Livewire\Admin\Category\UpdateCategory;
use App\Livewire\Admin\ManageEnquiry;
use App\Livewire\Public\Section\MyOrders;
use App\Livewire\Public\Page\ContactPage;
use App\Livewire\Admin\Category\ViewCategory;
use App\Livewire\Admin\Product\ViewProduct As AdminViewProduct;
use App\Livewire\Admin\Product\EditProduct;
use App\Livewire\Admin\Product\ProductVariantList;
use App\Livewire\Admin\Product\ProductVariantEdit;



Route::get('register', Register::class)->name('register');
Route::get('/',LandingPage::class)->name('home');
//  Route::get('/wishlist', Wishlist::class)->name('wishlist.index');
Route::get('/view/{slug}',ViewProduct::class)->name('view.product');
Route::post('/logout',function(){
    Auth::logout();
    return redirect('/login');
})->name('logout');

//google login 

Route::get('/login', Login::class)->name('login');
Route::get('/auth/google', [Login::class, 'redirectToGoogle'])->name('google.redirect');
Route::get('/auth/google/callback', [Login::class, 'handleGoogleCallback'])->name('auth.google.callback');// Route::get('/cart',AddItem::class)->name('cart');

Route::get('/mycart',MyCart::class)->name('myCart');
Route::get('/myorder',MyOrder::class)->name('myOrder');
Route::get('/myorders',MyOrders::class)->name('myOrders');
Route::get('/product/{slug}', ViewProduct::class)->name('public.product.view');
Route::get('/wishlist', WishlistCard::class)->name('wishlist.index');
Route::get('/account',ProfileInformation::class)->name('profile-information');
Route::get('/account/address',ManageAddress::class)->name('Manage-address');
Route::get('/contact', ContactPage::class)->name('contact');
// Admin routes protected by middleware
Route::prefix('admin')->name('admin.')->middleware(['auth', 'admin'])->group(function () {
    // Dashboard
    Route::get('/', Dashboard::class)->name('dashboard');
    Route::get('/Variant',VariantValus::class)->name('VariantValues');
    Route::get('/Variant/Name',VariantName::class)->name('VariantName');
    // Categories
    Route::get('/categories/list', ListCategory::class)->name('categories.index');
    Route::get('/categories/create', CreateCategory::class)->name('categories.create');
    Route::get('/categories/{slug}', ViewCategory::class)->name('categories.view');
    Route::get('/categories/{slug}/edit', UpdateCategory::class)->name('categories.edit');
    
    // Products
    Route::get('/products', ListProduct::class)->name('products.index');
    Route::get('/products/list', ListProduct::class)->name('products.list');
    Route::get('/products/create', CreateProduct::class)->name('products.create');
    Route::get('/products/{product:slug}',AdminViewProduct::class)->name('products.view');
    Route::get('/products/{product:slug}/edit', EditProduct::class)->name('products.edit');
    
    // Product Variants
    Route::get('/products/{product}/variants', ProductVariantList::class)->name('products.variants');
    Route::get('/products/{product}/variants/{variant}/edit', ProductVariantEdit::class)->name('products.variants.edit');
    
    // contact enquiries
    Route::get('/enquiries', ManageEnquiry::class)->name('enquiries');
    //brand

    Route::get('/manage-brand', ManageBrand::class)->name('brand.manage');
    Route::get('brand-add', CreateBrand::class)->name('brand-add');
    Route::get('brand/{brandId}/edit', \App\Livewire\Admin\Brand\EditBrand::class)->name('brand.edit');
    
    Route::get('categories', ManageCategory::class)->name('categories');
    Route::get('brands', ManageBrand::class)->name('brands');
    Route::get('products-old', ManageProduct::class)->name('products');
    Route::get('productImage', MultipleImages::class)->name('product-image');
    Route::get('coupon', ManageCoupon::class)->name('coupon');
    Route::get('user', Customer::class)->name('customer');

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
})->name('product-list.old');
Route::get('/product-add', function () {
    return view('admin.products.create');
})->name('product-add.old');
Route::get('/brand-list', function () {
    return view('admin.brand.list');
})->name('brand-list');
Route::get('/category-add', function () {
    return view('admin.category.create');
})->name('category-add');
Route::get('/category-list', function () {
    return view('admin.category.list');
})->name('category-list');


//NEw Routes
Route::get('/our-products', AllProduct::class)->name('public.product.all');

