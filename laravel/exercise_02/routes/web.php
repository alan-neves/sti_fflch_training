<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BookController;

Route::get('/books', [BookController::class, 'index']);

Route::get('/books/stats', [BookController::class, 'stats']);
Route::get('/books/stats/year', [BookController::class, 'statsYear']);
Route::get('/books/stats/author', [BookController::class, 'statsAuthor']);
Route::get('/books/stats/language', [BookController::class, 'statsLanguage']);

Route::get('/books/importcsv', [BookController::class, 'importCsv']);

Route::get('/books/create', [BookController::class, 'create']);
Route::post('/books', [BookController::class, 'store']);
Route::get('/books/{book}', [BookController::class, 'show']);
Route::get('/books/{book}/edit', [BookController::class, 'edit']);
Route::put('/books/{book}', [BookController::class, 'update']);
Route::delete('/books/{book}', [BookController::class, 'destroy']);
