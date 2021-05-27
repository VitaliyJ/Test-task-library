<?php

namespace Tests\Feature;

use App\Models\Author;
use App\Services\Author\AuthorService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthorTest extends TestCase
{
    use RefreshDatabase;

    public function test_making_request_to_author_creation_with_valid_params(): void
    {
        $response = $this->postJson('/api/v1/authors', ['name' => 'test_author']);

        $response
            ->assertStatus(200)
            ->assertJson([
                'success' => true,
                'data' => [],
            ]);
    }

    public function test_making_request_to_author_creation_with_invalid_params(): void
    {
        $response = $this->postJson('/api/v1/authors', ['name' => '']);

        $response->assertStatus(400);
        $this->assertFalse($response['success']);
    }

    public function test_making_request_to_non_existent_author_book_list(): void
    {
        $response = $this->getJson('/api/v1/authors/1/books');

        $response
            ->assertStatus(404)
            ->assertJson([
                'success' => false,
                'message' => AuthorService::ERROR_MESSAGE_AUTHOR_NOT_FOUND,
            ]);
    }

    public function test_making_request_to_existent_author_book_list(): void
    {
        $author = Author::factory()->make();
        $author->save();

        $response = $this->getJson('/api/v1/authors/' . $author->id . '/books');

        $response
            ->assertStatus(200)
            ->assertJson([
                'success' => true,
                'data' => [],
            ]);
    }
}
