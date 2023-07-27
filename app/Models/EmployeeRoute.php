<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class EmployeeRoute extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'employee',
        'store_branch',
        'day',
        'sort',
    ];

    public function employee(): BelongsTo
    {
        return $this->belongsTo(User::class, 'employee');
    }

    public function store_branch(): BelongsTo
    {
        return $this->belongsTo(StoreBranch::class, 'store_branch');
    }

    public function visit(): HasMany
    {
        return $this->hasMany(Visit::class, 'employee_route');
    }
}
