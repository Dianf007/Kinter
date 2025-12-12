<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
class ScheduleSubjectTeacher extends Model
{
    use HasFactory;
    protected $table = 'schedule_subject_teacher';
    protected $fillable = ['schedule_id', 'subject_id', 'teacher_id'];
    public function schedule() { return $this->belongsTo(Schedule::class); }
    public function subject() { return $this->belongsTo(Subject::class); }
    public function teacher() { return $this->belongsTo(Teacher::class); }
}
