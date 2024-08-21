<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Location extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['name', 'address', 'company_id'];

    public function company() : BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    public function keys() : HasMany
    {
        return $this->hasMany(Key::class);
    }
}
