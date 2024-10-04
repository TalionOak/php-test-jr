<?php

use App\Models\Book;
use App\Models\Loan;
use App\Models\User;
use App\Repositories\BookRepository;
use App\Repositories\LoanRepository;
use App\Repositories\UserRepository;
use App\Services\BookService;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(Tests\TestCase::class, RefreshDatabase::class);

function setUpMocks()
{
    $loanRepositoryMock = Mockery::mock(LoanRepository::class);
    $bookRepositoryMock = Mockery::mock(BookRepository::class);
    $userRepositoryMock = Mockery::mock(UserRepository::class);

    return [$loanRepositoryMock, $bookRepositoryMock, $userRepositoryMock];
}

function createBookService($loanRepositoryMock, $bookRepositoryMock, $userRepositoryMock)
{
    return new BookService($loanRepositoryMock, $bookRepositoryMock, $userRepositoryMock);
}

it('borrows a book successfully', function () {
    [$loanRepositoryMock, $bookRepositoryMock, $userRepositoryMock] = setUpMocks();

    $bookMock = Mockery::mock();
    $bookMock->shouldReceive('isAvailable')->andReturn(true);
    $bookMock->id = 1;

    $userMock = Mockery::mock(User::class)->makePartial();
    $userMock->id = 1;

    $bookRepositoryMock->shouldReceive('findBookById')->with(1)->andReturn($bookMock);
    $userRepositoryMock->shouldReceive('findUserById')->with(1)->andReturn($userMock);
    $loanRepositoryMock->shouldReceive('createLoan')->once()->andReturnTrue();

    $bookService = createBookService($loanRepositoryMock, $bookRepositoryMock, $userRepositoryMock);
    $response = $bookService->borrowBook(1, 1);

    expect($response)->toBeTrue();
});

it('fails to borrow an unavailable book', function () {
    [$loanRepositoryMock, $bookRepositoryMock, $userRepositoryMock] = setUpMocks();

    $bookMock = Mockery::mock();
    $bookMock->shouldReceive('isAvailable')->andReturn(false);

    $userMock = Mockery::mock(User::class);

    $bookRepositoryMock->shouldReceive('findBookById')->with(1)->andReturn($bookMock);
    $userRepositoryMock->shouldReceive('findUserById')->with(1)->andReturn($userMock);

    $bookService = createBookService($loanRepositoryMock, $bookRepositoryMock, $userRepositoryMock);
    $response = $bookService->borrowBook(1, 1);

    expect($response)->toBeFalse();
});

it('returns a book successfully', function () {
    [$loanRepositoryMock, $bookRepositoryMock] = setUpMocks();

    $bookMock = Mockery::mock(Book::class)->makePartial();
    $bookMock->id = 1;

    $loanMock = Mockery::mock(Loan::class)->makePartial();

    $bookRepositoryMock->shouldReceive('findBookById')->with(1)->andReturn($bookMock);
    $loanRepositoryMock->shouldReceive('findActiveLoanByBook')->with(1)->andReturn($loanMock);
    $loanRepositoryMock->shouldReceive('updateLoan')
        ->with(Mockery::on(function ($loan) {
            return $loan instanceof Loan;
        }), Mockery::on(function ($attributes) {
            return isset($attributes['return_date']) && $attributes['return_date'] instanceof Carbon;
        }))
        ->once();

    $bookService = createBookService($loanRepositoryMock, $bookRepositoryMock, Mockery::mock(UserRepository::class));
    $response = $bookService->returnBook(1);

    expect($response)->toBeTrue();
});

it('fails to return a book with no active loan', function () {
    [$loanRepositoryMock, $bookRepositoryMock] = setUpMocks();

    $bookMock = Mockery::mock();
    $bookMock->id = 1;

    $bookRepositoryMock->shouldReceive('findBookById')->with(1)->andReturn($bookMock);
    $loanRepositoryMock->shouldReceive('findActiveLoanByBook')->with(1)->andReturn(null);

    $bookService = createBookService($loanRepositoryMock, $bookRepositoryMock, Mockery::mock(UserRepository::class));
    $response = $bookService->returnBook(1);

    expect($response)->toBeFalse();
});
