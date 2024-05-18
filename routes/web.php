<?php

use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\RoomController;
use App\Http\Controllers\RoomCommentController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\FollowController;
use App\Http\Controllers\LogController;
use App\Http\Controllers\TaskController;





// Route::get('/', function () {
//     return Inertia::render('Welcome', [
//         'canLogin' => Route::has('login'),
//         'canRegister' => Route::has('register'),
//         'laravelVersion' => Application::VERSION,
//         'phpVersion' => PHP_VERSION,
//     ]);
// });
Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get('/room/create', [RoomController::class, 'create'])->name('room.create');
Route::post('/room/store', [RoomController::class, 'store'])->name('room.store');
Route::get('/room/{id}', [RoomController::class, 'show'])->name('room.show');
Route::post('/ajaxComment', [RoomCommentController::class, 'store']);
Route::get('/rooms/{room}/comments', [RoomCommentController::class, 'index']);
Route::post('/follow/{id}', [FollowController::class, 'follow'])->name('follow');
Route::post('/unfollow/{id}', [FollowController::class, 'unfollow'])->name('unfollow');
Route::get('/user/{id}/following', [FollowController::class, 'following'])->name('user.following');
Route::get('/user/{id}/followers', [FollowController::class, 'followers'])->name('user.followers');Route::get('/logs', [LogController::class, 'getLogs']);
Route::post('/task/store', [TaskController::class, 'store'])->name('task.store');
Route::get('/tasks', [TaskController::class, 'index']);
Route::get('/room/{roomId}/user-count', [RoomController::class, 'getUserCount']);



Route::get('/room', function () {
    return Inertia::render('Room', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
})->name('room');

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');


Route::get('/user/{id}', [UserController::class, 'show'])->name('user.show');


Route::middleware('auth')->group(function () {
    // Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    // Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    // Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/mypage', [UserController::class, 'myPage'])->name('user.mypage')->middleware('auth');
    Route::get('/profile/edit', [UserController::class, 'editProfile'])->name('profile.edit');
    Route::post('/profile/update', [UserController::class, 'updateProfile'])->name('profile.update');
    Route::get('/withdraw-confirm', [UserController::class, 'withdrawConfirm'])->name('withdraw.confirm');
    Route::post('/withdraw', [UserController::class, 'withdraw'])->name('withdraw');
});



require __DIR__.'/auth.php';
