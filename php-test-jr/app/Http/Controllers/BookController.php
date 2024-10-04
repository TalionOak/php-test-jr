<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Services\LoanService;
use Illuminate\Http\JsonResponse;

class BookController extends Controller
{
    public function availableBooks(LoanService $loanService): JsonResponse
    {
        $availableBooks = $loanService->getAvailableBooks();
        return response()->json($availableBooks);
    }
}
