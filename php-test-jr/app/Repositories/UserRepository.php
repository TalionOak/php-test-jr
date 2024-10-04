<?php

namespace App\Repositories;

use App\Models\User;

class UserRepository
{
    public function findUserById($userId)
    {
        return User::findOrFail($userId);
    }
}
