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
        'rating',
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
            'rating' => 'float',
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
        return $this->belongsToMany(User::class, 'favorites')->withTimestamps();
    }
}
