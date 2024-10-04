<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Models\User;
use App\Services\BookService;
use Illuminate\Http\JsonResponse;

class BorrowController extends Controller
{
    public function borrow($bookId, $userId, BookService $bookService): JsonResponse
    {
        $book = Book::findOrFail($bookId);
        $user = User::findOrFail($userId);

        if ($bookService->borrowBook($book, $user)) {
            return response()->json(['message' => 'Book borrowed successfully!']);
        }

        return response()->json(['message' => 'Book is not available.'], 400);
    }
}
