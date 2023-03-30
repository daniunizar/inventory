<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Tag extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'label',
        'description'
    ];

    //RELATIONSHIPS
    /**
     * The boardgames that belong to the tag.
     */
    public function boargames(): BelongsToMany
    {
        return $this->belongsToMany(Boardgame::class);
    }
    //METHODS
}
