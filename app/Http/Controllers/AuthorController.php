<?php

namespace App\Http\Controllers;

use App\Helpers\APIResponseHelper;
use App\Models\Author;
use App\Services\Author\AuthorService;
use App\Services\Book\BookService;
use App\Validators\AuthorCreationValidator;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Exception;

class AuthorController extends Controller
{
    public function books(Author $author): Response
    {
        return APIResponseHelper::success(BookService::list($author->id));
    }

    public function create(Request $request): Response
    {
        $validator = AuthorCreationValidator::make($request->all());
        if ($validator->fails()) {
            return APIResponseHelper::failed($validator->messages()->first());
        }

        try {
            $authorID = AuthorService::create($request->name);
        } catch (Exception $e) {
            return APIResponseHelper::failed($e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return APIResponseHelper::success(['id' => $authorID]);
    }
}
