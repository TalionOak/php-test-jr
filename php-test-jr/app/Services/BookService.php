<?php

namespace App\Services;

use App\Models\Book;

class BookService
{
    public function borrowBook($bookId, $userId)
    {
        $book = Book::find($bookId);
        if ($book) {
            return $book->borrow($userId) ? "Book borrowed successfully." : "Book is not available.";
        }
        return "Book not found.";
    }

    public function returnBook($bookId)
    {
        $book = Book::find($bookId);
        if ($book) {
            return $book->returnBook() ? "Book returned successfully." : "Book was not borrowed.";
        }
        return "Book not found.";
    }
}
