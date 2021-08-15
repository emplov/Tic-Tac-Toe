<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * @method static where(string $string, $room_id)
 */
class Room extends Model
{
    use HasFactory;

    /**
     * @var array
     */
    protected $fillable = [
        'name',
        'user_1_id',
        'user_2_id',
        'turn',
    ];

    /**
     * @var array
     */
    protected $casts = [
        'name' => 'string',
        'user_1_id' => 'integer',
        'user_2_id' => 'integer',
        'turn' => 'string',
    ];

    /**
     * @return BelongsTo
     */
    public function user_1(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_1_id', 'id');
    }

    /**
     * @return BelongsTo
     */
    public function user_2(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_2_id', 'id');
    }

    /**
     * @return HasMany
     */
    public function games(): HasMany
    {
        return $this->hasMany(Game::class, 'room_id', 'id');
    }
}
