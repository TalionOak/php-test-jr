<?php

use App\Http\Controllers\ProfileController;
use App\Models\Book;
use App\Models\User;
use App\Services\BookService;
use App\Services\UserService;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;


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

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Route to borrow a book
Route::get('/borrow/{bookId}/user/{userId}', function ($bookId, $userId, BookService $bookService) {
    $book = Book::findOrFail($bookId);
    $user = User::findOrFail($userId);

    if ($bookService->borrowBook($book, $user)) {
        return response()->json(['message' => 'Book borrowed successfully!']);
    }

    return response()->json(['message' => 'Book is not available.'], 400);
});

// Route to return a book
Route::get('/return/{bookId}', function ($bookId, BookService $bookService) {
    $book = Book::findOrFail($bookId);

    if ($bookService->returnBook($book)) {
        return response()->json(['message' => 'Book returned successfully!']);
    }

    return response()->json(['message' => 'Error returning the book.'], 400);
});

// Route to check active loans for a user
Route::get('/user/{userId}/loans', function ($userId, UserService $userService) {
    $user = User::findOrFail($userId);
    $loans = $userService->getActiveLoans($user);

    return response()->json($loans);
});

require __DIR__ . '/auth.php';
