<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BookCategories extends Model
{
    use HasFactory;
    protected $table = 'book_categories';
    protected $fillable = ['book_id', 'category_id'];

    public function book()
    {
        return $this->belongsTo(Book::class);
    }
     
    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
