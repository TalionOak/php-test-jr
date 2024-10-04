<?php

use App\Http\Controllers\BookController;
use App\Http\Controllers\BorrowController;
use App\Http\Controllers\LoanController;
use App\Http\Controllers\ReturnController;
use Illuminate\Support\Facades\Route;

Route::get('/borrow/{bookId}/user/{userId}', [BorrowController::class, 'borrow']);
Route::get('/return/{bookId}', [ReturnController::class, 'return']);
Route::get('/user/{userId}/loans', [LoanController::class, 'activeLoans']);
Route::get('/books/available', [BookController::class, 'availableBooks']);
