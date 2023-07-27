<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Visit extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'name',
        'employee_route',
        'employee',
        'store',
        'store_branch',
        'day',
        'status',
        'finished_at',
    ];

    public function store(): HasOne
    {
        return $this->hasOne(Store::class, 'id', 'store');
    }

    public function store_branch(): HasOne
    {
        return $this->hasOne(StoreBranch::class, 'id', 'store_branch');
    }

    public function employee(): BelongsTo
    {
        return $this->belongsTo(User::class, 'employee');
    }

    public function files(): HasMany
    {
        return $this->hasMany(VisitFile::class, 'visit');
    }

    public function stock_outs(): HasMany
    {
        return $this->hasMany(VisitStockOut::class, 'visit');
    }

    public function logs(): HasMany
    {
        return $this->hasMany(VisitLog::class, 'visit');
    }
}
