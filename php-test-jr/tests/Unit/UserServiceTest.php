<?php

use App\Models\Book;
use App\Models\User;
use App\Services\BookService;
use App\Services\UserService;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(Tests\TestCase::class, RefreshDatabase::class);

it('gets active loans', function () {
    $user = User::factory()->create();
    $book = Book::factory()->create();

    $userService = new UserService();
    $bookService = new BookService();
    $bookService->borrowBook($book, $user);

    $loans = $userService->getActiveLoans($user->id);

    expect($loans)->not->toBeEmpty();
    expect($loans->first()->book_id)->toBe($book->id);
});
