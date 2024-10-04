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
    $result = $bookService->borrowBook($book, $user);

    expect($result)->toBeTrue();
});

it('returns a book successfully', function () {
    $book = Book::factory()->create();
    $user = User::factory()->create();

    $bookService = new BookService();
    $bookService->borrowBook($book, $user);
    $result = $bookService->returnBook($book);

    expect($result)->toBeTrue();
});
