<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Class AuthorsToBook
 * @package App\Models
 *
 * @property int    $id
 * @property int    $book_id
 * @property int    $author_id
 * @property Carbon $created_at
 * @property Carbon $updated_at
 */
class AuthorsToBook extends Model
{
    use HasFactory;

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
}
