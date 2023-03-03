<?php

use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\ListController;
use App\Http\Controllers\CampaignController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::group(['middleware' => ['auth']], function() {
    Route::group(['prefix' => 'list'], function() {
        Route::get('/', [ListController::class, 'index'])->name('list.index');
        Route::get('show/{id}', [ListController::class, 'show'])->name('list.show');
        Route::post('store', [ListController::class, 'store'])->name('list.store');
        Route::put('update/{id}', [ListController::class, 'update'])->name('list.update');
        Route::delete('delete/{id}', [ListController::class, 'destroy'])->name('list.destroy');
    });

    Route::group(['prefix' => 'campaign'], function() {
        Route::get('/', [CampaignController::class, 'index'])->name('campaign.index');
        Route::get('create', [CampaignController::class, 'create'])->name('campaign.create');
        Route::get('show/{id}', [CampaignController::class, 'show'])->name('campaign.show');
        Route::post('store', [CampaignController::class, 'store'])->name('campaign.store');
        Route::put('update/{id}', [CampaignController::class, 'update'])->name('campaign.update');
        Route::delete('delete/{id}', [CampaignController::class, 'destroy'])->name('campaign.destroy');
    });
});


require __DIR__.'/auth.php';
