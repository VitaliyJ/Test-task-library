<?php

namespace App\Services\Book;

use App\Models\Book;
use App\Services\Author\AuthorService;
use App\Services\Service;

class DetailsService implements Service
{
    private Book $book;

    public function __construct(Book $book)
    {
        $this->book = $book;
    }

    public function run(): array
    {
        $authors = AuthorService::list([$this->book->id]);

        return [
            'id' => $this->book->id,
            'name' => $this->book->name,
            'authors' => $authors[$this->book->id] ?? [],
        ];
    }
}
