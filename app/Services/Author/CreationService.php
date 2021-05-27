<?php

namespace App\Services\Author;

use App\Exceptions\StoreException;
use App\Models\Author;
use App\Services\Service;

class CreationService implements Service
{
    private string $name;
    private int $authorID;

    public function __construct(string $name)
    {
        $this->name = $name;
    }

    /**
     * @return int
     * @throws StoreException
     */
    public function run(): int
    {
        if ($this->authorAlreadyExists()) {
            return $this->authorID;
        }

        $this->create();
        return $this->authorID;
    }

    private function authorAlreadyExists(): bool
    {
        $author = Author::select('id')->where('name', $this->name)->first();

        if (is_null($author)) {
            return false;
        }

        $this->authorID = $author->id;
        return true;
    }

    /**
     * @throws StoreException
     */
    private function create(): void
    {
        $author = new Author;
        $author->name = $this->name;

        if ($author->save()) {
            $this->authorID = $author->id;
            return;
        }

        throw new StoreException(AuthorService::ERROR_MESSAGE_CAN_NOT_CREATE);
    }
}
