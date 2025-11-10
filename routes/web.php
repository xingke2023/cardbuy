<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('teachers.index');
});

Route::get('/hello', function () {
    return '<h1>Hello World!</h1>';
});

// Teacher routes
Route::get('/teachers', [App\Http\Controllers\TeacherController::class, 'index'])->name('teachers.index');
Route::get('/teachers/{teacher}', [App\Http\Controllers\TeacherController::class, 'show'])->name('teachers.show');

// Order routes
Route::get('/services/{service}/order', [App\Http\Controllers\OrderController::class, 'create'])->name('orders.create');
Route::post('/services/{service}/order', [App\Http\Controllers\OrderController::class, 'store'])->name('orders.store');
Route::get('/orders/{order}', [App\Http\Controllers\OrderController::class, 'show'])->name('orders.show');
Route::post('/orders/{order}/accept', [App\Http\Controllers\OrderController::class, 'accept'])->name('orders.accept');
Route::post('/orders/{order}/reject', [App\Http\Controllers\OrderController::class, 'reject'])->name('orders.reject');
Route::patch('/orders/{order}/cancel', [App\Http\Controllers\OrderController::class, 'cancel'])->name('orders.cancel');
Route::patch('/orders/{order}/complete', [App\Http\Controllers\OrderController::class, 'complete'])->name('orders.complete');
Route::patch('/orders/{order}/confirm', [App\Http\Controllers\OrderController::class, 'confirm'])->name('orders.confirm');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/my', [ProfileController::class, 'show'])->name('profile.show');
    Route::get('/my/services', [App\Http\Controllers\ServiceController::class, 'myServices'])->name('services.my');
    Route::get('/my/orders', [App\Http\Controllers\OrderController::class, 'teacherOrders'])->name('orders.teacher');
    Route::get('/my/customer-orders', [App\Http\Controllers\OrderController::class, 'customerOrders'])->name('orders.customer');
    Route::get('/my/invitations', [ProfileController::class, 'invitations'])->name('profile.invitations');
    Route::get('/my/wallet', [ProfileController::class, 'wallet'])->name('profile.wallet');
    Route::get('/help', [ProfileController::class, 'help'])->name('help');
    Route::get('/teacher/apply', [App\Http\Controllers\TeacherApplicationController::class, 'apply'])->name('teacher.apply');
    Route::post('/teacher/apply', [App\Http\Controllers\TeacherApplicationController::class, 'submit'])->name('teacher.submit');
    Route::get('/services/create', [App\Http\Controllers\ServiceController::class, 'create'])->name('services.create');
    Route::post('/services', [App\Http\Controllers\ServiceController::class, 'store'])->name('services.store');
    Route::get('/services/{service}', [App\Http\Controllers\ServiceController::class, 'show'])->name('services.show');
    Route::get('/services/{service}/edit', [App\Http\Controllers\ServiceController::class, 'edit'])->name('services.edit');
    Route::patch('/services/{service}', [App\Http\Controllers\ServiceController::class, 'update'])->name('services.update');
    Route::delete('/services/{service}', [App\Http\Controllers\ServiceController::class, 'destroy'])->name('services.destroy');
    Route::patch('/services/{service}/toggle-status', [App\Http\Controllers\ServiceController::class, 'toggleStatus'])->name('services.toggle-status');
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
