<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'author', 'isbn', 'publication_year'];

    public function isAvailable()
    {
        return !Loan::where('book_id', $this->id)
            ->whereNull('return_date')
            ->exists();
    }
}
