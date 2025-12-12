<?php
namespace App\Http\Controllers;

use App\Models\Schedule;
use App\Models\Classroom;
use App\Models\Room;
use App\Models\School;
use App\Models\Subject;
use App\Models\Teacher;
use App\Models\ScheduleSubjectTeacher;
use Illuminate\Http\Request;

class AdminScheduleController extends Controller
{
    public function index(Request $request)
    {
        $schoolId = $request->input('school_id');
        $classroomId = $request->input('classroom_id');
        $query = Schedule::with(['classroom', 'room', 'scheduleSubjectTeachers.subject', 'scheduleSubjectTeachers.teacher']);
        if ($schoolId) $query->where('school_id', $schoolId);
        if ($classroomId) $query->where('classroom_id', $classroomId);
        $schedules = $query->orderBy('date')->orderBy('start_time')->get();
        $schools = School::all();
        $classrooms = Classroom::all();
        return view('admin.schedules.index', compact('schedules', 'schools', 'classrooms'));
    }
    public function create()
    {
        $schools = School::all();
        $classrooms = Classroom::all();
        $rooms = Room::all();
        $subjects = Subject::all();
        $teachers = Teacher::all();
        return view('admin.schedules.create', compact('schools', 'classrooms', 'rooms', 'subjects', 'teachers'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'school_id' => 'required|exists:schools,id',
            'classroom_id' => 'required|exists:classrooms,id',
            'room_id' => 'required|exists:rooms,id',
            'date' => 'required|date',
            'start_time' => 'required',
            'end_time' => 'required|after:start_time',
            'subject_ids' => 'required|array',
            'teacher_ids' => 'required|array',
        ]);
        $schedule = Schedule::create($request->only(['school_id', 'classroom_id', 'room_id', 'date', 'start_time', 'end_time', 'note']));
        // Multi mapel-guru
        foreach ($request->subject_ids as $i => $subjectId) {
            $teacherId = $request->teacher_ids[$i] ?? null;
            if ($teacherId) {
                ScheduleSubjectTeacher::create([
                    'schedule_id' => $schedule->id,
                    'subject_id' => $subjectId,
                    'teacher_id' => $teacherId,
                ]);
            }
        }
        return redirect()->route('admin.schedules.index')->with('success', 'Jadwal berhasil ditambahkan');
    }

    public function edit(Schedule $schedule)
    {
        $schools = School::all();
        $classrooms = Classroom::all();
        $rooms = Room::all();
        $subjects = Subject::all();
        $teachers = Teacher::all();
        $selectedSubjects = $schedule->scheduleSubjectTeachers->pluck('subject_id')->toArray();
        $selectedTeachers = $schedule->scheduleSubjectTeachers->pluck('teacher_id')->toArray();
        return view('admin.schedules.edit', compact('schedule', 'schools', 'classrooms', 'rooms', 'subjects', 'teachers', 'selectedSubjects', 'selectedTeachers'));
    }

    public function update(Request $request, Schedule $schedule)
    {
        $request->validate([
            'school_id' => 'required|exists:schools,id',
            'classroom_id' => 'required|exists:classrooms,id',
            'room_id' => 'required|exists:rooms,id',
            'date' => 'required|date',
            'start_time' => 'required',
            'end_time' => 'required|after:start_time',
            'subject_ids' => 'required|array',
            'teacher_ids' => 'required|array',
        ]);
        $schedule->update($request->only(['school_id', 'classroom_id', 'room_id', 'date', 'start_time', 'end_time', 'note']));
        // Sync mapel-guru
        $schedule->scheduleSubjectTeachers()->delete();
        foreach ($request->subject_ids as $i => $subjectId) {
            $teacherId = $request->teacher_ids[$i] ?? null;
            if ($teacherId) {
                ScheduleSubjectTeacher::create([
                    'schedule_id' => $schedule->id,
                    'subject_id' => $subjectId,
                    'teacher_id' => $teacherId,
                ]);
            }
        }
        return redirect()->route('admin.schedules.index')->with('success', 'Jadwal berhasil diupdate');
    }

    public function destroy(Schedule $schedule)
    {
        $schedule->scheduleSubjectTeachers()->delete();
        $schedule->delete();
        return redirect()->route('admin.schedules.index')->with('success', 'Jadwal berhasil dihapus');
    }
}
