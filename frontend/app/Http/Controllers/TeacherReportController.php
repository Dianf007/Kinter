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
    
    public function exportPDF(Student $student)
    {
        $reports = DailyReport::where('student_id', $student->id)
            ->with('activity')
            ->latest('report_date')
            ->get();
            
        $averageRating = $reports->avg('performance_rating');
        $totalActivities = $reports->count();
        
        // For now, we'll create a simple HTML to PDF conversion
        // In production, you'd use packages like dompdf or tcpdf
        
        $html = $this->generateReportHTML($student, $reports, $averageRating, $totalActivities);
        
        // Create a simple PDF-like response
        return response($html)
            ->header('Content-Type', 'application/pdf')
            ->header('Content-Disposition', 'attachment; filename="Laporan_' . str_replace(' ', '_', $student->name) . '_' . date('Y-m-d') . '.pdf"');
    }
    
    public function sendEmail(Student $student)
    {
        $reports = DailyReport::where('student_id', $student->id)
            ->with('activity')
            ->latest('report_date')
            ->take(5) // Last 5 reports
            ->get();
            
        // For demo purposes, we'll simulate email sending
        // In production, you'd use Laravel's Mail facade
        
        $emailData = [
            'student' => $student,
            'reports' => $reports,
            'average_rating' => $reports->avg('performance_rating'),
            'total_reports' => $reports->count(),
            'generated_at' => now()->format('d M Y H:i')
        ];
        
        // Simulate email sending delay
        sleep(2);
        
        return response()->json([
            'success' => true,
            'message' => 'Email laporan berhasil dikirim ke wali murid',
            'data' => $emailData
        ]);
    }
    
    private function generateReportHTML($student, $reports, $averageRating, $totalActivities)
    {
        $html = '
        <!DOCTYPE html>
        <html>
        <head>
            <meta charset="utf-8">
            <title>Laporan Pembelajaran - ' . $student->name . '</title>
            <style>
                body { font-family: Arial, sans-serif; margin: 20px; }
                .header { text-align: center; margin-bottom: 30px; border-bottom: 2px solid #333; padding-bottom: 20px; }
                .student-info { background: #f5f5f5; padding: 15px; margin-bottom: 20px; border-radius: 5px; }
                .stats { display: flex; justify-content: space-around; margin-bottom: 30px; }
                .stat-box { text-align: center; padding: 15px; background: #e8f4fd; border-radius: 5px; }
                .report-item { margin-bottom: 20px; border: 1px solid #ddd; padding: 15px; border-radius: 5px; }
                .report-date { font-weight: bold; color: #2c5aa0; }
                .report-activity { font-size: 18px; margin: 10px 0; }
                .report-rating { color: #f39c12; }
                .footer { margin-top: 30px; text-align: center; font-size: 12px; color: #666; }
            </style>
        </head>
        <body>
            <div class="header">
                <h1>LAPORAN PEMBELAJARAN SISWA</h1>
                <h2>' . $student->name . '</h2>
                <p>Kelas ' . $student->class . ' | ID: ' . $student->student_id . '</p>
            </div>
            
            <div class="student-info">
                <h3>Informasi Siswa</h3>
                <p><strong>Nama:</strong> ' . $student->name . '</p>
                <p><strong>Kelas:</strong> ' . $student->class . '</p>
                <p><strong>Email:</strong> ' . ($student->email ?? 'Tidak tersedia') . '</p>
                <p><strong>Tanggal Laporan:</strong> ' . date('d F Y') . '</p>
            </div>
            
            <div class="stats">
                <div class="stat-box">
                    <h3>' . $totalActivities . '</h3>
                    <p>Total Laporan</p>
                </div>
                <div class="stat-box">
                    <h3>' . number_format($averageRating, 1) . '/5</h3>
                    <p>Rata-rata Tingkat Pemahaman</p>
                </div>
                <div class="stat-box">
                    <h3>' . $reports->sum('duration') . '</h3>
                    <p>Total Menit</p>
                </div>
            </div>
            
            <h3>Riwayat Pembelajaran</h3>';
            
        foreach($reports as $report) {
            $ratingTexts = [
                1 => 'Awal Berkembang',
                2 => 'Mulai Berkembang', 
                3 => 'Berkembang',
                4 => 'Mahir',
                5 => 'Sangat Mahir'
            ];
            $ratingEmojis = [
                1 => '🌱',
                2 => '🌿',
                3 => '🌳', 
                4 => '⭐',
                5 => '🏆'
            ];
            
            $ratingDisplay = $ratingEmojis[$report->performance_rating] . ' ' . $ratingTexts[$report->performance_rating];
            
            $html .= '
            <div class="report-item">
                <div class="report-date">' . Carbon::parse($report->report_date)->format('d F Y') . '</div>
                <div class="report-activity">' . $report->activity->name . '</div>
                <p>' . $report->activity_description . '</p>
                <div class="report-rating">Tingkat Pemahaman: ' . $ratingDisplay . '</div>';
                
            if($report->notes) {
                $html .= '<p><strong>Catatan:</strong> ' . $report->notes . '</p>';
            }
            
            $html .= '</div>';
        }
        
        $html .= '
            <div class="footer">
                <p>Laporan ini dibuat secara otomatis oleh Sistem Teacher Portal</p>
                <p>Digenerate pada: ' . date('d F Y H:i:s') . '</p>
            </div>
        </body>
        </html>';
        
        return $html;
    }
}
