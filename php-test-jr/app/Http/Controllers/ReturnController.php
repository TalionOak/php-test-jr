<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Services\LoanService;
use Illuminate\Http\JsonResponse;

class ReturnController extends Controller
{

    public function return(string $bookId, LoanService $loanService): JsonResponse
    {
        $success = $loanService->returnBook($bookId);

        if ($success) {
            return response()->json(['message' => 'Book returned successfully!']);
        }

        return response()->json(['message' => 'Error returning the book.'], 400);
    }
}
