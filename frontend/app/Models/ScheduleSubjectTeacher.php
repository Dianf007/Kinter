<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
class ScheduleMapelTeacher extends Model
{
    use HasFactory;
    protected $table = 'schedule_mapel_teachers';
    protected $fillable = ['schedule_id', 'mapel_id', 'teacher_id'];
    public function schedule() { return $this->belongsTo(Schedule::class); }
    public function mapel() { return $this->belongsTo(Mapel::class); }
    public function teacher() { return $this->belongsTo(Teacher::class); }
}
