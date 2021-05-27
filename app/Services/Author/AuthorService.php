<?php

namespace App\Services\Author;

use App\Exceptions\StoreException;

class AuthorService
{
    public const ERROR_MESSAGE_AUTHOR_NOT_FOUND = 'Author not found';
    public const ERROR_MESSAGE_CAN_NOT_CREATE = 'Author creation error';

    private function __construct() {}

    public static function list(array $booksIDs): array
    {
        return (new ListService($booksIDs))->run();
    }

    /**
     * @param string $name
     * @return int
     * @throws StoreException
     */
    public static function create(string $name): int
    {
        return (new CreationService($name))->run();
    }
}
