<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PowerUp extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'description', 'type', 'icon', 'price_points'
    ];

    public function users()
    {
        return $this->belongsToMany(User::class, 'user_power_ups')->withPivot('quantity', 'source')->withTimestamps();
    }
}
