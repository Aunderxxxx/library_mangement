<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\BookController;
use Illuminate\Support\Facades\Route;

Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);
Route::get('role', [AuthController::class, 'role']);

Route::middleware(['auth:sanctum', 'role:librarian'])->prefix('librarian')->group(function () {
    Route::post('logout', [AuthController::class, 'logout']);
    Route::get('books', [BookController::class, 'all_books']);
    Route::post('books', [BookController::class, 'new_books']);
    Route::post('books/{id}', [BookController::class, 'update_books']);
    Route::delete('books/{id}', [BookController::class, 'remove_books']);
});

Route::middleware(['auth:sanctum', 'role:member'])->prefix('member')->group(function () {
    Route::post('logout', [AuthController::class, 'logout']);
    Route::get('books', [BookController::class, 'all_books']);
    Route::post('loans', [BookController::class, 'borrow_books']);
    Route::get('loans', [BookController::class, 'borrow_all_books']);
    Route::post('loans/{id}/return', [BookController::class, 'return_books']);
});
