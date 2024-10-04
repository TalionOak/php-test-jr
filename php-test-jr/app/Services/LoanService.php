<?php

namespace App\Services;

use App\Repositories\BookRepository;
use App\Repositories\LoanRepository;
use App\Repositories\UserRepository;

class LoanService
{
    protected $loanRepository;
    protected $bookRepository;
    protected $userRepository;

    public function __construct(
        LoanRepository $loanRepository,
        BookRepository $bookRepository,
        UserRepository $userRepository
    ) {
        $this->loanRepository = $loanRepository;
        $this->bookRepository = $bookRepository;
        $this->userRepository = $userRepository;
    }


    public function borrowBook($bookId, $userId)
    {
        $book = $this->bookRepository->findBookById($bookId);
        $user = $this->userRepository->findUserById($userId);

        if ($this->loanRepository->isBookAvailable($bookId)) {
            $this->loanRepository->createLoan([
                'book_id' => $book->id,
                'user_id' => $user->id,
                'loan_date' => now()
            ]);
            return true;
        }

        return false;
    }

    public function returnBook($bookId)
    {
        $book = $this->bookRepository->findBookById($bookId);
        $loan = $this->loanRepository->findActiveLoanByBook($book->id);

        if ($loan) {
            $this->loanRepository->updateLoan($loan, ['return_date' => now()]);
            return true;
        }

        return false;
    }

    public function getActiveLoans($userId)
    {
        return $this->loanRepository->getActiveLoansByUserId($userId);
    }
}
