<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\DuelComment;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Duel extends Model
{
    use HasFactory;

    protected $fillable = [
        'challenger_id',
        'challenger_recipe_id',
        'challenged_id',
        'challenged_recipe_id',
        'challenger_average_rating',
        'challenged_average_rating',
        'challenger_votes_count',
        'challenged_votes_count',
        'status',
        'duel_result',
        'start_date',
        'end_date',
        'winner_recipe_id',
        'winner_user_id',
    ];

    /**
     * Casts de les propietats del model.
     */
    protected function casts(): array
    {
        return [
            'challenger_average_rating' => 'float',
            'challenged_average_rating' => 'float',
            'challenger_votes_count' => 'integer',
            'challenged_votes_count' => 'integer',
            'start_date' => 'datetime',
            'end_date' => 'datetime',
        ];
    }

    /**
     * Relació amb l'usuari reptador.
     */
    public function challenger(): BelongsTo
    {
        return $this->belongsTo(User::class, 'challenger_id');
    }

    /**
     * Relació amb l'usuari reptat.
     */
    public function challenged(): BelongsTo
    {
        return $this->belongsTo(User::class, 'challenged_id');
    }

    /**
     * Relació amb la recepta del reptador.
     */
    public function challengerRecipe(): BelongsTo
    {
        return $this->belongsTo(Recipe::class, 'challenger_recipe_id');
    }

    /**
     * Relació amb la recepta del reptat.
     */
    public function challengedRecipe(): BelongsTo
    {
        return $this->belongsTo(Recipe::class, 'challenged_recipe_id');
    }

    /**
     * Relació amb l'usuari guanyador (si el duel ha finalitzat).
     */
    public function winnerUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'winner_user_id');
    }

    /**
     * Relació amb la recepta guanyadora del duel.
     */
    public function winnerRecipe(): BelongsTo
    {
        return $this->belongsTo(Recipe::class, 'winner_recipe_id');
    }

    /**
     * Relació amb tots els vots registrats en aquest duel.
     */
    public function votes(): HasMany
    {
        return $this->hasMany(DuelVote::class);
    }

    /**
     * Relació amb tots els comentaris del duel.
     */
    public function comments(): HasMany
    {
        return $this->hasMany(DuelComment::class);
    }

    /**
     * Relació amb els comentaris de primer nivell (no respostes).
     */
    public function topLevelComments(): HasMany
    {
        return $this->hasMany(DuelComment::class)->whereNull('parent_id');
    }
}
