<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DailyReport extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'teacher_id',
        'student_id', 
        'activity_id',
        'activity_description',
        'performance_rating',
        'notes',
        'report_date'
    ];
    
    protected $casts = [
        'report_date' => 'date'
    ];
    
    public function teacher()
    {
        return $this->belongsTo(User::class, 'teacher_id');
    }
    
    public function student()
    {
        return $this->belongsTo(Student::class);
    }
    
    public function activity()
    {
        return $this->belongsTo(Activity::class);
    }
    
    public function getRatingTextAttribute()
    {
        return match($this->performance_rating) {
            1 => 'Kurang Baik',
            2 => 'Cukup Baik', 
            3 => 'Baik',
            4 => 'Sangat Baik',
            5 => 'Istimewa',
            default => 'Unknown'
        };
    }
    
    public function getRatingEmojiAttribute()
    {
        return match($this->performance_rating) {
            1 => '🔴',
            2 => '🟡',
            3 => '🟢', 
            4 => '🔵',
            5 => '🟣',
            default => '⚪'
        };
    }
    
    public function getRatingColorAttribute()
    {
        return match($this->performance_rating) {
            1 => 'bg-red-100 text-red-800',
            2 => 'bg-yellow-100 text-yellow-800',
            3 => 'bg-green-100 text-green-800',
            4 => 'bg-blue-100 text-blue-800', 
            5 => 'bg-purple-100 text-purple-800',
            default => 'bg-gray-100 text-gray-800'
        };
    }
}
