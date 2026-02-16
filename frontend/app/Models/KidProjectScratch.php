<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
class KidProjectScratch extends Model
{
    use HasFactory;
    protected $table = 'kid_projects_scratch';
    protected $fillable = [
        'user_id',
        'scratch_id',
        'title',
        'description',
        'instructions',
        'expired_dt',
        'is_published',
    ];
    protected $casts = [
        'expired_dt' => 'date',
        'is_published' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function student()
    {
        return $this->belongsTo(Student::class, 'user_id');
    }

    public function getIsExpiredAttribute(): bool
    {
        if (!$this->expired_dt) {
            return false;
        }
        return $this->expired_dt->isPast();
    }
}
