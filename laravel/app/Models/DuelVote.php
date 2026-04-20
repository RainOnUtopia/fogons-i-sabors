<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class DuelVote extends Model
{
    use HasFactory;

    protected $fillable = [
        'duel_id',
        'user_id',
        'recipe_id',
        'rating',
    ];

    /**
     * Casts de les propietats del model.
     */
    protected function casts(): array
    {
        return [
            'rating' => 'integer',
        ];
    }

    /**
     * Relació amb el duel on s'ha castat el vot.
     */
    public function duel(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Duel::class);
    }

    /**
     * Relació amb l'usuari que ha votat.
     */
    public function user(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relació amb la recepta votada dins del duel.
     */
    public function recipe(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Recipe::class);
    }
}
