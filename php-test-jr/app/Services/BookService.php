<?php

namespace App\Services;

use App\Models\Book;
use App\Models\Loan;
use App\Models\User;

class BookService
{
    public function borrowBook(Book $book, User $user)
    {
        if ($book->isAvailable()) {
            Loan::create([
                'book_id' => $book->id,
                'user_id' => $user->id,
                'loan_date' => now()
            ]);
            return true;
        }
        return false;
    }

    public function returnBook(Book $book)
    {
        $loan = Loan::where('book_id', $book->id)
            ->whereNull('return_date')
            ->first();
        if ($loan) {
            $loan->return_date = now();
            $loan->save();
            return true;
        }
        return false;
    }
}
