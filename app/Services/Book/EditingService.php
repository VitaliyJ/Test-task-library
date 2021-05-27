<?php

namespace App\Services\Book;

use App\Exceptions\StoreException;
use App\Models\AuthorsToBook;
use App\Models\Book;
use App\Services\Author\AuthorService;
use App\Services\Service;
use Illuminate\Support\Facades\DB;

class EditingService implements Service
{
    public const ERROR_MESSAGE_CAN_NOT_EDIT = 'Book editing error';

    private Book $book;
    private string $name;
    private array $authors;
    private array $relations = [];

    public function __construct(Book $book, ?string $name, ?array $authors)
    {
        $this->book = $book;

        if (is_string($name)) {
            $this->name = $name;
        }

        if (is_array($authors)) {
            $this->authors = $authors;
        }
    }

    /**
     * @throws StoreException
     */
    public function run(): void
    {
        DB::beginTransaction();

        if (isset($this->name)) {
            $this->edit();
        }

        if (isset($this->authors)) {
            $this->setAuthors();
            $this->updateRelations();
        }

        DB::commit();
    }

    /**
     * @throws StoreException
     */
    private function edit(): void
    {
        if ($this->book->name === $this->name) {
            return;
        }

        $this->book->name = $this->name;
        if ($this->book->save()) {
            return;
        }

        throw new StoreException(self::ERROR_MESSAGE_CAN_NOT_EDIT);
    }

    /**
     * @throws StoreException
     */
    private function setAuthors(): void
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
    private function updateRelations(): void
    {
        AuthorsToBook::where('book_id', $this->book->id)->delete();
        $inserted = (bool)DB::table('authors_to_books')->insert($this->relations);

        if ($inserted) {
            return;
        }

        throw new StoreException(BookService::ERROR_MESSAGE_CAN_NOT_CREATE_RELATIONS);
    }
}
