<?php
namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\School;
use App\Models\Classroom;
use Illuminate\Http\Request;

class AdminStudentController extends Controller
{
    public function index(Request $request)
    {
        $schools = School::all();
        $schoolId = $request->input('school_id', session('admin_school_id'));
        $query = Student::query();
        if ($schoolId) {
            $query->where('school_id', $schoolId);
        }
        if ($request->filled('q')) {
            $q = $request->input('q');
            $query->where(function($sub) use ($q) {
                $sub->where('name', 'like', "%$q%")
                    ->orWhere('student_id', 'like', "%$q%")
                    ->orWhere('email', 'like', "%$q%")
                    ->orWhere('class', 'like', "%$q%")
                ;
            });
        }
        
        // Sorting logic
        $sort = $request->input('sort', 'name');
        $order = $request->input('order', 'asc');
        
        // Validate sort and order to prevent SQL injection
        $allowedSorts = ['student_id', 'name', 'email', 'class', 'student_code'];
        $sort = in_array($sort, $allowedSorts) ? $sort : 'name';
        $order = in_array($order, ['asc', 'desc']) ? $order : 'asc';
        
        $students = $query->orderBy($sort, $order)->paginate(15)->withQueryString();
        return view('admin.students.index', compact('students', 'schools', 'schoolId'));
    }

    public function create()
    {
        $schools = School::all();
        $classrooms = Classroom::all();
        return view('admin.students.create', compact('schools', 'classrooms'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'school_id' => 'required|exists:schools,id',
            'name' => 'required|string|max:100',
            'student_id' => 'required|string|max:30|unique:students,student_id',
            'email' => 'required|email|unique:students,email',
            'password' => 'required|string|min:6',
            'class' => 'required|string|max:30',
            'gender' => 'nullable|in:L,P',
            'birth_date' => 'nullable|date',
            'phone' => 'nullable|string|max:30',
            'address' => 'nullable|string|max:255',
            'student_code' => 'nullable|string|max:30|unique:students,student_code',
        ]);

        // Auto-generate student_code if not provided
        if (empty($data['student_code'])) {
            $data['student_code'] = strtoupper(substr($data['name'], 0, 2)) . $data['student_id'];
        }

        // Hash password
        $data['password'] = \Illuminate\Support\Facades\Hash::make($data['password']);

        // Auto-assign to current authenticated admin
        $data['teacher_id'] = auth()->id() ?: 1;

        Student::create($data);
        return redirect()->route('admin.students.index')->with('success', 'Siswa berhasil ditambahkan.');
    }

    public function edit(Student $student)
    {
        $schools = School::all();
        $classrooms = Classroom::all();
        return view('admin.students.edit', compact('student', 'schools', 'classrooms'));
    }

    public function update(Request $request, Student $student)
    {
        $data = $request->validate([
            'school_id' => 'required|exists:schools,id',
            'name' => 'required|string|max:100',
            'student_id' => 'required|string|max:30|unique:students,student_id,' . $student->id,
            'email' => 'required|email|unique:students,email,' . $student->id,
            'password' => 'nullable|string|min:6',
            'class' => 'required|string|max:30',
            'gender' => 'nullable|in:L,P',
            'birth_date' => 'nullable|date',
            'phone' => 'nullable|string|max:30',
            'address' => 'nullable|string|max:255',
        ]);
        $student->update($data);
        return redirect()->route('admin.students.index')->with('success', 'Siswa berhasil diupdate.');
    }

    public function destroy(Student $student)
    {
        $student->delete();
        return redirect()->route('admin.students.index')->with('success', 'Siswa berhasil dihapus.');
    }
}
