<?php

namespace App\Services\Book;

use App\Models\Book;
use App\Exceptions\StoreException;

class BookService
{
    public const ERROR_MESSAGE_BOOK_NOT_FOUND = 'Book not found';
    public const ERROR_MESSAGE_CAN_NOT_CREATE = 'Book creation error';
    public const ERROR_MESSAGE_CAN_NOT_CREATE_RELATIONS = 'Authors to book relations creation error';
    public const ERROR_MESSAGE_CAN_NOT_EDIT = 'Book editing error';

    private function __construct() {}

    public static function list(int $authorID = null): array
    {
        $service = is_int($authorID) ? new ListService($authorID) : new ListService;
        return $service->run();
    }

    public static function details(Book $book): array
    {
        return (new DetailsService($book))->run();
    }

    /**
     * @param string $name
     * @param array $authors
     * @return int
     * @throws StoreException
     */
    public static function create(string $name, array $authors): int
    {
        return (new CreationService($name, $authors))->run();
    }

    /**
     * @param Book $book
     * @param string|null $name
     * @param array|null $authors
     * @throws StoreException
     */
    public static function edit(Book $book, ?string $name, ?array $authors): void
    {
        (new EditingService($book, $name, $authors))->run();
    }
}
