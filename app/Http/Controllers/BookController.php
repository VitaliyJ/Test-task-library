<?php

namespace App\Http\Controllers;

use App\Helpers\APIResponseHelper;
use App\Models\Book;
use App\Services\Book\BookService;
use App\Validators\BookCreationValidator;
use App\Validators\BookEditingValidator;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Exception;

class BookController extends Controller
{
    public function list(): Response
    {
        return APIResponseHelper::success(BookService::list());
    }

    public function details(Book $book): Response
    {
        return APIResponseHelper::success(BookService::details($book));
    }

    public function create(Request $request): Response
    {
        $validator = BookCreationValidator::make($request->all());
        if ($validator->fails()) {
            return APIResponseHelper::failed($validator->messages()->first());
        }

        try {
            $bookID = BookService::create($request->name, $request->authors);
        } catch (Exception $e) {
            return APIResponseHelper::failed($e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return APIResponseHelper::success(['id' => $bookID]);
    }

    public function edit(Request $request, Book $book): Response
    {
        $validator = BookEditingValidator::make($request->all());
        if ($validator->fails()) {
            return APIResponseHelper::failed($validator->messages()->first());
        }

        try {
            BookService::edit($book, $request->name, $request->authors);
        } catch (Exception $e) {
            return APIResponseHelper::failed($e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return APIResponseHelper::success();
    }
}
