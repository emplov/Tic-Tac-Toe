<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Game extends Model
{
    use HasFactory;

    /**
     * @var array
     */
    protected $fillable = [
        'room_id',
        'game_ended'
    ];

    /**
     * @return HasMany
     */
    public function moves(): HasMany
    {
        return $this->hasMany(Move::class, 'game_id', '');
    }
}
