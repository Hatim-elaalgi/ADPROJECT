<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    const ROLE_ADMIN = 'admin';
    const ROLE_SUBSCRIBER = 'subscriber';
    const ROLE_RESPONSABLE_THEME = 'responsable_theme';
    const ROLE_INVITE = 'invite';

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // Relation with managed themes (for theme managers)
    public function themes()
    {
        return $this->hasMany(Theme::class, 'user_id');
    }

    // Relation with subscribed themes (for subscribers)
    public function subscribedThemes()
    {
        return $this->belongsToMany(Theme::class, 'themes_users_');
    }

    // Get managed themes for responsable_theme
    public function managedThemes()
    {
        if ($this->role === self::ROLE_RESPONSABLE_THEME) {
            return $this->themes();
        }
        return null;
    }

}