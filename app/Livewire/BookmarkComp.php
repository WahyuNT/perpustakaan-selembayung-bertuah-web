<?php

namespace App\Livewire;

use App\Models\Bookmark;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithPagination;
use Tymon\JWTAuth\Facades\JWTAuth;

class BookmarkComp extends Component
{
    use WithPagination;
    public $search;

    public function render()
    {
        $data = Bookmark::select('bookmark.*')
            ->addSelect(DB::raw('(
            SELECT GROUP_CONCAT(authors.name SEPARATOR ", ") 
            FROM book_authors 
            JOIN authors ON authors.id = book_authors.author_id 
            WHERE book_authors.book_id = bookmark.book_id
        ) as authors'))
            ->when($this->search, function ($query) {
                $search = '%' . $this->search . '%';
                $query->whereHas('book', function ($query) use ($search) {
                    $query->where('title', 'like', $search);
                })
                    ->orWhereRaw('(SELECT GROUP_CONCAT(authors.name SEPARATOR ", ") 
                                FROM book_authors 
                                JOIN authors ON authors.id = book_authors.author_id 
                                WHERE book_authors.book_id = bookmark.book_id) like ?', [$search]);
            })
            ->orderBy('created_at', 'desc')
            ->paginate(30);


        return view('livewire.bookmark-comp', compact('data'))->extends('layouts.master');
    }
    public function removeBookmark($id)
    {
        $user = JWTAuth::parseToken()->authenticate();
        Bookmark::where('user_id', $user->id)->where('book_id', $id)->delete();
    }
}
