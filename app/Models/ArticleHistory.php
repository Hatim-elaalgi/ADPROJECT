<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ArticleHistory extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'article_id',
        'last_visited_at'
    ];

    protected $casts = [
        'last_visited_at' => 'datetime'
    ];

    public function article()
    {
        return $this->belongsTo(Article::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
