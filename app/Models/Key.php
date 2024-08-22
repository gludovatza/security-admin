<?php

namespace App\Models;

use Filament\Facades\Filament;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Key extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['name', 'location_id', 'available'];

    public function location(): BelongsTo
    {
        return $this->belongsTo(Location::class);
    }

    use \Znck\Eloquent\Traits\BelongsToThrough;
    public function company(): \Znck\Eloquent\Relations\BelongsToThrough
    {
        return $this->belongsToThrough(Company::class, Location::class);
    }

    public function pickUpDropOffEvents() : HasMany
    {
        return $this->hasMany(KeyPickUpDropOff::class);
    }
}
