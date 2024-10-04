<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\UserService;
use Illuminate\Http\JsonResponse;

class LoanController extends Controller
{
    public function activeLoans($userId, UserService $userService): JsonResponse
    {
        $user = User::findOrFail($userId);
        $loans = $userService->getActiveLoans($user);

        return response()->json($loans);
    }
}
