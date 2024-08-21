<?php

namespace App\Models;

use Filament\Facades\Filament;
use Spatie\Permission\Models\Role;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

class Company extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['name', 'slug', 'logo'];

    public function members(): BelongsToMany
    {
        return $this->belongsToMany(User::class);
    }

    public function locations(): HasMany
    {
        return $this->hasMany(Location::class);
    }

    public function roles(): HasMany
    {
        return $this->hasMany(Role::class);
    }

    public function keys(): HasManyThrough
    {
        return $this->hasManyThrough(Key::class, Location::class);
    }

    public function keyPickUpDropOffs() : HasMany
    {
        return $this->hasMany(KeyPickUpDropOff::class);
    }
}
