<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
class Schedule extends Model
{
    use HasFactory;
    protected $fillable = [
        'school_id', 'classroom_id', 'room_id', 'date', 'start_time', 'end_time', 'note'
    ];
    public function school() { return $this->belongsTo(School::class); }
    public function classroom() { return $this->belongsTo(Classroom::class); }
    public function room() { return $this->belongsTo(Room::class); }
    public function scheduleSubjectTeachers() { return $this->hasMany(ScheduleSubjectTeacher::class); }
    public function attendances() { return $this->hasMany(Attendance::class); }
}
