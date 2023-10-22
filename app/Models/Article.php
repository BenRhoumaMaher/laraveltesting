<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    protected $fillable = ['title', 'body', 'author_id'];

    public function author()
    {
        return $this->belongsTo(User::class, 'author_id');
    }
}
