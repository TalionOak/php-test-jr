<?php

namespace App\Repositories;

use App\Models\Loan;

class LoanRepository
{
    public function createLoan(array $data)
    {
        return Loan::create($data);
    }

    public function findActiveLoanByBook($bookId)
    {
        return Loan::where('book_id', $bookId)
            ->whereNull('return_date')
            ->first();
    }

    public function updateLoan(Loan $loan, array $data)
    {
        return $loan->update($data);
    }

    public function getActiveLoansByUserId($userId)
    {
        return Loan::where('user_id', $userId)
            ->whereNull('return_date')
            ->get();
    }

    public function findAllLoansCount($bookId): int
    {
        return Loan::where('book_id', $bookId)
            ->whereNull('return_date')->count();
    }

    public function isBookAvailable($bookId)
    {
        return !$this->findActiveLoanByBook($bookId);
    }
}
