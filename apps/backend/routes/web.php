<?php

use App\Http\Controllers\GuestMenuController;
use App\Http\Controllers\Web\AuthController;
use App\Http\Controllers\Web\BrowseController;
use App\Http\Controllers\Web\DashboardController;
use App\Http\Controllers\Web\MenuManageController;
use App\Http\Controllers\Web\QrDesignerController;
use App\Http\Controllers\Web\RestaurantController;
use App\Http\Controllers\Web\SettingsController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('landing');
});

Route::get('/menu/{restaurantSlug}/{tableUuid}', [GuestMenuController::class, 'show'])
    ->name('guest.menu');
Route::get('/menu/{restaurantSlug}/m/{menuUuid}', [GuestMenuController::class, 'showByMenu'])
    ->name('guest.menu.direct');

// Auth (guest only)
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth')->name('logout');

// Authenticated
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/settings', [SettingsController::class, 'index'])->name('settings');

    // Restaurant CRUD
    Route::resource('restaurants', RestaurantController::class)->names('web.restaurants');

    // AJAX endpoints for menu management (return JSON)
    Route::post('/restaurants/{restaurant}/menus', [MenuManageController::class, 'storeMenu'])->name('manage.menus.store');
    Route::put('/menus/{menu}', [MenuManageController::class, 'updateMenu'])->name('manage.menus.update');
    Route::delete('/menus/{menu}', [MenuManageController::class, 'deleteMenu'])->name('manage.menus.delete');

    Route::post('/menus/{menu}/categories', [MenuManageController::class, 'storeCategory'])->name('manage.categories.store');
    Route::put('/categories/{category}', [MenuManageController::class, 'updateCategory'])->name('manage.categories.update');
    Route::delete('/categories/{category}', [MenuManageController::class, 'deleteCategory'])->name('manage.categories.delete');

    Route::post('/categories/{category}/dishes', [MenuManageController::class, 'storeDish'])->name('manage.dishes.store');
    Route::put('/dishes/{dish}', [MenuManageController::class, 'updateDish'])->name('manage.dishes.update');
    Route::delete('/dishes/{dish}', [MenuManageController::class, 'deleteDish'])->name('manage.dishes.delete');
    Route::post('/dishes/{dish}/image', [MenuManageController::class, 'uploadDishImage'])->name('manage.dishes.image');
    Route::delete('/dishes/{dish}/image', [MenuManageController::class, 'deleteDishImage'])->name('manage.dishes.image.delete');

    Route::post('/restaurants/{restaurant}/tables', [MenuManageController::class, 'storeTable'])->name('manage.tables.store');
    Route::put('/tables/{table}', [MenuManageController::class, 'updateTable'])->name('manage.tables.update');
    Route::delete('/tables/{table}', [MenuManageController::class, 'deleteTable'])->name('manage.tables.delete');
    Route::get('/menus/{menu}/qr', [MenuManageController::class, 'downloadMenuQr'])->name('manage.menus.qr');
    Route::get('/menus/{menu}/qr-designer', [QrDesignerController::class, 'show'])->name('manage.menus.qr-designer');
    Route::post('/menus/{menu}/qr-designer/download', [QrDesignerController::class, 'download'])->name('manage.menus.qr-designer.download');
    Route::get('/menus/{menu}/qr-designer/preview', [QrDesignerController::class, 'preview'])->name('manage.menus.qr-designer.preview');
    Route::get('/tables/{table}/qr', [MenuManageController::class, 'downloadQr'])->name('manage.tables.qr');
});

// Browse (public)
Route::get('/browse', [BrowseController::class, 'index'])->name('browse');
Route::post('/browse/lookup', [BrowseController::class, 'lookupByCode'])->name('browse.lookup');
Route::get('/browse/{restaurant}', [BrowseController::class, 'showRestaurant'])->name('browse.restaurant');
