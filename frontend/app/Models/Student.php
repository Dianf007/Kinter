<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'name',
        'email',
        'student_id',
        'student_code',
        'class',
        'teacher_id',
        'avatar',
        'catatan'
    ];
    
    public function teacher()
    {
        return $this->belongsTo(User::class, 'teacher_id');
    }
    
    public function dailyReports()
    {
        return $this->hasMany(DailyReport::class);
    }
    
    public function getAvatarUrlAttribute()
    {
        return $this->avatar 
            ? asset('storage/' . $this->avatar)
            : 'https://ui-avatars.com/api/?name=' . urlencode($this->name) . '&background=667eea&color=fff';
    }
}
