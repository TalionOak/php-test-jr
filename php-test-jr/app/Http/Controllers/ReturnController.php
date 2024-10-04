<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Services\BookService;
use Illuminate\Http\JsonResponse;

class ReturnController extends Controller
{
    public function return($bookId, BookService $bookService): JsonResponse
    {
        $book = Book::findOrFail($bookId);

        if ($bookService->returnBook($book)) {
            return response()->json(['message' => 'Book returned successfully!']);
        }

        return response()->json(['message' => 'Error returning the book.'], 400);
    }
}
