<?php

namespace App\Models;

use Filament\Facades\Filament;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Key extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['name', 'location_id'];

    public function location(): BelongsTo
    {
        return $this->belongsTo(Location::class);
    }

    use \Znck\Eloquent\Traits\BelongsToThrough;
    public function company(): \Znck\Eloquent\Relations\BelongsToThrough
    {
        return $this->belongsToThrough(Company::class, Location::class);
    }
}
