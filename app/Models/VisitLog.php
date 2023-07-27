<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class VisitLog extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'employee',
        'visit',
        'rel_id',
        'type',
        'note',
        'latitude',
        'longitude',
    ];

    public function file(): HasOne
    {
        return $this->hasOne(VisitFile::class, 'id', 'rel_id');
    }
}
