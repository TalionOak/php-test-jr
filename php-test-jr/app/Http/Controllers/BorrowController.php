<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Services\BookService;
use Illuminate\Http\JsonResponse;

class BorrowController extends Controller
{
    protected $bookService;

    public function __construct(BookService $bookService)
    {
        $this->bookService = $bookService;
    }

    public function borrow(string $bookId, string $userId): JsonResponse
    {
        $success = $this->bookService->borrowBook($bookId, $userId);

        if ($success) {
            return response()->json(['message' => 'Book borrowed successfully.']);
        }

        return response()->json(['message' => 'Book is not available.'], 400);
    }
}
