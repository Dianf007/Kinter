<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Auth\Authenticatable as AuthenticatableTrait;
use Laravel\Passport\HasApiTokens;

class Student extends Model implements Authenticatable
{
    use HasFactory, HasApiTokens, AuthenticatableTrait;
    
    protected $fillable = [
        'name',
        'email',
        'student_id',
        'student_code',
        'class',
        'teacher_id',
        'avatar',
        'catatan',
        'password',
        'birth_date',
        'gender',
        'phone',
        'address'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];
    
    public function teacher()
    {
        return $this->belongsTo(User::class, 'teacher_id');
    }
    
    public function dailyReports()
    {
        return $this->hasMany(DailyReport::class);
    }

    public function projects()
    {
        return $this->hasMany(KidProjectScratch::class, 'user_id');
    }
    
    public function getAvatarUrlAttribute()
    {
        return $this->avatar 
            ? asset('storage/' . $this->avatar)
            : 'https://ui-avatars.com/api/?name=' . urlencode($this->name) . '&background=667eea&color=fff';
    }
}
