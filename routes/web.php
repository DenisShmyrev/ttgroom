<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\RoomController;
use App\Http\Controllers\HomeController;

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
// Регистрация/авторизация
Auth::routes();

// Личный кабинет
Route::middleware(['auth'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile');
    Route::resource('bookings', BookingController::class);
});

// Админка
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::resource('rooms', RoomController::class);
    Route::get('/bookings/{booking}/edit', [BookingController::class, 'edit'])->name('bookings.edit');
    Route::put('/bookings/{booking}', [BookingController::class, 'update'])->name('bookings.update');
    Route::delete('/admin/bookings/{booking}', [BookingController::class, 'destroy'])->name('bookings.destroy');
    Route::patch('/bookings/{booking}/restore', [BookingController::class, 'restore'])->name('bookings.restore'); // Новый маршрут
    Route::get('/', function () {
        return view('admin.dashboard');
    })->name('dashboard');
    
    Route::get('/bookings', [BookingController::class, 'adminIndex'])->name('bookings');
    Route::get('/rooms', [RoomController::class, 'controll'])->name('rooms.controll');
    Route::get('/rooms/create', [RoomController::class, 'create'])->name('rooms.create');
    Route::get('/rooms/edit/{room}', [RoomController::class, 'edit'])->name('rooms.edit');
    Route::put('/rooms/edit/{room}', [RoomController::class, 'update'])->name('rooms.update');

});
    Route::patch('/bookings/{booking}/cancel', [BookingController::class, 'cancel'])->name('bookings.cancel');

Route::get('/', function () {
    return view('welcome');
});

//Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
//});

Route::get('/rooms', [RoomController::class, 'index'])->name('rooms.index');
//Route::get('/rooms/{room}', [RoomController::class, 'show'])->name('rooms.show');

Route::get('/bookings/available-times', [BookingController::class, 'availableTimes'])->name('bookings.availableTimes');

// Страницы "О нас", "Контакты" и "Политика конфиденциальности"
Route::get('/about', [ProfileController::class, 'about'])->name('about');
Route::get('/contact', [ProfileController::class, 'contact'])->name('contact');
Route::get('/policy', [ProfileController::class, 'policy'])->name('policy');


//Auth::routes();

Route::middleware(['auth'])->get('/home', [HomeController::class, 'index'])->name('home');