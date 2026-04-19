<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

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

    public function challenger(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class, 'challenger_id');
    }

    public function challenged(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class, 'challenged_id');
    }

    public function challengerRecipe(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Recipe::class, 'challenger_recipe_id');
    }

    public function challengedRecipe(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Recipe::class, 'challenged_recipe_id');
    }

    public function winnerUser(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class, 'winner_user_id');
    }

    public function winnerRecipe(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Recipe::class, 'winner_recipe_id');
    }

    public function votes(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(DuelVote::class);
    }
}
