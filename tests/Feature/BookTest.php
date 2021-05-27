<?php

namespace Tests\Feature;

use App\Models\Book;
use App\Services\Book\BookService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BookTest extends TestCase
{
    use RefreshDatabase;

    public function test_making_request_to_book_list(): void
    {
        $response = $this->getJson('/api/v1/books');

        $response
            ->assertStatus(200)
            ->assertJson([
                'success' => true,
                'data' => [],
            ]);
    }

    public function test_making_request_to_existent_book_details(): void
    {
        $book = Book::factory()->make();
        $book->save();

        $response = $this->getJson('/api/v1/books/' . $book->id);

        $response->assertStatus(200);
        $this->assertTrue($response['success']);
    }

    public function test_making_request_to_non_existent_book_details(): void
    {
        $response = $this->getJson('/api/v1/books/1');

        $response
            ->assertStatus(404)
            ->assertJson([
                'success' => false,
                'message' => BookService::ERROR_MESSAGE_BOOK_NOT_FOUND,
            ]);
    }

    public function test_making_request_to_book_creation_with_valid_params(): void
    {
        $response = $this->postJson('/api/v1/books', ['name' => 'test_book', 'authors' => ['test_author']]);

        $response->assertStatus(200);
        $this->assertTrue($response['success']);
    }

    public function test_making_request_to_book_creation_with_invalid_params(): void
    {
        $response = $this->postJson('/api/v1/books', ['name' => '']);

        $response->assertStatus(400);
        $this->assertFalse($response['success']);
    }

    public function test_making_request_to_editing_non_existent_book(): void
    {
        $response = $this->putJson('/api/v1/books/2', ['name' => '']);

        $response->assertStatus(404);
        $this->assertFalse($response['success']);
    }

    public function test_making_request_to_editing_book_with_valid_params(): void
    {
        $book = Book::factory()->make();
        $book->save();

        $response = $this->putJson('/api/v1/books/' . $book->id, ['name' => 'test_book', 'authors' => ['test_author']]);

        $response->assertStatus(200);
        $this->assertTrue($response['success']);
    }

    public function test_making_request_to_editing_book_with_invalid_params(): void
    {
        $book = Book::factory()->make();
        $book->save();

        $response = $this->putJson('/api/v1/books/' . $book->id, ['authors' => ['']]);

        $response->assertStatus(400);
        $this->assertFalse($response['success']);
    }
}
