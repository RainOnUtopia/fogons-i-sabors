<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Rating extends Model
{
    protected $fillable = [
        'user_id',
        'recipe_id',
        'rating',
    ];

    /**
     * L'usuari que ha donat la puntuació.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * La recepta puntuada.
     */
    public function recipe()
    {
        return $this->belongsTo(Recipe::class);
    }
}
