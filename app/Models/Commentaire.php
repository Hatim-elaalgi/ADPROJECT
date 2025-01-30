<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Commentaire extends Model
{
    use HasFactory;

    // Champs mass assignables
    protected $fillable = [
        'message',
        'article_id',
        'user_id',
        'visibility'
    ];

    protected $attributes = [
        'visibility' => 'visible'
    ];

    // Relations
    public function article()
    {
        return $this->belongsTo(Article::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Scopes
    public function scopeVisible($query)
    {
        return $query->where('visibility', 'visible');
    }

    public function scopeHidden($query)
    {
        return $query->where('visibility', 'hidden');
    }
}
