<?php

use App\Models\Book;
use App\Models\User;
use App\Services\BookService;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(Tests\TestCase::class, RefreshDatabase::class);

it('borrows a book successfully', function () {
    $book = Book::factory()->create();
    $user = User::factory()->create();

    $bookService = new BookService();
    $result = $bookService->borrowBook($book->id, $user->id);

    expect($result)->toBe("Book borrowed successfully.");
});

it('returns a book successfully', function () {
    $book = Book::factory()->create();
    $user = User::factory()->create();

    $bookService = new BookService();
    $bookService->borrowBook($book->id, $user->id);
    $result = $bookService->returnBook($book->id);

    expect($result)->toBe("Book returned successfully.");
});
