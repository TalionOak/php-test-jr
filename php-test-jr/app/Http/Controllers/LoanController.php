<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Services\LoanService;
use Illuminate\Http\JsonResponse;

class LoanController extends Controller
{
    public function activeLoans($userId, LoanService $loanService): JsonResponse
    {
        $loans = $loanService->getActiveLoans($userId);

        if ($loans->isNotEmpty()) {
            return response()->json($loans);
        }

        return response()->json(['message' => 'No active loans.'], 404);
    }
}
