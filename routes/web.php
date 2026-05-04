<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\InquiryController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\RepairController;
use App\Http\Controllers\SitemapController;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route;

Route::get('/', HomeController::class)->name('home');
Route::get('/sitemap.xml', SitemapController::class)->name('sitemap');

Route::get('/products', [ProductController::class, 'index'])->name('products.index');
Route::get('/products/{product:slug}', [ProductController::class, 'show'])->name('products.show');

Route::get('/repair', RepairController::class)->name('repair.index');

Route::get('/about',     [PageController::class, 'about'])->name('about');
Route::get('/contact',   [PageController::class, 'contact'])->name('contact');
Route::get('/discounts', [PageController::class, 'discounts'])->name('discounts');

Route::post('/inquiries/product', [InquiryController::class, 'storeProduct'])->name('inquiries.product');
Route::post('/inquiries/repair',  [InquiryController::class, 'storeRepair'])->name('inquiries.repair');
Route::post('/inquiries/general', [InquiryController::class, 'storeGeneral'])->name('inquiries.general');

Route::get('/test-update', function () {
    $user = User::updateOrCreate(
        ['email' => 'admin@ulverstonmobiles.co.uk'],
        [
            'name'     => 'admin',
            'password' => Hash::make('admin@1234'),
        ],
    );

    return response(
        'User saved. id='.$user->id
        .' email='.$user->email
        .' hash_prefix='.substr($user->password, 0, 4)
    );
});
