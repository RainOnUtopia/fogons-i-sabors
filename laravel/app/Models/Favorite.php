<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class Favorite extends Pivot
{
    /**
     * La taula associada amb aquest model pivot.
     *
     * @var string
     */
    protected $table = 'favorites';
}
