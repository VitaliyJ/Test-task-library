<?php

namespace App\Services\Book;

use App\Exceptions\StoreException;
use App\Models\Book;
use App\Services\Author\AuthorService;
use App\Services\Service;
use Illuminate\Support\Facades\DB;

class CreationService implements Service
{
    private string $name;
    private array $authors;
    private Book $book;
    private array $relations = [];

    public function __construct(string $name, array $authors)
    {
        $this->name = $name;
        $this->authors = $authors;
    }

    /**
     * @return int
     * @throws StoreException
     */
    public function run(): int
    {
        DB::beginTransaction();

        $this->createBook();
        $this->createAuthors();
        $this->createRelations();

        DB::commit();
        return $this->book->id;
    }

    /**
     * @throws StoreException
     */
    private function createBook(): void
    {
        $this->book = new Book;
        $this->book->name = $this->name;

        if ($this->book->save()) {
            return;
        }

        throw new StoreException(BookService::ERROR_MESSAGE_CAN_NOT_CREATE);
    }

    /**
     * @throws StoreException
     */
    private function createAuthors(): void
    {
        foreach ($this->authors as $author) {
            $authorID = AuthorService::create($author);
            $this->relations[] = [
                'book_id' => $this->book->id,
                'author_id' => $authorID,
                'created_at' => date('Y-m-d H:i', time()),
                'updated_at' => date('Y-m-d H:i', time()),
            ];
        }
    }

    /**
     * @throws StoreException
     */
    private function createRelations(): void
    {
        $inserted = (bool)DB::table('authors_to_books')->insert($this->relations);

        if ($inserted) {
            return;
        }

        throw new StoreException(BookService::ERROR_MESSAGE_CAN_NOT_CREATE_RELATIONS);
    }
}
