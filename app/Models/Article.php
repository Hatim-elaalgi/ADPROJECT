<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    use HasFactory;

    // Attributs mass assignables
    protected $fillable = [
        'title',
        'content',
        'image_url',
        'status',
        'user_id',
        'theme_id',
    ];

    
    // Relations
    // Relation with the author (user)
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relation with the theme
    public function theme()
    {
        return $this->belongsTo(Theme::class);
    }

    public function commentaires()
    {
        return $this->hasMany(Commentaire::class);
    }

    public function ratings()
    {
        return $this->hasMany(Rating::class);
    }

    public function numeros()
    {
        return $this->belongsToMany(Numero::class, 'numeros_articles');
    }
}
