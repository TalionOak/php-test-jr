<?php

use App\Models\Book;
use App\Models\Loan;
use App\Models\User;
use App\Repositories\BookRepository;
use App\Repositories\LoanRepository;
use App\Repositories\UserRepository;
use App\Services\LoanService;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Collection;

uses(Tests\TestCase::class, RefreshDatabase::class);

function setUpMocks()
{
    $loanRepositoryMock = Mockery::mock(LoanRepository::class);
    $bookRepositoryMock = Mockery::mock(BookRepository::class);
    $userRepositoryMock = Mockery::mock(UserRepository::class);

    return [$loanRepositoryMock, $bookRepositoryMock, $userRepositoryMock];
}

function createLoanService($loanRepositoryMock, $bookRepositoryMock, $userRepositoryMock)
{
    return new LoanService($loanRepositoryMock, $bookRepositoryMock, $userRepositoryMock);
}

it('borrows a book successfully', function () {
    [$loanRepositoryMock, $bookRepositoryMock, $userRepositoryMock] = setUpMocks();

    $book = Book::factory()->create();
    $user = User::factory()->create();

    $bookRepositoryMock->shouldReceive('findBookById')->with($book->id)->andReturn($book);
    $userRepositoryMock->shouldReceive('findUserById')->with($user->id)->andReturn($user);
    $loanRepositoryMock->shouldReceive('isBookAvailable')->with($book->id)->andReturn(true);
    $loanRepositoryMock->shouldReceive('createLoan')->once()->andReturnTrue();

    $loanService = createLoanService($loanRepositoryMock, $bookRepositoryMock, $userRepositoryMock);
    $response = $loanService->borrowBook($book->id, $user->id);

    expect($response)->toBeTrue();
});

it('fails to borrow an unavailable book', function () {
    [$loanRepositoryMock, $bookRepositoryMock, $userRepositoryMock] = setUpMocks();

    $book = Book::factory()->create();
    $user = User::factory()->create();

    $bookRepositoryMock->shouldReceive('findBookById')->with($book->id)->andReturn($book);
    $userRepositoryMock->shouldReceive('findUserById')->with($user->id)->andReturn($user);
    $loanRepositoryMock->shouldReceive('isBookAvailable')->with($book->id)->andReturn(false);

    $loanService = createLoanService($loanRepositoryMock, $bookRepositoryMock, $userRepositoryMock);
    $response = $loanService->borrowBook($book->id, $user->id);

    expect($response)->toBeFalse();
});

it('returns a book successfully', function () {
    [$loanRepositoryMock, $bookRepositoryMock, $userRepositoryMock] = setUpMocks();

    $book = Book::factory()->create();
    $loan = Loan::factory()->create(['book_id' => $book->id]);

    $bookRepositoryMock->shouldReceive('findBookById')->with($book->id)->andReturn($book);
    $loanRepositoryMock->shouldReceive('findActiveLoanByBook')->with($book->id)->andReturn($loan);
    $loanRepositoryMock->shouldReceive('updateLoan')
        ->with(Mockery::on(function ($loan) {
            return $loan instanceof Loan;
        }), Mockery::on(function ($attributes) {
            return isset($attributes['return_date']) && $attributes['return_date'] instanceof Carbon;
        }))
        ->once();

    $loanService = createLoanService($loanRepositoryMock, $bookRepositoryMock, $userRepositoryMock);
    $response = $loanService->returnBook($book->id);

    expect($response)->toBeTrue();
});

it('fails to return a book with no active loan', function () {
    [$loanRepositoryMock, $bookRepositoryMock, $userRepositoryMock] = setUpMocks();

    $book = Book::factory()->create();

    $bookRepositoryMock->shouldReceive('findBookById')->with($book->id)->andReturn($book);
    $loanRepositoryMock->shouldReceive('findActiveLoanByBook')->with($book->id)->andReturn(null);

    $loanService = createLoanService($loanRepositoryMock, $bookRepositoryMock, $userRepositoryMock);
    $response = $loanService->returnBook($book->id);

    expect($response)->toBeFalse();
});

it('returns loans when there are active loans', function () {
    [$loanRepositoryMock, $bookRepositoryMock, $userRepositoryMock] = setUpMocks();

    $user = User::factory()->create();
    $loans = Loan::factory()->count(2)->create(['user_id' => $user->id]);

    $loanRepositoryMock->shouldReceive('getActiveLoansByUserId')
        ->with($user->id)
        ->andReturn($loans);

    $loanService = createLoanService($loanRepositoryMock, $bookRepositoryMock, $userRepositoryMock);
    $response = $loanService->getActiveLoans($user->id);

    expect($response)->toBeInstanceOf(Collection::class);
    expect($response->count())->toBe(2);
});

it('returns an empty collection when there are no active loans', function () {
    [$loanRepositoryMock, $bookRepositoryMock, $userRepositoryMock] = setUpMocks();

    $user = User::factory()->create();

    $loanRepositoryMock->shouldReceive('getActiveLoansByUserId')
        ->with($user->id)
        ->andReturn(collect());

    $loanService = createLoanService($loanRepositoryMock, $bookRepositoryMock, $userRepositoryMock);
    $response = $loanService->getActiveLoans($user->id);

    expect($response)->toBeInstanceOf(Collection::class);
    expect($response->isEmpty())->toBeTrue();
});
