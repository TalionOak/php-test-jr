<?php

use App\Repositories\LoanRepository;
use App\Services\UserService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Collection;

uses(Tests\TestCase::class, RefreshDatabase::class);

it('returns loans when there are active loans', function () {
    $loanRepositoryMock = Mockery::mock(LoanRepository::class);

    $loanRepositoryMock->shouldReceive('getActiveLoansByUserId')
        ->with(1)
        ->andReturn(collect(['loan1', 'loan2']));

    $userService = new UserService($loanRepositoryMock);
    $response = $userService->getActiveLoans(1);

    expect($response)->toBeInstanceOf(Collection::class);
    expect($response->toArray())->toEqual(['loan1', 'loan2']);
});

it('returns no active loans message when there are no active loans', function () {
    $loanRepositoryMock = Mockery::mock(LoanRepository::class);

    $loanRepositoryMock->shouldReceive('getActiveLoansByUserId')
        ->with(2)
        ->andReturn(collect());

    $userService = new UserService($loanRepositoryMock);

    $response = $userService->getActiveLoans(2);

    expect($response)->toBeInstanceOf(Collection::class);
    expect($response->isEmpty())->toBeTrue();
});
