<?php

namespace App\Services;

use App\Repositories\LoanRepository;

class UserService
{
    protected $loanRepository;

    public function __construct(LoanRepository $loanRepository)
    {
        $this->loanRepository = $loanRepository;
    }

    public function getActiveLoans($userId)
    {
        return $this->loanRepository->getActiveLoansByUserId($userId);
    }
}
