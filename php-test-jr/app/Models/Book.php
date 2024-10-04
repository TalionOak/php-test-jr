<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'author', 'isbn', 'publication_year'];

    public function borrow($userId)
    {
        if ($this->isAvailable()) {
            Loan::create([
                'book_id' => $this->id,
                'user_id' => $userId,
                'loan_date' => now()
            ]);
            return true;
        }
        return false;
    }

    public function returnBook()
    {
        $loan = Loan::where('book_id', $this->id)
            ->whereNull('return_date')
            ->first();
        if ($loan) {
            $loan->return_date = now();
            $loan->save();
            return true;
        }
        return false;
    }

    public function isAvailable()
    {
        return !Loan::where('book_id', $this->id)
            ->whereNull('return_date')
            ->exists();
    }
}
