<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Theme extends Model
{
    use HasFactory;

    // Constantes pour les statuts
    const STATUS_EN_COURS = 'en_cours';
    const STATUS_PUBLIE = 'publie';
    const STATUS_RETENU = 'retenu';
    const STATUS_REFUSE = 'refuse';

    // Champs mass assignables
    protected $fillable = [
        'title',
        'discription',
        'is_accept',
        'user_id',
        'status'
    ];

    // Cast automatique des types
    protected $casts = [
        'is_accept' => 'boolean',
        'status' => 'string'
    ];

    // Valeurs par défaut
    protected $attributes = [
        'is_accept' => false,
        'status' => self::STATUS_EN_COURS
    ];

    // Relation with theme manager
    public function themeManager()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Relation avec les articles
    public function articles()
    {
        return $this->hasMany(Article::class);
    }

    // Relation avec les subscribers (users abonnés au thème)
    public function users()
    {
        return $this->belongsToMany(User::class, 'themes_users_');
    }
}
