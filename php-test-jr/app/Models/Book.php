<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'author', 'isbn', 'publication_year', 'total_copies'];

    public function loans()
    {
        return $this->hasMany(Loan::class);
    }
}
