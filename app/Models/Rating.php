<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rating extends Model
{
    use HasFactory;

    // Champs mass assignables
    protected $fillable = [
        'rating',
        'user_id',
        'article_id',
    ];

    // Cast automatique des types
    protected $casts = [
        'rating' => 'integer',
    ];

    // Relation avec l'utilisateur
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relation avec l'article
    public function article()
    {
        return $this->belongsTo(Article::class);
    }
}


