<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * Els atributs que poden ser assignats massivament.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'is_active',
        'avatar',
        'city',
        'country',
        'about_me',
    ];

    /**
     * Els atributs que s'han d'ocultar per a la serialització.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Obté els atributs que s'han de convertir.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_active' => 'boolean',
        ];
    }

    /**
     * Receptes que l'usuari ha marcat com a favorites.
     */
    public function favoriteRecipes(): BelongsToMany
    {
        return $this->belongsToMany(Recipe::class, 'favorites')
                    ->using(Favorite::class)
                    ->withTimestamps();
    }

    /**
     * Receptes creades per l'usuari.
     */
    public function recipes(): HasMany
    {
        return $this->hasMany(Recipe::class);
    }

    /**
     * Les puntuacions que l'usuari ha donat a les receptes.
     */
    public function ratings(): HasMany
    {
        return $this->hasMany(Rating::class);
    }
    /**
     * Els comentaris fets per l'usuari.
     */
    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }
}
