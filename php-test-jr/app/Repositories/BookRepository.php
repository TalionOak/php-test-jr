<?php

namespace App\Repositories;

use App\Models\Book;

class BookRepository
{
    public function findBookById($bookId)
    {
        return Book::findOrFail($bookId);
    }
}
