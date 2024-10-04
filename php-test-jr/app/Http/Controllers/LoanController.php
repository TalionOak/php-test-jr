<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Services\UserService;
use Illuminate\Http\JsonResponse;

class LoanController extends Controller
{
    protected $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function activeLoans($userId): JsonResponse
    {
        $loans = $this->userService->getActiveLoans($userId);

        if ($loans->isNotEmpty()) {
            return response()->json($loans);
        }

        return response()->json(['message' => 'No active loans.'], 404);
    }
}
