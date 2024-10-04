<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Services\LoanService;
use Illuminate\Http\JsonResponse;

class BorrowController extends Controller
{

    public function borrow(string $bookId, string $userId, LoanService $loanService): JsonResponse
    {
        $success = $loanService->borrowBook($bookId, $userId);

        if ($success) {
            return response()->json(['message' => 'Book borrowed successfully.']);
        }

        return response()->json(['message' => 'Book is not available.'], 400);
    }
}
