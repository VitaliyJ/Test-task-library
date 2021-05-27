<?php

namespace App\Services\Book;

use App\Models\Book;
use App\Services\Author\AuthorService;
use App\Services\Service;
use Illuminate\Support\Collection;

class ListService implements Service
{
    private int $authorID;

    public function __construct(int $authorID = null)
    {
        if (is_int($authorID)) {
            $this->authorID = $authorID;
        }
    }

    public function run(): array
    {
        $books = $this->books();
        $authors = AuthorService::list(array_keys($books->all()));

        return $books->map(function ($book, $bookID) use ($authors) {
            $item = $book;
            $item['authors'] = $authors[$bookID] ?? [];
            return $item;
        })->all();
    }

    private function books(): Collection
    {
        $books = Book::select(...$this->selectList());

        if (isset($this->authorID)) {
            $books->join('authors_to_books', 'authors_to_books.book_id', '=', 'books.id')
                ->where('authors_to_books.author_id', $this->authorID);
        }

        return $books->get()
            ->mapWithKeys(function ($book) {
                return [$book->id => $book];
            });
    }

    private function selectList(): array
    {
        return [
            'books.id',
            'books.name',
        ];
    }
}
