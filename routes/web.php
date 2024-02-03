<?php

use App\Http\Controllers\AdminDashboard;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\csProfileController;
use App\Http\Controllers\ProdukDetailController;
use App\Http\Controllers\ReviewController;
use App\Livewire\ChatComponent;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\FindPlayerController;
use App\Http\Controllers\HistoryOrderController;
use App\Http\Controllers\insertVerifController;
use App\Http\Controllers\VerificationController;
use App\Http\Controllers\PostingController;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return redirect()->route('login'); // Arahkan ke route 'login'
});

Route::get('/register', [AuthController::class, 'register'])->name('register');
Route::post('/register', [AuthController::class, 'registerPost'])->name('register');

Route::get('/login', [LoginController::class, 'login'])->name('login');
Route::post('/login', [LoginController::class, 'loginPost'])->name('login');

Route::get('/dashboard', [DashboardController::class, 'dashboard'])->name('dashboard')->middleware('auth');
Route::get('/dashboard/soccer', [DashboardController::class, 'filterSoccer'])->name('dashboardSoccer')->middleware('auth');
Route::get('/dashboard/BasketBall', [DashboardController::class, 'filterBasketball'])->name('dashboardBasketBall')->middleware('auth');
Route::get('/dashboard/Badminton', [DashboardController::class, 'filterBadminton'])->name('dashboardBadminton')->middleware('auth');
Route::get('/dashboard/IndoorFootball', [DashboardController::class, 'filterIndoorFootball'])->name('dashboardIndoorFootball')->middleware('auth');
Route::get('/dashboard/Volley', [DashboardController::class, 'filterVolley'])->name('dashboardVolley')->middleware('auth');
Route::post('/bookings/{product_id}', [ProdukDetailController::class, 'booking'])->name('bookings.product');


Route::get('/dashboard/product/{id}', [ProdukDetailController::class, 'showPD'])->name('productDetail')->middleware('auth');
Route::get('/dashboard/product/{id}/reviews', [ProdukDetailController::class, 'showReviews'])->name('showReviews');
Route::post('/dashboard/product/{id}/post', [ProdukDetailController::class, 'review'])->name('reviewProduk');

    Route::get('/admin/dashboard', [AdminDashboard::class, 'ad_dashboard'])->name('ad_dashboard');
Route::post('/admin/dashboard/post', [AdminDashboard::class, 'ad_dashboard_add'])->name('ad_dashboardPost');

Route::get('/csProfile', [csProfileController::class, 'profile'])->name('csProfile')->middleware('auth');
Route::post('/csProfile/Update/{id}', [csProfileController::class, 'updateProfile'])->name('csProfileUpdate');

Route::get('/chat/{productId}',  [ChatComponent::class, 'show'])->name('chat');

// Route::get('/checkout', [BookingController::class, 'checkOut'])->name('checkoutGet'); // Rute untuk menampilkan halaman checkout
Route::post('/checkout', [ProdukDetailController::class, 'booking'])->name('checkout')->middleware('allowhttp');
Route::get('/historyOrder', [HistoryOrderController::class, 'index'])->middleware(['auth'])->name('historyOrder');

Route::get('/checkout/{booking_id}', [ProdukDetailController::class, 'showCheckoutForm'])->name('showCheckoutForm')->middleware('allowhttp');
Route::post('/checkout/{booking_id}', [ProdukDetailController::class, 'booking'])->name('checkoutHistory')->middleware('allowhttp');

Route::get('/findPlayer', [PostingController::class, 'index'])->middleware(['auth'])->name('findPlayer');
Route::get('/filter-postings', [PostingController::class, 'applyFilters'])->name('filterPostings');
Route::post('/send-verification-code', [VerificationController::class, 'sendVerificationCode'])->middleware('auth')->name('send-verification-code');
Route::get('/insert-code', [insertVerifController::class, 'insertCode'])->name('code');
Route::post('/post-code', [insertVerifController::class, 'postCode'])->name('post-code');
Route::post('/create-post', [PostingController::class, 'post'])->name('create.post');
Route::get('/redirect-to-wa-post/{postId}', [FindPlayerController::class, 'redirectToWhatsApp'])->name('redirectWA');
Route::get('/redirect-to-wa-product/{productId}', [ProdukDetailController::class, 'redirectToWhatsAppProduct'])
    ->name('redirecttoWA');
Route::get('/historyOrder/filter', [HistoryOrderController::class, 'filter'])->name('historyOrder.filter');

Route::get('/ad_dashboard/edit/{id}', 'AdminDashboard@ad_dashboard_Edit')->name('ad_dashboardEdit');
Route::post('/ad_dashboard/update/{id}', [AdminDashboard::class, 'ad_dashboard_Update'])->name('ad_dashboardUpdate');
Route::delete('/ad_dashboard/delete/{id}',  [AdminDashboard::class, 'ad_dashboard_Delete'])->name('ad_dashboardDelete');
Route::get('/post/{postId}/edit', 'PostingController@edit')->name('edit.post');
Route::patch('/post/{postId}/update', [PostingController::class, 'update'])->name('update.post');
Route::delete('/post/{postId}/delete', [PostingController::class, 'delete'])->name('delete.post');
Route::get('/logout', [AuthController::class, 'logout'])->name('logout');
