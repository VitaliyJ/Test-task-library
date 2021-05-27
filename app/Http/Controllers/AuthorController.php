<?php

namespace App\Http\Controllers;

use App\Helpers\APIResponseHelper;
use App\Models\Author;
use App\Services\Book\BookService;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AuthorController extends Controller
{
    public function books(Author $author): Response
    {
        return APIResponseHelper::success(BookService::list($author->id));
    }

    public function create(Request $request): Response
    {

    }
}
