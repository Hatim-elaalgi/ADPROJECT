<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Numero extends Model
{
    use HasFactory;

    // Champs mass assignables
    protected $fillable = [
        'title',
        'date',
        'ispublic',
    ];

    // Cast automatique des types
    protected $casts = [
        'date' => 'datetime',
        'ispublic' => 'boolean',
    ];

    // Relation avec Article via une table pivot
    public function articles()
    {
        return $this->belongsToMany(Article::class, 'numeros_articles');
    }
}
