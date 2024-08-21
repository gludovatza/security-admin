<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class KeyPickUpDropOff extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'key_id',

        'pick_up_time',
        'pick_up_user_id',
        'pick_up_sign',
        'pick_up_security_sign',

        'drop_off_time',
        'drop_off_user_id',
        'drop_off_sign',
        'drop_off_security_sign',

        'company_id',
    ];

    // use \Znck\Eloquent\Traits\BelongsToThrough;
    // public function company(): \Znck\Eloquent\Relations\BelongsToThrough
    // {
    //     return $this->belongsToThrough(Company::class, [
    //         Location::class,
    //         Key::class
    //     ]);
    // }

    public function key(): BelongsTo
    {
        return $this->belongsTo(Key::class);
    }

    public function pickUpUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'pick_up_user_id'); // aki felveszi a kulcsot
    }

    public function dropOffUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'drop_off_user_id'); // aki leadja a kulcsot
    }

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }
}
