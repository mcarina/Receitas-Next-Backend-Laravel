<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class BucketImg extends Model
{
    use HasFactory;
    protected $table = 'bucket_img';

    protected $fillable = [
        'user_id',
        'recipe_id',
        'path',
        'url',
    ];
}
