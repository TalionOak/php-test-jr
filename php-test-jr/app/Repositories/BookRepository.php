<?php

namespace App\Repositories;

use App\Models\Book;
use Illuminate\Support\Facades\DB;

class BookRepository
{
    public function findBookById($bookId)
    {
        return Book::findOrFail($bookId);
    }

    public function getAvailableBooks()
    {
        return Book::leftJoin('loans', 'books.id', '=', 'loans.book_id')
            ->select('books.*', DB::raw('COUNT(CASE WHEN loans.return_date IS NULL THEN 1 END) as active_loans'))
            ->groupBy('books.id')
            ->havingRaw('active_loans < books.total_copies OR active_loans IS NULL')
            ->get();
    }
}
