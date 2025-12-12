<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
class Classroom extends Model
{
    use HasFactory;
    protected $fillable = ['name', 'school_id'];
    public function school() { return $this->belongsTo(School::class); }
    public function students() { return $this->belongsToMany(Student::class, 'student_classroom'); }
    public function schedules() { return $this->hasMany(Schedule::class); }
}
