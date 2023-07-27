<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class StoreBranch extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'parent_id',
        'name',
        'address'
    ];

    public function store(): BelongsTo
    {
        return $this->belongsTo(Store::class, 'parent_id');
    }

    public function visits(): HasMany
    {
        return $this->hasMany(Visit::class, 'store_branch');
    }
}
