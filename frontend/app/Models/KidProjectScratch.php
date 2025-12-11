<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
class KidProjectScratch extends Model
{
    use HasFactory;
    protected $table = 'kid_projects_scratch';
    protected $fillable = [
        'scratch_id',
        'title',
        'description',
        'instructions',
        'expired_dt',
    ];
    public $timestamps = false;
    protected $casts = [
        'expired_dt' => 'date',
    ];
    public function getIsExpiredAttribute(): bool
    {
        if (!$this->expired_dt) {
            return false;
        }
        return $this->expired_dt->isPast();
    }
}
