<?php

namespace App\Models;

use Database\Factories\RecipeFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Recipe extends Model
{
    use HasFactory;

    /**
     * Atributos que pueden ser asignación masiva
     */
    protected $fillable = [
        'title',
        'description',
        'cooking_time',
        'difficulty',
        'image',
        'tags',
        'ingredients',
        'chef_notes',
        'chef_name',
        'chef_avatar',
        'user_id',
    ];

    /**
     * Transformación de tipos de datos
     */
    protected function casts(): array
    {
        return [
            'tags' => 'json',
            'ingredients' => 'json',
            'cooking_time' => 'integer',
            'average_rating' => 'float',
            'ratings_count' => 'integer',
            'favorites_count' => 'integer',
        ];
    }

    /**
     * Relación: Una receta pertenece a un usuario
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Usuaris que han desat aquesta recepta com a favorita.
     */
    public function favoritedByUsers(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'favorites')
                    ->using(Favorite::class)
                    ->withTimestamps();
    }

    /**
     * Totes les puntuacions d'aquesta recepta.
     */
    public function ratings()
    {
        return $this->hasMany(Rating::class);
    }

    /**
     * Puntuacions donades per usuaris 
     */
    public function raters(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'ratings')->withPivot('rating')->withTimestamps();
    }
    /**
     * Tots els comentaris d'aquesta recepta.
     */
    public function comments(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Comment::class);
    }
    /**
     * Comentaris principals (arrel) d'aquesta recepta.
     */
    public function topLevelComments(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Comment::class)->whereNull('parent_id');
    }

    /**
     * Puntuació donada a aquesta recepta per l'usuari actualment autenticat
     */
    public function userRating(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(Rating::class)->where('user_id', auth()->id());
    }
}
