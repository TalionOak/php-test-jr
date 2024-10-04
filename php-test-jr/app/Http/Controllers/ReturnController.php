<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Services\BookService;
use Illuminate\Http\JsonResponse;

class ReturnController extends Controller
{
    protected $bookService;

    public function __construct(BookService $bookService)
    {
        $this->bookService = $bookService;
    }

    public function return($bookId): JsonResponse
    {
        $success = $this->bookService->returnBook($bookId);

        if ($success) {
            return response()->json(['message' => 'Book returned successfully!']);
        }

        return response()->json(['message' => 'Error returning the book.'], 400);
    }
}
