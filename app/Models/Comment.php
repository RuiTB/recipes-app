<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Comment extends Model
{
    use HasFactory;


    protected $fillable = ['content', 'user_id', 'recipe_id'];

    protected $with = ['owner'];

    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function commentedOn(): BelongsTo
    {
        return $this->belongsTo(Recipe::class);
    }
}
