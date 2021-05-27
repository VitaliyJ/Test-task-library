<?php

namespace App\Services\Author;

use App\Models\AuthorsToBook;
use App\Services\Service;

class ListService implements Service
{
    private array $booksIDs;

    public function __construct(array $booksIDs)
    {
        $this->booksIDs = $booksIDs;
    }

    public function run(): array
    {
        return AuthorsToBook::select(...$this->selectList())
            ->join('authors', 'authors.id', '=', 'authors_to_books.author_id')
            ->whereIn('authors_to_books.book_id', $this->booksIDs)
            ->get()
            ->mapToGroups(function ($author, $key) {
                return [$author->book_id => [
                    'name' => $author->author_name,
                    'id' => $author->author_id,
                ]];
            })
            ->all();
    }

    private function selectList(): array
    {
        return [
            'authors.name as author_name',
            'authors_to_books.author_id',
            'authors_to_books.book_id',
        ];
    }
}
