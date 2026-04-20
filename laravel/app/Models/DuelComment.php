<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class DuelComment extends Model
{
    protected $fillable = [
        'user_id',
        'duel_id',
        'parent_id',
        'content',
        'is_deleted',
    ];

    /**
     * Casts de les propietats del model.
     */
    protected function casts(): array
    {
        return [
            'is_deleted' => 'boolean',
        ];
    }

    /**
     * Relació amb l'usuari que ha realitzat el comentari.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relació amb el duel al qual pertany el comentari.
     */
    public function duel(): BelongsTo
    {
        return $this->belongsTo(Duel::class);
    }

    /**
     * Relació amb el comentari pare en cas de ser una resposta.
     */
    public function parent(): BelongsTo
    {
        return $this->belongsTo(DuelComment::class, 'parent_id');
    }

    /**
     * Relació amb les respostes associades a aquest comentari.
     */
    public function replies(): HasMany
    {
        return $this->hasMany(DuelComment::class, 'parent_id');
    }
}
