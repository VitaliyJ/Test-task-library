<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BookController;
use App\Http\Controllers\AuthorController;
use App\Helpers\APIResponseHelper;
use Illuminate\Http\Response;
use App\Services\Book\BookService;
use App\Services\Author\AuthorService;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::prefix('books')->group(function () {
    Route::get('/', [BookController::class, 'list']);
    Route::get('/{book}', [BookController::class, 'details'])->missing(function () {
        return APIResponseHelper::failed(
            BookService::ERROR_MESSAGE_BOOK_NOT_FOUND,
            Response::HTTP_NOT_FOUND
        );
    });
    Route::post('/', [BookController::class, 'create']);
    Route::match(['put', 'patch'], '/{book}', [BookController::class, 'edit'])->missing(function () {
        return APIResponseHelper::failed(
            BookService::ERROR_MESSAGE_BOOK_NOT_FOUND,
            Response::HTTP_NOT_FOUND
        );
    });
});

Route::prefix('authors')->group(function () {
    Route::get('/{author}/books', [AuthorController::class, 'books'])->missing(function () {
        return APIResponseHelper::failed(
            AuthorService::ERROR_MESSAGE_AUTHOR_NOT_FOUND,
            Response::HTTP_NOT_FOUND
        );
    });
    Route::post('/', [AuthorController::class, 'create']);
});
