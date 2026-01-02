<?php
namespace App\Http\Controllers;

use App\Models\Teacher;
use App\Models\School;
use App\Models\Subject;
use Illuminate\Http\Request;

class AdminTeacherController extends Controller
{
    public function index(Request $request)
    {
        $schools = School::all();
        $schoolId = $request->input('school_id', session('admin_school_id'));
        $query = Teacher::query()->with('subjects');
        if ($schoolId) {
            $query->where('school_id', $schoolId);
        }
        if ($request->filled('q')) {
            $q = $request->input('q');
            $query->where(function($sub) use ($q) {
                $sub->where('name', 'like', "%$q%")
                    ->orWhere('email', 'like', "%$q%")
                    ->orWhere('phone', 'like', "%$q%")
                ;
            });
        }
        
        // Sorting logic
        $sort = $request->input('sort', 'name');
        $order = $request->input('order', 'asc');
        
        // Validate sort and order to prevent SQL injection
        $allowedSorts = ['name', 'email', 'phone'];
        $sort = in_array($sort, $allowedSorts) ? $sort : 'name';
        $order = in_array($order, ['asc', 'desc']) ? $order : 'asc';
        
        $teachers = $query->orderBy($sort, $order)->paginate(15)->withQueryString();
        return view('admin.teachers.index', compact('teachers', 'schools', 'schoolId'));
    }

    public function create()
    {
        $schools = School::all();
        $currentSchoolId = session('admin_school_id');
        $subjects = collect(); // Empty collection initially, will be loaded via AJAX
        return view('admin.teachers.create', compact('schools', 'subjects', 'currentSchoolId'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'school_id' => 'required|exists:schools,id',
            'name' => 'required|string|max:100',
            'email' => 'nullable|email|max:100|unique:teachers,email',
            'phone' => 'nullable|string|max:20',
            'subject_ids' => 'nullable|array',
            'subject_ids.*' => 'exists:mapels,id',
        ]);
        
        $subjects = $data['subject_ids'] ?? [];
        unset($data['subject_ids']);
        
        $teacher = Teacher::create($data);
        if (!empty($subjects)) {
            $teacher->subjects()->attach($subjects);
        }
        
        return redirect()->route('admin.teachers.index')->with('success', 'Guru berhasil ditambahkan.');
    }

    public function edit(Teacher $teacher)
    {
        $schools = School::all();
        $subjects = Subject::where('school_id', $teacher->school_id)->get();
        $currentSchoolId = $teacher->school_id;
        return view('admin.teachers.edit', compact('teacher', 'schools', 'subjects', 'currentSchoolId'));
    }

    public function update(Request $request, Teacher $teacher)
    {
        $data = $request->validate([
            'school_id' => 'required|exists:schools,id',
            'name' => 'required|string|max:100',
            'email' => 'nullable|email|max:100|unique:teachers,email,' . $teacher->id,
            'phone' => 'nullable|string|max:20',
            'subject_ids' => 'nullable|array',
            'subject_ids.*' => 'exists:mapels,id',
        ]);
        
        $subjects = $data['subject_ids'] ?? [];
        unset($data['subject_ids']);
        
        $teacher->update($data);
        $teacher->subjects()->sync($subjects);
        
        return redirect()->route('admin.teachers.index')->with('success', 'Guru berhasil diupdate.');
    }

    public function getSchoolSubjects($schoolId)
    {
        $subjects = Subject::where('school_id', $schoolId)->get(['id', 'name']);
        return response()->json($subjects);
    }

    public function destroy(Teacher $teacher)
    {
        $teacher->subjects()->detach();
        $teacher->delete();
        return redirect()->route('admin.teachers.index')->with('success', 'Guru berhasil dihapus.');
    }
}