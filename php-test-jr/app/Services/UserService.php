<?php

namespace App\Services;

use App\Models\User;

class UserService
{
    public function getActiveLoans($userId)
    {
        $user = User::find($userId);
        if ($user) {
            $loans = $user->activeLoans;
            return $loans->isEmpty() ? "No active loans." : $loans;
        }
        return "User not found.";
    }
}
