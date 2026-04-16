<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Book extends Model
{
    use SoftDeletes;

    protected $fillable = ['title', 'author', 'isbn', 'publisher', 'year', 'category', 'stock', 'cover', 'description'];

    protected $appends = ['cover_url'];

    public function loanItems()
    {
        return $this->hasMany(LoanItem::class);
    }

    public function getCoverUrlAttribute(): ?string
    {
        if (!$this->cover) {
            return null;
        }

        $cover = ltrim($this->cover, '/');

        if (str_starts_with($cover, 'http://') || str_starts_with($cover, 'https://') || str_starts_with($cover, '//') || str_starts_with($cover, 'data:')) {
            return $this->cover;
        }

        if (str_starts_with($cover, 'images/')) {
            return '/' . $cover;
        }

        if (str_starts_with($cover, 'public/storage/')) {
            return route('books.cover', ['path' => $cover]);
        }

        if (
            str_starts_with($cover, 'storage/')
            || str_starts_with($cover, 'images/')
            || str_starts_with($cover, 'covers/')
        ) {
            return route('books.cover', ['path' => $cover]);
        }

        return route('books.cover', ['path' => $cover]);
    }
}
