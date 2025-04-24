<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\InternController;

Route::get('/interns', [InternController::class, 'index']);

Route::get('/interns/create', [InternController::class, 'create']);
