<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
class Classroom extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'code',
        'description',
        'status',
        'school_id',
        'teacher_id', // wali kelas
    ];
    public function school() { return $this->belongsTo(School::class); }
    public function students() { return $this->belongsToMany(Student::class, 'student_classroom'); }
    public function schedules() { return $this->hasMany(Schedule::class); }

    // Wali kelas (guru utama)
    public function waliKelas() { return $this->belongsTo(Teacher::class, 'teacher_id'); }

    // Banyak guru bisa mengajar banyak kelas
    public function teachers() { return $this->belongsToMany(Teacher::class, 'classroom_teacher'); }
}
