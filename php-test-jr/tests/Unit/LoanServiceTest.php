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

    $bookMock = Mockery::mock();
    $bookMock->shouldReceive('isAvailable')->andReturn(true);
    $bookMock->id = 1;

    $userMock = Mockery::mock(User::class)->makePartial();
    $userMock->id = 1;

    $bookRepositoryMock->shouldReceive('findBookById')->with(1)->andReturn($bookMock);
    $userRepositoryMock->shouldReceive('findUserById')->with(1)->andReturn($userMock);
    $loanRepositoryMock->shouldReceive('createLoan')->once()->andReturnTrue();

    $loanService = createLoanService($loanRepositoryMock, $bookRepositoryMock, $userRepositoryMock);
    $response = $loanService->borrowBook(1, 1);

    expect($response)->toBeTrue();
});

it('fails to borrow an unavailable book', function () {
    [$loanRepositoryMock, $bookRepositoryMock, $userRepositoryMock] = setUpMocks();

    $bookMock = Mockery::mock();
    $bookMock->shouldReceive('isAvailable')->andReturn(false);

    $userMock = Mockery::mock(User::class);

    $bookRepositoryMock->shouldReceive('findBookById')->with(1)->andReturn($bookMock);
    $userRepositoryMock->shouldReceive('findUserById')->with(1)->andReturn($userMock);

    $loanService = createLoanService($loanRepositoryMock, $bookRepositoryMock, $userRepositoryMock);
    $response = $loanService->borrowBook(1, 1);

    expect($response)->toBeFalse();
});

it('returns a book successfully', function () {
    [$loanRepositoryMock, $bookRepositoryMock, $userRepositoryMock] = setUpMocks();

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

    $loanService = createLoanService($loanRepositoryMock, $bookRepositoryMock, $userRepositoryMock);
    $response = $loanService->returnBook(1);

    expect($response)->toBeTrue();
});

it('fails to return a book with no active loan', function () {
    [$loanRepositoryMock, $bookRepositoryMock, $userRepositoryMock] = setUpMocks();

    $bookMock = Mockery::mock();
    $bookMock->id = 1;

    $bookRepositoryMock->shouldReceive('findBookById')->with(1)->andReturn($bookMock);
    $loanRepositoryMock->shouldReceive('findActiveLoanByBook')->with(1)->andReturn(null);

    $loanService = createLoanService($loanRepositoryMock, $bookRepositoryMock, $userRepositoryMock);
    $response = $loanService->returnBook(1);

    expect($response)->toBeFalse();
});

it('returns loans when there are active loans', function () {
    [$loanRepositoryMock, $bookRepositoryMock, $userRepositoryMock] = setUpMocks();

    $loanRepositoryMock->shouldReceive('getActiveLoansByUserId')
        ->with(1)
        ->andReturn(collect(['loan1', 'loan2']));

    $loanService = createLoanService($loanRepositoryMock, $bookRepositoryMock, $userRepositoryMock);
    $response = $loanService->getActiveLoans(1);

    expect($response)->toBeInstanceOf(Collection::class);
    expect($response->toArray())->toEqual(['loan1', 'loan2']);
});

it('returns an empty collection when there are no active loans', function () {
    [$loanRepositoryMock, $bookRepositoryMock, $userRepositoryMock] = setUpMocks();

    $loanRepositoryMock->shouldReceive('getActiveLoansByUserId')
        ->with(2)
        ->andReturn(collect());

    $loanService = createLoanService($loanRepositoryMock, $bookRepositoryMock, $userRepositoryMock);
    $response = $loanService->getActiveLoans(2);

    expect($response)->toBeInstanceOf(Collection::class);
    expect($response->isEmpty())->toBeTrue();
});
