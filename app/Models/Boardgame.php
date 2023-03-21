<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Boardgame extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'label',
        'description',
        'editorial',
        'min_players',
        'max_players',
        'min_age',
        'max_age',
        'user_id',
    ];

    //RELATIONSHIPS
    /**
     * Get the user that owns the boardgame.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    //METHODS
}
