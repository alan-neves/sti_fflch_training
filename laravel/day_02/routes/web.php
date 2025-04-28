<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BookController;

// CREATE
Route::get('/books/create', [BookController::class, 'create']);
Route::post('/books', [BookController::class, 'store']);

// READ
Route::get('/books', [BookController::class, 'index']);
Route::get('/books/{book}', [BookController::class, 'show']);

// UPDATE
Route::get('/books/{book}/edit', [BookController::class, 'edit']);
Route::put('/books/{book}', [BookController::class, 'update']);

// DELETE
Route::delete('/books/{book}', [BookController::class,'destroy']);