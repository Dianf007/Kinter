<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Student;
use App\Models\DailyReport;
use App\Models\Activity;
use Carbon\Carbon;

class TeacherReportController extends Controller
{
    public function index()
    {
        $todayReports = DailyReport::whereDate('report_date', today())
            ->with(['student', 'activity'])
            ->latest()
            ->take(10)
            ->get();
            
        // For demo purposes, show all students since we don't have authentication yet
        $recentStudents = Student::latest()
            ->take(8)
            ->get();
            
        $weeklyStats = $this->getWeeklyStats();
        
        return view('teacher.reports.index', compact('todayReports', 'recentStudents', 'weeklyStats'));
    }
    
    public function create()
    {
        // For demo purposes, show all students since we don't have authentication yet
        $students = Student::all();
        $activities = Activity::all();
        
        return view('teacher.reports.create', compact('students', 'activities'));
    }
    
    public function store(Request $request)
    {
        $request->validate([
            'student_id' => 'required|exists:students,id',
            'activity_id' => 'required|exists:activities,id',
            'activity_description' => 'required|string|max:500',
            'performance_rating' => 'required|integer|between:1,5',
            'notes' => 'nullable|string|max:1000',
            'report_date' => 'required|date'
        ]);
        
        DailyReport::create([
            'teacher_id' => 1, // Default teacher ID for demo
            'student_id' => $request->student_id,
            'activity_id' => $request->activity_id,
            'activity_description' => $request->activity_description,
            'performance_rating' => $request->performance_rating,
            'notes' => $request->notes,
            'report_date' => $request->report_date,
            'duration' => $request->duration ?? 60
        ]);

        return redirect()->route('teacher.reports.index')
            ->with('success', 'Laporan berhasil disimpan!');
    }
    
    public function show(Student $student)
    {
        $reports = DailyReport::where('student_id', $student->id)
            ->with('activity')
            ->latest('report_date')
            ->paginate(15);
            
        $averageRating = $reports->avg('performance_rating');
        $totalActivities = $reports->count();
        
        return view('teacher.reports.show', compact('student', 'reports', 'averageRating', 'totalActivities'));
    }
    
    public function students()
    {
        // For demo purposes, we'll show all students since we don't have authentication yet
        $students = Student::withCount('dailyReports')
            ->withAvg('dailyReports', 'performance_rating')
            ->get();
            
        return view('teacher.students.index', compact('students'));
    }
    
    private function getWeeklyStats()
    {
        $startOfWeek = Carbon::now()->startOfWeek();
        $endOfWeek = Carbon::now()->endOfWeek();
        
        return [
            'total_reports' => DailyReport::whereBetween('report_date', [$startOfWeek, $endOfWeek])->count(),
            'average_rating' => DailyReport::whereBetween('report_date', [$startOfWeek, $endOfWeek])->avg('performance_rating'),
            'active_students' => DailyReport::whereBetween('report_date', [$startOfWeek, $endOfWeek])->distinct('student_id')->count()
        ];
    }
}
