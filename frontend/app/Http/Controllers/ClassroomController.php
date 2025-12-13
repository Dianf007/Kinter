<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ClassroomController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Ambil classroom sesuai akses user (sementara: semua)
        $classrooms = \App\Models\Classroom::with(['school', 'waliKelas', 'teachers'])
            ->orderBy('name')
            ->paginate(15);
        $schools = \App\Models\School::orderBy('name')->get();
        return view('admin.classrooms.index', compact('classrooms', 'schools'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $schools = \App\Models\School::orderBy('name')->get();
        $teachers = \App\Models\Teacher::orderBy('name')->get();
        return view('admin.classrooms.create', compact('schools', 'teachers'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:100',
            'code' => 'nullable|string|max:50',
            'description' => 'nullable|string',
            'status' => 'required|in:aktif,non-aktif',
            'school_id' => 'required|exists:schools,id',
            'teacher_id' => 'nullable|exists:teachers,id',
            'teachers' => 'nullable|array',
            'teachers.*' => 'exists:teachers,id',
        ]);
        $classroom = \App\Models\Classroom::create($data);
        if ($request->has('teachers')) {
            $classroom->teachers()->sync($request->teachers);
        }
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
        $classroom = \App\Models\Classroom::with('teachers')->findOrFail($id);
        $schools = \App\Models\School::orderBy('name')->get();
        $teachers = \App\Models\Teacher::orderBy('name')->get();
        return view('admin.classrooms.edit', compact('classroom', 'schools', 'teachers'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $classroom = \App\Models\Classroom::findOrFail($id);
        $data = $request->validate([
            'name' => 'required|string|max:100',
            'code' => 'nullable|string|max:50',
            'description' => 'nullable|string',
            'status' => 'required|in:aktif,non-aktif',
            'school_id' => 'required|exists:schools,id',
            'teacher_id' => 'nullable|exists:teachers,id',
            'teachers' => 'nullable|array',
            'teachers.*' => 'exists:teachers,id',
        ]);
        $classroom->update($data);
        if ($request->has('teachers')) {
            $classroom->teachers()->sync($request->teachers);
        }
        return redirect()->route('admin.classrooms.index')->with('success', 'Kelas berhasil diupdate.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $classroom = \App\Models\Classroom::findOrFail($id);
        $classroom->teachers()->detach();
        $classroom->delete();
        return redirect()->route('admin.classrooms.index')->with('success', 'Kelas berhasil dihapus.');
    }
}
