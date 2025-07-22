<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuizAttemptPowerUp extends Model
{
    use HasFactory;

    protected $fillable = [
        'quiz_attempt_id', 'power_up_id', 'used_at'
    ];

    public function attempt()
    {
        return $this->belongsTo(QuizAttempt::class, 'quiz_attempt_id');
    }

    public function powerUp()
    {
        return $this->belongsTo(PowerUp::class);
    }
}
