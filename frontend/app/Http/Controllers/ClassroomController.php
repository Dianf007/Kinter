<?php

namespace App\Http\Controllers;

use App\Http\Requests\ClassroomRequest;
use App\Models\Classroom;
use App\Models\School;
use App\Models\Teacher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class ClassroomController extends Controller
{
    private function currentRole(): string
    {
        return Session::get('admin_role', 'admin');
    }

    private function allowedSchoolIds(): array
    {
        $role = $this->currentRole();

        if ($role === 'superadmin') {
            return array_values(array_filter((array) Session::get('admin_school_ids', [])));
        }

        // admin: single school
        $schoolId = Session::get('admin_school_id');
        return $schoolId ? [(int) $schoolId] : [];
    }

    private function ensureSchoolAllowed(int $schoolId): void
    {
        abort_if(!in_array($schoolId, $this->allowedSchoolIds(), true), 403);
    }

    private function availableSchoolsForCurrent()
    {
        return School::query()
            ->whereIn('id', $this->allowedSchoolIds())
            ->orderBy('name')
            ->get();
    }

    private function applyScopeAndFilters($query, Request $request)
    {
        $query->whereIn('school_id', $this->allowedSchoolIds());

        // Default to current school context if no explicit filter.
        $currentSchoolId = Session::get('admin_school_id');
        if (($request->input('school_id') === null || $request->input('school_id') === '') && $currentSchoolId) {
            $query->where('school_id', (int) $currentSchoolId);
        }

        $schoolId = $request->input('school_id');
        if ($schoolId !== null && $schoolId !== '') {
            $schoolId = (int) $schoolId;
            $this->ensureSchoolAllowed($schoolId);
            $query->where('school_id', $schoolId);
        }

        $status = (string) $request->input('status', '');
        if ($status !== '') {
            abort_if(!in_array($status, ['aktif', 'non-aktif'], true), 422);
            $query->where('status', $status);
        }

        $q = trim((string) $request->input('q', $request->input('search', '')));
        if ($q !== '') {
            $query->where(function ($builder) use ($q) {
                $builder->where('name', 'like', "%{$q}%")
                    ->orWhere('code', 'like', "%{$q}%");
            });
        }

        return $query;
    }

    private function validateTeachersBelongToSchool(?int $schoolId, ?int $waliTeacherId, array $teacherIds): void
    {
        if (!$schoolId) {
            return;
        }

        if ($waliTeacherId) {
            $waliOk = Teacher::query()->where('id', $waliTeacherId)->where('school_id', $schoolId)->exists();
            abort_if(!$waliOk, 422, 'Wali kelas harus berasal dari sekolah yang dipilih.');
        }

        if (count($teacherIds) > 0) {
            $cnt = Teacher::query()->whereIn('id', $teacherIds)->where('school_id', $schoolId)->count();
            abort_if($cnt !== count(array_unique($teacherIds)), 422, 'Semua guru pengajar harus berasal dari sekolah yang dipilih.');
        }
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Classroom::query()->with(['school', 'waliKelas']);
        $this->applyScopeAndFilters($query, $request);

        $classrooms = $query->orderBy('id', 'desc')->paginate(10)->withQueryString();
        $schools = $this->availableSchoolsForCurrent();

        return view('admin.classrooms.index', [
            'classrooms' => $classrooms,
            'schools' => $schools,
            'currentRole' => $this->currentRole(),
        ]);
    }

    public function data(Request $request)
    {
        $baseQuery = Classroom::query()->with(['school', 'waliKelas']);
        $baseQuery->whereIn('school_id', $this->allowedSchoolIds());
        $recordsTotal = (clone $baseQuery)->count();

        $this->applyScopeAndFilters($baseQuery, $request);

        $searchValue = trim((string) $request->input('search.value', ''));
        if ($searchValue !== '') {
            $baseQuery->where(function ($q) use ($searchValue) {
                $q->where('name', 'like', "%{$searchValue}%")
                    ->orWhere('code', 'like', "%{$searchValue}%");
            });
        }

        $recordsFiltered = (clone $baseQuery)->count();

        $orderColIndex = (int) $request->input('order.0.column', 0);
        $orderDir = strtolower((string) $request->input('order.0.dir', 'asc')) === 'desc' ? 'desc' : 'asc';
        $columns = (array) $request->input('columns', []);
        $orderDataKey = $columns[$orderColIndex]['data'] ?? 'id';

        $orderMap = [
            'name' => 'name',
            'code' => 'code',
            'status' => 'status',
            'id' => 'id',
        ];
        $orderBy = $orderMap[$orderDataKey] ?? 'id';
        $baseQuery->orderBy($orderBy, $orderDir);

        $start = max(0, (int) $request->input('start', 0));
        $length = (int) $request->input('length', 10);
        if ($length < 1 || $length > 200) {
            $length = 10;
        }

        $items = $baseQuery->skip($start)->take($length)->get();

        $data = $items->map(function (Classroom $classroom) {
            return [
                'id' => $classroom->id,
                'name' => $classroom->name,
                'code' => $classroom->code ?? '-',
                'school' => $classroom->school->name ?? '-',
                'wali_kelas' => $classroom->waliKelas->name ?? '-',
                'status' => $classroom->status ?? 'aktif',
                'edit_url' => route('admin.classrooms.edit', $classroom),
                'destroy_url' => route('admin.classrooms.destroy', $classroom),
            ];
        })->values();

        return response()->json([
            'draw' => (int) $request->input('draw', 0),
            'recordsTotal' => $recordsTotal,
            'recordsFiltered' => $recordsFiltered,
            'data' => $data,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $schools = $this->availableSchoolsForCurrent();
        $teachers = Teacher::query()
            ->whereIn('school_id', $this->allowedSchoolIds())
            ->orderBy('name')
            ->get();
        return view('admin.classrooms.create', compact('schools', 'teachers'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ClassroomRequest $request)
    {
        $data = $request->validated();
        $schoolId = (int) $data['school_id'];
        $this->ensureSchoolAllowed($schoolId);

        $teacherIds = array_values(array_filter((array) $request->input('teachers', [])));
        $waliId = $data['teacher_id'] ? (int) $data['teacher_id'] : null;
        $this->validateTeachersBelongToSchool($schoolId, $waliId, array_map('intval', $teacherIds));

        $classroom = Classroom::create($data);
        $classroom->teachers()->sync($teacherIds);
        return redirect()->route('admin.classrooms.index')->with('success', 'Kelas berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $classroom = Classroom::with('teachers')->findOrFail($id);
        $this->ensureSchoolAllowed((int) $classroom->school_id);

        $schools = $this->availableSchoolsForCurrent();
        $teachers = Teacher::query()
            ->whereIn('school_id', $this->allowedSchoolIds())
            ->orderBy('name')
            ->get();
        return view('admin.classrooms.edit', compact('classroom', 'schools', 'teachers'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ClassroomRequest $request, $id)
    {
        $classroom = Classroom::findOrFail($id);
        $this->ensureSchoolAllowed((int) $classroom->school_id);

        $data = $request->validated();
        $schoolId = (int) $data['school_id'];
        $this->ensureSchoolAllowed($schoolId);

        $teacherIds = array_values(array_filter((array) $request->input('teachers', [])));
        $waliId = $data['teacher_id'] ? (int) $data['teacher_id'] : null;
        $this->validateTeachersBelongToSchool($schoolId, $waliId, array_map('intval', $teacherIds));

        $classroom->update($data);
        $classroom->teachers()->sync($teacherIds);
        return redirect()->route('admin.classrooms.index')->with('success', 'Kelas berhasil diupdate.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $classroom = Classroom::findOrFail($id);
        $this->ensureSchoolAllowed((int) $classroom->school_id);
        $classroom->teachers()->detach();
        $classroom->delete();
        return redirect()->route('admin.classrooms.index')->with('success', 'Kelas berhasil dihapus.');
    }
}
