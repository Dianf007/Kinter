<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserPowerUp extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'power_up_id', 'quantity', 'source'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function powerUp()
    {
        return $this->belongsTo(PowerUp::class);
    }
}
