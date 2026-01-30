<?php
namespace App\Http\Controllers;

use App\Models\Schedule;
use App\Models\Classroom;
use App\Models\School;
use App\Models\Subject;
use App\Models\Teacher;
use App\Models\ScheduleSubjectTeacher;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Session;

class AdminScheduleController extends Controller
{
    private function canonicalDateFromDayOfWeek(int $dayOfWeekIso): string
    {
        $dayOfWeekIso = max(1, min(7, $dayOfWeekIso));

        // Store a canonical date per weekday to keep DB schema intact (weekly schedule).
        // 2020-01-06 is a Monday.
        $baseMonday = Carbon::create(2020, 1, 6)->startOfDay();
        return $baseMonday->copy()->addDays($dayOfWeekIso - 1)->toDateString();
    }

    private function currentRole(): string
    {
        return (string) Session::get('admin_role', 'admin');
    }

    private function allowedSchoolIds(): array
    {
        $role = $this->currentRole();

        if ($role === 'ultraadmin') {
            return School::query()->pluck('id')->map(fn ($id) => (int) $id)->all();
        }

        if ($role === 'superadmin') {
            return array_values(array_map('intval', array_filter((array) Session::get('admin_school_ids', []))));
        }

        $schoolId = Session::get('admin_school_id');
        return $schoolId ? [(int) $schoolId] : [];
    }

    private function ensureSchoolAllowed(int $schoolId): void
    {
        abort_if(!in_array($schoolId, $this->allowedSchoolIds(), true), 403);
    }

    public function index(Request $request)
    {
        $schoolId = $request->input('school_id');
        $classroomId = $request->input('classroom_id');
        $query = Schedule::with(['classroom', 'scheduleSubjectTeachers.subject', 'scheduleSubjectTeachers.teacher']);

        $query->whereIn('school_id', $this->allowedSchoolIds());

        // Default to current school context if no explicit filter.
        if (($schoolId === null || $schoolId === '') && Session::get('admin_school_id')) {
            $schoolId = (int) Session::get('admin_school_id');
        }

        if ($schoolId) {
            $schoolId = (int) $schoolId;
            $this->ensureSchoolAllowed($schoolId);
            $query->where('school_id', $schoolId);
        }
        if ($classroomId) $query->where('classroom_id', $classroomId);
        $schedules = $query->orderBy('date')->orderBy('start_time')->get();

        $schools = School::query()->whereIn('id', $this->allowedSchoolIds())->orderBy('name')->get();
        $classrooms = Classroom::query()
            ->when($schoolId, fn ($q) => $q->where('school_id', (int) $schoolId))
            ->whereIn('school_id', $this->allowedSchoolIds())
            ->orderBy('name')
            ->get();

        return view('admin.schedules.index', compact('schedules', 'schools', 'classrooms'));
    }
    public function create()
    {
        $schoolId = Session::get('admin_school_id') ? (int) Session::get('admin_school_id') : null;

        $schools = School::query()->whereIn('id', $this->allowedSchoolIds())->orderBy('name')->get();
        $classrooms = Classroom::query()
            ->when($schoolId, fn ($q) => $q->where('school_id', $schoolId))
            ->whereIn('school_id', $this->allowedSchoolIds())
            ->orderBy('name')
            ->get();
        $subjects = Subject::query()
            ->when($schoolId, fn ($q) => $q->where('school_id', $schoolId))
            ->whereIn('school_id', $this->allowedSchoolIds())
            ->orderBy('name')
            ->get();
        $teachers = Teacher::query()
            ->when($schoolId, fn ($q) => $q->where('school_id', $schoolId))
            ->whereIn('school_id', $this->allowedSchoolIds())
            ->orderBy('name')
            ->get();
        return view('admin.schedules.create', compact('schools', 'classrooms', 'subjects', 'teachers'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'school_id' => 'required|exists:schools,id',
            'classroom_id' => 'required|exists:classrooms,id',
            'day_of_week' => 'required|integer|min:1|max:7',
            'start_time' => 'required',
            'end_time' => 'required|after:start_time',
            'subject_ids' => 'required|array',
            'teacher_ids' => 'required|array',
        ]);

        $this->ensureSchoolAllowed((int) $request->input('school_id'));

        $schoolId = (int) $request->input('school_id');
        abort_if(!Classroom::query()->where('id', (int) $request->input('classroom_id'))->where('school_id', $schoolId)->exists(), 422, 'Kelas harus berasal dari sekolah yang dipilih.');

        $subjectIds = array_values(array_map('intval', (array) $request->input('subject_ids', [])));
        $teacherIds = array_values(array_map('intval', (array) $request->input('teacher_ids', [])));

        if (count($subjectIds) > 0) {
            $cnt = Subject::query()->whereIn('id', $subjectIds)->where('school_id', $schoolId)->count();
            abort_if($cnt !== count(array_unique($subjectIds)), 422, 'Semua mapel harus berasal dari sekolah yang dipilih.');
        }
        if (count($teacherIds) > 0) {
            $cnt = Teacher::query()->whereIn('id', $teacherIds)->where('school_id', $schoolId)->count();
            abort_if($cnt !== count(array_unique($teacherIds)), 422, 'Semua guru harus berasal dari sekolah yang dipilih.');
        }

        $date = $this->canonicalDateFromDayOfWeek((int) $request->input('day_of_week'));
        $schedule = Schedule::create([
            'school_id' => $schoolId,
            'classroom_id' => (int) $request->input('classroom_id'),
            'date' => $date,
            'start_time' => (string) $request->input('start_time'),
            'end_time' => (string) $request->input('end_time'),
            'note' => $request->input('note'),
        ]);
        // Multi mapel-guru
        foreach ($request->subject_ids as $i => $subjectId) {
            $teacherId = $request->teacher_ids[$i] ?? null;
            if ($teacherId) {
                ScheduleSubjectTeacher::create([
                    'schedule_id' => $schedule->id,
                    'mapel_id' => $subjectId,
                    'teacher_id' => $teacherId,
                ]);
            }
        }
        return redirect()->route('admin.schedules.index')->with('success', 'Jadwal berhasil ditambahkan');
    }

    public function edit(Schedule $schedule)
    {
        $this->ensureSchoolAllowed((int) $schedule->school_id);

        $schoolId = (int) $schedule->school_id;
        $schools = School::query()->whereIn('id', $this->allowedSchoolIds())->orderBy('name')->get();
        $classrooms = Classroom::query()->where('school_id', $schoolId)->orderBy('name')->get();
        $subjects = Subject::query()->where('school_id', $schoolId)->orderBy('name')->get();
        $teachers = Teacher::query()->where('school_id', $schoolId)->orderBy('name')->get();
        $selectedSubjects = $schedule->scheduleSubjectTeachers->pluck('mapel_id')->toArray();
        $selectedTeachers = $schedule->scheduleSubjectTeachers->pluck('teacher_id')->toArray();
        return view('admin.schedules.edit', compact('schedule', 'schools', 'classrooms', 'subjects', 'teachers', 'selectedSubjects', 'selectedTeachers'));
    }

    public function update(Request $request, Schedule $schedule)
    {
        $request->validate([
            'school_id' => 'required|exists:schools,id',
            'classroom_id' => 'required|exists:classrooms,id',
            'day_of_week' => 'required|integer|min:1|max:7',
            'start_time' => 'required',
            'end_time' => 'required|after:start_time',
            'subject_ids' => 'required|array',
            'teacher_ids' => 'required|array',
        ]);

        $this->ensureSchoolAllowed((int) $schedule->school_id);
        $this->ensureSchoolAllowed((int) $request->input('school_id'));

        $schoolId = (int) $request->input('school_id');
        abort_if(!Classroom::query()->where('id', (int) $request->input('classroom_id'))->where('school_id', $schoolId)->exists(), 422, 'Kelas harus berasal dari sekolah yang dipilih.');

        $subjectIds = array_values(array_map('intval', (array) $request->input('subject_ids', [])));
        $teacherIds = array_values(array_map('intval', (array) $request->input('teacher_ids', [])));

        if (count($subjectIds) > 0) {
            $cnt = Subject::query()->whereIn('id', $subjectIds)->where('school_id', $schoolId)->count();
            abort_if($cnt !== count(array_unique($subjectIds)), 422, 'Semua mapel harus berasal dari sekolah yang dipilih.');
        }
        if (count($teacherIds) > 0) {
            $cnt = Teacher::query()->whereIn('id', $teacherIds)->where('school_id', $schoolId)->count();
            abort_if($cnt !== count(array_unique($teacherIds)), 422, 'Semua guru harus berasal dari sekolah yang dipilih.');
        }

        $date = $this->canonicalDateFromDayOfWeek((int) $request->input('day_of_week'));
        $schedule->update([
            'school_id' => $schoolId,
            'classroom_id' => (int) $request->input('classroom_id'),
            'date' => $date,
            'start_time' => (string) $request->input('start_time'),
            'end_time' => (string) $request->input('end_time'),
            'note' => $request->input('note'),
        ]);
        // Sync mapel-guru
        $schedule->scheduleSubjectTeachers()->delete();
        foreach ($request->subject_ids as $i => $subjectId) {
            $teacherId = $request->teacher_ids[$i] ?? null;
            if ($teacherId) {
                ScheduleSubjectTeacher::create([
                    'schedule_id' => $schedule->id,
                    'mapel_id' => $subjectId,
                    'teacher_id' => $teacherId,
                ]);
            }
        }
        return redirect()->route('admin.schedules.index')->with('success', 'Jadwal berhasil diupdate');
    }

    public function destroy(Schedule $schedule)
    {
        $this->ensureSchoolAllowed((int) $schedule->school_id);
        $schedule->scheduleSubjectTeachers()->delete();
        $schedule->delete();
        return redirect()->route('admin.schedules.index')->with('success', 'Jadwal berhasil dihapus');
    }
}
