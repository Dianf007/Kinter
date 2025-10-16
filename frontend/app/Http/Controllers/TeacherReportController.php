<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Student;
use App\Models\DailyReport;
use App\Models\Activity;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;

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
    
    public function exportPDF(Request $request, Student $student)
    {
        // Get export parameters
        $exportType = $request->input('type', 'all');
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');
        $period = $request->input('period');
        
        // Build query based on export type
        $query = DailyReport::where('student_id', $student->id)
                           ->with('activity');
        
        if ($exportType !== 'all' && $startDate && $endDate) {
            $query->whereBetween('report_date', [$startDate, $endDate]);
        }
        
        $reports = $query->orderBy('report_date', 'desc')->get();
        $averageRating = $reports->avg('performance_rating');
        $totalActivities = $reports->count();
        
        // Determine report title
        $reportTitle = 'Laporan Pembelajaran';
        if ($exportType === 'weekly') {
            $reportTitle = 'Laporan Mingguan';
        } elseif ($exportType === 'monthly') {
            $reportTitle = 'Laporan Bulanan';
        }
        
        $html = $this->generateReportHTML($student, $reports, $averageRating, $totalActivities, $reportTitle, $period);
        
        // Generate filename
        $fileName = $this->generateFileName($student, $exportType, $period);
        
        // Generate PDF using dompdf
        $pdf = Pdf::loadHTML($html);
        $pdf->setPaper('A4', 'portrait');
        $pdf->setOptions([
            'isHtml5ParserEnabled' => true,
            'isPhpEnabled' => true,
            'isRemoteEnabled' => true,
        ]);
        
        return $pdf->download($fileName);
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
    
    private function generateReportHTML($student, $reports, $averageRating, $totalActivities, $reportTitle = 'Laporan Pembelajaran', $period = null)
    {
        $currentDate = date('d F Y');
        $generatedTime = date('d F Y H:i:s');
        
        $html = '
        <!DOCTYPE html>
        <html>
        <head>
            <meta charset="utf-8">
            <title>' . $reportTitle . ' - ' . $student->name . '</title>
            <style>
                @page {
                    margin: 15mm;
                    size: A4;
                }
                
                body { 
                    font-family: "Segoe UI", "Helvetica Neue", "Arial", sans-serif; 
                    margin: 0; 
                    padding: 0; 
                    background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
                    color: #2c3e50;
                    line-height: 1.6;
                    font-size: 13px;
                    font-weight: 400;
                }
                
                .document {
                    max-width: 100%;
                    margin: 20px auto;
                    background: white;
                    position: relative;
                    border-radius: 20px;
                    overflow: hidden;
                    box-shadow: 0 20px 60px rgba(0, 0, 0, 0.15);
                }
                
                .document::before {
                    content: "";
                    position: absolute;
                    top: 0;
                    left: 0;
                    right: 0;
                    bottom: 0;
                    background: url("data:image/jpeg;base64,' . base64_encode(file_get_contents(public_path('assets/img/teacher/report-bg1.jpg'))) . '");
                    background-size: cover;
                    background-position: center;
                    background-repeat: no-repeat;
                    opacity: 0.03;
                    z-index: -1;
                }
                
                @keyframes rotate {
                    0% { transform: rotate(0deg); }
                    100% { transform: rotate(360deg); }
                }
                
                .header { 
                    background: linear-gradient(135deg, rgba(102, 126, 234, 0.9) 0%, rgba(118, 75, 162, 0.9) 100%),
                               url("data:image/jpeg;base64,' . base64_encode(file_get_contents(public_path('assets/img/teacher/report-bg1.jpg'))) . '");
                    background-size: cover;
                    background-position: center;
                    background-repeat: no-repeat;
                    padding: 40px 40px;
                    margin: 0;
                    position: relative;
                    overflow: hidden;
                    color: white;
                    border-radius: 20px 20px 0 0;
                }
                
                .header::before {
                    content: "";
                    position: absolute;
                    top: -50%;
                    right: -20%;
                    width: 150%;
                    height: 150%;
                    background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%);
                    animation: float 8s ease-in-out infinite;
                }
                
                .header::after {
                    content: "🏛️";
                    position: absolute;
                    top: 20px;
                    right: 40px;
                    font-size: 80px;
                    opacity: 0.15;
                    z-index: 1;
                    animation: pulse 4s ease-in-out infinite;
                }
                
                @keyframes float {
                    0%, 100% { transform: translateY(0px) rotate(0deg); }
                    50% { transform: translateY(-20px) rotate(5deg); }
                }
                
                @keyframes pulse {
                    0%, 100% { opacity: 0.1; transform: scale(1); }
                    50% { opacity: 0.2; transform: scale(1.05); }
                }
                
                .header-content {
                    position: relative;
                    z-index: 2;
                }
                
                .header-content {
                    position: relative;
                    z-index: 2;
                    display: flex;
                    justify-content: space-between;
                    align-items: center;
                }
                
                .logo-section {
                    display: flex;
                    align-items: center;
                    gap: 15px;
                }
                
                .logo {
                    width: 85px;
                    height: 85px;
                    background: linear-gradient(135deg, rgba(255, 255, 255, 0.25) 0%, rgba(255, 255, 255, 0.1) 100%);
                    border-radius: 20px;
                    display: flex;
                    flex-direction: column;
                    align-items: center;
                    justify-content: center;
                    font-weight: bold;
                    color: white;
                    font-size: 10px;
                    text-align: center;
                    line-height: 1.2;
                    flex-shrink: 0;
                    backdrop-filter: blur(20px);
                    border: 3px solid rgba(255, 255, 255, 0.4);
                    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
                    position: relative;
                    overflow: hidden;
                }
                
                .logo::before {
                    content: "";
                    position: absolute;
                    top: -50%;
                    left: -50%;
                    width: 200%;
                    height: 200%;
                    background: linear-gradient(45deg, transparent, rgba(255,255,255,0.1), transparent);
                    animation: shine 3s infinite;
                }
                
                @keyframes shine {
                    0% { transform: translateX(-100%) translateY(-100%) rotate(45deg); }
                    100% { transform: translateX(100%) translateY(100%) rotate(45deg); }
                }
                
                .logo-main {
                    font-size: 11px;
                    font-weight: 900;
                    margin-bottom: 3px;
                    letter-spacing: 0.5px;
                    text-shadow: 0 2px 4px rgba(0,0,0,0.2);
                }
                
                .logo-sub {
                    font-size: 9px;
                    font-weight: 700;
                    opacity: 0.95;
                    letter-spacing: 0.3px;
                }
                
                .organization-info h1 {
                    margin: 0;
                    font-size: 22px;
                    font-weight: 800;
                    color: white;
                    letter-spacing: 1px;
                    text-shadow: 0 4px 8px rgba(0,0,0,0.2);
                    margin-bottom: 8px;
                }
                
                .organization-info p {
                    margin: 2px 0;
                    font-size: 12px;
                    color: rgba(255, 255, 255, 0.95);
                    font-weight: 500;
                    text-shadow: 0 2px 4px rgba(0,0,0,0.1);
                }
                
                .report-meta {
                    text-align: right;
                    font-size: 11px;
                    color: rgba(255, 255, 255, 0.9);
                }
                
                .report-meta h2 {
                    margin: 0 0 5px 0;
                    font-size: 16px;
                    color: white;
                    font-weight: 600;
                    text-shadow: 0 2px 4px rgba(0,0,0,0.1);
                }
                
                .content {
                    padding: 40px 40px;
                }
                
                .student-info { 
                    background: linear-gradient(135deg, #f8fbff 0%, #ebf4ff 100%);
                    border: 3px solid #e3f2fd;
                    border-radius: 20px;
                    padding: 30px; 
                    margin-bottom: 35px;
                    position: relative;
                    overflow: hidden;
                    box-shadow: 0 15px 40px rgba(25, 118, 210, 0.12);
                }
                
                .student-info::before {
                    content: "👨‍🎓";
                    position: absolute;
                    top: 20px;
                    right: 30px;
                    font-size: 32px;
                    opacity: 0.4;
                    animation: bounce 2s infinite;
                }
                
                .student-info::after {
                    content: "";
                    position: absolute;
                    top: -60%;
                    right: -60%;
                    width: 120%;
                    height: 120%;
                    background: radial-gradient(circle, rgba(25, 118, 210, 0.08) 0%, transparent 70%);
                    z-index: 0;
                }
                
                @keyframes bounce {
                    0%, 20%, 50%, 80%, 100% { transform: translateY(0); }
                    40% { transform: translateY(-10px); }
                    60% { transform: translateY(-5px); }
                }
                
                .student-info h3 {
                    margin: 0 0 20px 0;
                    color: #1976d2;
                    font-size: 16px;
                    font-weight: 700;
                    border-bottom: 3px solid #4fc3f7;
                    padding-bottom: 12px;
                    position: relative;
                    z-index: 1;
                    text-transform: uppercase;
                    letter-spacing: 1px;
                }
                
                .info-table {
                    width: 100%;
                    border-collapse: collapse;
                }
                
                .info-table td {
                    padding: 12px 0;
                    border-bottom: 2px solid #e1f5fe;
                    vertical-align: top;
                    position: relative;
                    z-index: 1;
                }
                
                .info-table td:first-child {
                    font-weight: 700;
                    color: #0277bd;
                    width: 130px;
                    font-size: 13px;
                }
                
                .info-table td:last-child {
                    color: #263238;
                    font-weight: 500;
                    font-size: 13px;
                }
                
                .stats-section { 
                    margin-bottom: 40px;
                    background: linear-gradient(135deg, #fffbf0 0%, #fff8e1 100%);
                    border-radius: 20px;
                    padding: 30px;
                    border: 3px solid #ffecb3;
                    position: relative;
                    overflow: hidden;
                    box-shadow: 0 15px 40px rgba(255, 193, 7, 0.15);
                }
                
                .stats-section::before {
                    content: "";
                    position: absolute;
                    top: -100%;
                    left: -100%;
                    width: 300%;
                    height: 300%;
                    background: repeating-conic-gradient(
                        from 0deg at 50% 50%,
                        rgba(255, 193, 7, 0.03) 0deg,
                        transparent 45deg,
                        rgba(255, 152, 0, 0.03) 90deg,
                        transparent 135deg
                    );
                    animation: rotate 30s linear infinite;
                    z-index: 0;
                }
                
                .stats-grid {
                    display: table;
                    width: 100%;
                    border-collapse: separate;
                    border-spacing: 15px;
                }
                
                .stat-box { 
                    display: table-cell;
                    text-align: center; 
                    padding: 25px 20px; 
                    background: white;
                    border: 3px solid #ffcc02;
                    border-radius: 18px;
                    box-shadow: 0 8px 25px rgba(255, 193, 7, 0.25);
                    position: relative;
                    width: 25%;
                    transition: transform 0.3s ease;
                }
                
                .stat-box::before {
                    content: "";
                    position: absolute;
                    top: -3px;
                    left: -3px;
                    right: -3px;
                    bottom: -3px;
                    background: linear-gradient(45deg, #ff9800, #ffc107, #ffeb3b, #cddc39);
                    border-radius: 18px;
                    z-index: -1;
                    animation: gradientShift 4s ease-in-out infinite;
                }
                
                @keyframes gradientShift {
                    0%, 100% { background-position: 0% 50%; }
                    50% { background-position: 100% 50%; }
                }
                
                .stat-value {
                    font-size: 32px;
                    font-weight: 900;
                    color: #e65100;
                    margin-bottom: 10px;
                    display: block;
                    text-shadow: 0 3px 6px rgba(230, 81, 0, 0.3);
                    letter-spacing: -1px;
                }
                
                .stat-label {
                    color: #bf360c;
                    font-size: 11px;
                    font-weight: 700;
                    text-transform: uppercase;
                    letter-spacing: 1px;
                    line-height: 1.2;
                }
                
                .section-title {
                    font-size: 18px;
                    font-weight: 700;
                    color: #1976d2;
                    margin: 40px 0 20px 0;
                    padding: 15px 25px;
                    background: linear-gradient(135deg, #e3f2fd 0%, #f3e5f5 100%);
                    border-radius: 15px;
                    border-left: 8px solid #2196f3;
                    position: relative;
                    text-transform: uppercase;
                    letter-spacing: 1px;
                    box-shadow: 0 8px 20px rgba(33, 150, 243, 0.15);
                }
                
                .section-title::before {
                    content: "📚";
                    margin-right: 15px;
                    font-size: 20px;
                    animation: wiggle 2s ease-in-out infinite;
                }
                
                @keyframes wiggle {
                    0%, 7% { transform: rotateZ(0); }
                    15% { transform: rotateZ(-15deg); }
                    20% { transform: rotateZ(10deg); }
                    25% { transform: rotateZ(-10deg); }
                    30% { transform: rotateZ(6deg); }
                    35% { transform: rotateZ(-4deg); }
                    40%, 100% { transform: rotateZ(0); }
                }
                
                .reports-table {
                    width: 100%;
                    border-collapse: collapse;
                    margin-bottom: 30px;
                    font-size: 12px;
                    border-radius: 15px;
                    overflow: hidden;
                    box-shadow: 0 10px 30px rgba(0,0,0,0.15);
                    background: white;
                }
                
                .reports-table th {
                    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                    color: white;
                    padding: 18px 12px;
                    text-align: left;
                    font-weight: 700;
                    font-size: 11px;
                    text-transform: uppercase;
                    letter-spacing: 1px;
                    text-shadow: 0 2px 4px rgba(0,0,0,0.3);
                    position: relative;
                }
                
                .reports-table th::after {
                    content: "";
                    position: absolute;
                    bottom: 0;
                    left: 0;
                    right: 0;
                    height: 3px;
                    background: linear-gradient(90deg, #ff6b6b, #feca57, #48dbfb, #ff9ff3);
                    background-size: 300% 100%;
                    animation: gradientMove 3s ease-in-out infinite;
                }
                
                @keyframes gradientMove {
                    0%, 100% { background-position: 0% 50%; }
                    50% { background-position: 100% 50%; }
                }
                
                .reports-table td {
                    padding: 15px 12px;
                    border-bottom: 2px solid #e1f5fe;
                    vertical-align: top;
                    transition: background-color 0.3s ease;
                }
                
                .reports-table tr:nth-child(even) {
                    background: linear-gradient(135deg, #f8f9ff 0%, #fff 100%);
                }
                
                .reports-table tr:hover {
                    background: linear-gradient(135deg, #e3f2fd 0%, #f3e5f5 100%);
                    transform: translateY(-1px);
                    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
                }
                
                .date-cell {
                    font-weight: 700;
                    color: #0277bd;
                    white-space: nowrap;
                    width: 100px;
                    font-size: 12px;
                }
                
                .activity-cell {
                    font-weight: 700;
                    color: #1976d2;
                    width: 120px;
                    font-size: 12px;
                }
                
                .description-cell {
                    line-height: 1.6;
                    color: #37474f;
                    font-size: 12px;
                    font-weight: 500;
                }
                
                .rating-cell {
                    text-align: center;
                    width: 100px;
                    padding: 8px;
                }
                
                .rating-badge {
                    display: inline-block;
                    padding: 10px 18px;
                    border-radius: 25px;
                    font-size: 11px;
                    font-weight: 800;
                    text-transform: uppercase;
                    letter-spacing: 0.8px;
                    box-shadow: 0 6px 18px rgba(0,0,0,0.2);
                    position: relative;
                    overflow: hidden;
                    min-width: 80px;
                }
                
                .rating-badge::before {
                    content: "";
                    position: absolute;
                    top: 0;
                    left: -100%;
                    width: 100%;
                    height: 100%;
                    background: linear-gradient(90deg, transparent, rgba(255,255,255,0.5), transparent);
                    animation: shimmer 3s infinite;
                }
                
                @keyframes shimmer {
                    0% { left: -100%; }
                    100% { left: 100%; }
                }
                
                .rating-1 { 
                    background: linear-gradient(135deg, #ff6b6b 0%, #ee5a52 100%); 
                    color: white; 
                    box-shadow: 0 6px 20px rgba(255, 107, 107, 0.5);
                }
                .rating-1::after { content: " 🌱"; }
                
                .rating-2 { 
                    background: linear-gradient(135deg, #ffa726 0%, #ff8f00 100%); 
                    color: white; 
                    box-shadow: 0 6px 20px rgba(255, 167, 38, 0.5);
                }
                .rating-2::after { content: " 🌿"; }
                
                .rating-3 { 
                    background: linear-gradient(135deg, #66bb6a 0%, #43a047 100%); 
                    color: white; 
                    box-shadow: 0 6px 20px rgba(102, 187, 106, 0.5);
                }
                .rating-3::after { content: " 🌳"; }
                
                .rating-4 { 
                    background: linear-gradient(135deg, #42a5f5 0%, #1e88e5 100%); 
                    color: white; 
                    box-shadow: 0 6px 20px rgba(66, 165, 245, 0.5);
                }
                .rating-4::after { content: " ⭐"; }
                
                .rating-5 { 
                    background: linear-gradient(135deg, #ab47bc 0%, #8e24aa 100%); 
                    color: white; 
                    box-shadow: 0 6px 20px rgba(171, 71, 188, 0.5);
                }
                .rating-5::after { content: " 🏆"; }
                
                .duration-cell {
                    text-align: center;
                    font-weight: 700;
                    color: #7f8c8d;
                    width: 80px;
                    font-size: 12px;
                }
                
                .notes-cell {
                    font-style: italic;
                    color: #7f8c8d;
                    font-size: 11px;
                    background: rgba(127, 140, 141, 0.05);
                    border-left: 4px solid #bdc3c7;
                    padding-left: 16px;
                    line-height: 1.5;
                }
                
                .summary-section {
                    margin-top: 40px;
                    padding: 35px;
                    background: linear-gradient(135deg, #e8f5e8 0%, #f1f8e9 100%);
                    border-radius: 20px;
                    border: 3px solid #c8e6c9;
                    position: relative;
                    overflow: hidden;
                    box-shadow: 0 15px 40px rgba(46, 125, 50, 0.15);
                }
                
                .summary-section::before {
                    content: "🎯";
                    position: absolute;
                    top: 25px;
                    right: 35px;
                    font-size: 36px;
                    opacity: 0.4;
                    animation: bounce 3s infinite;
                }
                
                .summary-section::after {
                    content: "";
                    position: absolute;
                    top: -50%;
                    left: -50%;
                    width: 200%;
                    height: 200%;
                    background: radial-gradient(circle, rgba(46, 125, 50, 0.05) 0%, transparent 70%);
                    animation: rotate 25s linear infinite;
                    z-index: 0;
                }
                
                .summary-title {
                    font-size: 16px;
                    font-weight: 700;
                    color: #2e7d32;
                    margin-bottom: 20px;
                    padding-bottom: 12px;
                    border-bottom: 3px solid #81c784;
                    position: relative;
                    z-index: 1;
                    text-transform: uppercase;
                    letter-spacing: 1px;
                }
                
                .summary-text {
                    color: #1b5e20;
                    line-height: 1.8;
                    font-size: 12px;
                    position: relative;
                    z-index: 1;
                    font-weight: 500;
                }
                
                .footer { 
                    margin-top: 60px; 
                    padding: 40px 40px;
                    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                    border-radius: 0 0 20px 20px;
                    text-align: center; 
                    font-size: 12px; 
                    color: white;
                    position: relative;
                    overflow: hidden;
                }
                
                .footer::before {
                    content: "";
                    position: absolute;
                    top: -50%;
                    left: -50%;
                    width: 200%;
                    height: 200%;
                    background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%);
                    animation: float 10s ease-in-out infinite reverse;
                }
                
                .footer::after {
                    content: "🚀";
                    position: absolute;
                    top: 20px;
                    left: 40px;
                    font-size: 32px;
                    opacity: 0.2;
                    z-index: 1;
                    animation: pulse 3s ease-in-out infinite;
                }
                
                .footer-info {
                    position: relative;
                    z-index: 2;
                    margin-bottom: 15px;
                    font-weight: 700;
                    font-size: 14px;
                    text-shadow: 0 2px 4px rgba(0,0,0,0.2);
                    letter-spacing: 1px;
                }
                
                .footer-timestamp {
                    position: relative;
                    z-index: 2;
                    font-weight: 500;
                    color: rgba(255, 255, 255, 0.95);
                    margin-bottom: 8px;
                    font-size: 12px;
                    font-style: italic;
                }
                
                .no-reports {
                    text-align: center;
                    padding: 60px 20px;
                    color: #90a4ae;
                    font-style: italic;
                    background: linear-gradient(135deg, #f5f5f5 0%, #eeeeee 100%);
                    border-radius: 12px;
                    margin: 20px 0;
                }
                
                .no-reports::before {
                    content: "📋";
                    display: block;
                    font-size: 48px;
                    margin-bottom: 15px;
                    opacity: 0.5;
                }
                
                .page-break {
                    page-break-before: always;
                }
                
                .text-center { text-align: center; }
                .text-right { text-align: right; }
                .font-bold { font-weight: 600; }
                .text-muted { color: #7f8c8d; }
                
                @media print {
                    body { background: white; }
                    .document { box-shadow: none; }
                }
            </style>
        </head>
        <body>
            <div class="document">
                <div class="header">
                    <div class="header-content">
                        <div class="logo-section">
                            <div class="logo">
                                <div class="logo-main">PONDOK</div>
                                <div class="logo-main">KODING</div>
                                <div class="logo-sub">MOJOKERTO</div>
                            </div>
                            <div class="organization-info">
                                <h1>PONDOK KODING MOJOKERTO</h1>
                                <p>Laporan Perkembangan Siswa</p>
                                <p>Bermain sambil belajar, Belajar sambil berkarya</p>
                            </div>
                        </div>
                        <div class="report-meta">
                            <h2>' . $reportTitle . '</h2>';
                            
        if ($period) {
            $html .= '<p><strong>Periode:</strong> ' . $period . '</p>';
        }
        
        $html .= '
                            <p><strong>Tanggal Cetak:</strong> ' . $currentDate . '</p>
                        </div>
                    </div>
                </div>
                
                <div class="content">';
        
        $html .= '
                    <!-- Informasi Siswa -->
                    <div class="student-info">
                        <h3>INFORMASI SISWA</h3>
                        <table class="info-table">
                            <tr>
                                <td>Nama Lengkap</td>
                                <td>: ' . $student->name . '</td>
                            </tr>
                            <tr>
                                <td>ID Siswa</td>
                                <td>: ' . $student->student_id . '</td>
                            </tr>
                            <tr>
                                <td>Kelas</td>
                                <td>: ' . $student->class . '</td>
                            </tr>
                            <tr>
                                <td>Email</td>
                                <td>: ' . ($student->email ?? 'Tidak tersedia') . '</td>
                            </tr>
                        </table>
                    </div>
                    
                    <!-- Ringkasan Statistik -->
                    <div class="stats-section">
                        <h3 class="section-title">RINGKASAN PEMBELAJARAN</h3>
                        <div class="stats-grid">
                            <div class="stat-box">
                                <span class="stat-value">' . $totalActivities . '</span>
                                <div class="stat-label">Total Laporan</div>
                            </div>
                            <div class="stat-box">
                                <span class="stat-value">' . number_format($averageRating, 1) . '</span>
                                <div class="stat-label">Rata-rata Nilai</div>
                            </div>
                            <div class="stat-box">
                                <span class="stat-value">' . $reports->sum('duration') . '</span>
                                <div class="stat-label">Total Menit</div>
                            </div>
                            <div class="stat-box">
                                <span class="stat-value">' . $reports->where('performance_rating', '>=', 3)->count() . '</span>
                                <div class="stat-label">Pencapaian Baik</div>
                            </div>
                        </div>
                    </div>';
                    
        if($reports->count() > 0) {
            $html .= '
                    <!-- Detail Laporan -->
                    <h3 class="section-title">DETAIL AKTIVITAS PEMBELAJARAN</h3>
                    <table class="reports-table">
                        <thead>
                            <tr>
                                <th>Tanggal</th>
                                <th>Aktivitas</th>
                                <th>Deskripsi</th>
                                <th>Rating</th>
                                <th>Durasi</th>
                            </tr>
                        </thead>
                        <tbody>';
                        
            foreach($reports as $report) {
                $ratingTexts = [
                    1 => 'Awal Berkembang',
                    2 => 'Mulai Berkembang', 
                    3 => 'Berkembang',
                    4 => 'Mahir',
                    5 => 'Sangat Mahir'
                ];
                
                $ratingClass = 'rating-' . $report->performance_rating;
                $formattedDate = Carbon::parse($report->report_date)->locale('id')->isoFormat('DD/MM/Y');
                
                $html .= '
                            <tr>
                                <td class="date-cell">' . $formattedDate . '</td>
                                <td class="activity-cell">' . $report->activity->name . '</td>
                                <td class="description-cell">' . $report->activity_description . '</td>
                                <td class="rating-cell">
                                    <span class="rating-badge ' . $ratingClass . '">' . $ratingTexts[$report->performance_rating] . '</span>
                                </td>
                                <td class="duration-cell">' . ($report->duration ? $report->duration . ' min' : '-') . '</td>
                            </tr>';
                            
                if($report->notes) {
                    $html .= '
                            <tr>
                                <td colspan="5" class="notes-cell">
                                    <strong>Catatan:</strong> ' . $report->notes . '
                                </td>
                            </tr>';
                }
            }
            
            $html .= '
                        </tbody>
                    </table>
                    
                    <!-- Ringkasan Evaluasi -->
                    <div class="summary-section">
                        <div class="summary-title">EVALUASI PERKEMBANGAN</div>
                        <div class="summary-text">
                            Berdasarkan ' . $totalActivities . ' aktivitas pembelajaran yang telah dilakukan, 
                            ' . $student->name . ' menunjukkan tingkat pemahaman rata-rata ' . number_format($averageRating, 1) . ' 
                            dari skala 5. ';
                            
            if($averageRating >= 4) {
                $html .= 'Siswa menunjukkan perkembangan yang sangat baik dan konsisten dalam pembelajaran.';
            } elseif($averageRating >= 3) {
                $html .= 'Siswa menunjukkan perkembangan yang baik dengan beberapa area yang perlu ditingkatkan.';
            } else {
                $html .= 'Siswa memerlukan bimbingan tambahan untuk meningkatkan pemahaman materi.';
            }
            
            $html .= '
                        </div>
                    </div>';
        } else {
            $html .= '
                    <div class="no-reports">
                        <p>Tidak ada laporan pembelajaran dalam periode ini.</p>
                    </div>';
        }
        
        $html .= '
                </div>
                
                <div class="footer">
                    <div class="footer-info">
                        <strong>PONDOK KODING</strong> - PONDOK KODING MOJOKERTO DIGITAL
                    </div>
                    <div class="footer-timestamp">
                        Ciptakan karya digital versimu sejak usia dini
                    </div>
                    <div class="footer-timestamp">
                        Dokumen ini dibuat secara otomatis pada: ' . $generatedTime . '
                    </div>
                </div>
            </div>
        </body>
        </html>';
        
        return $html;
    }
    
    private function generateFileName($student, $exportType, $period)
    {
        $studentName = str_replace(' ', '_', $student->name);
        $date = date('Y-m-d');
        
        if ($exportType === 'weekly') {
            return "Laporan_Mingguan_{$studentName}_{$date}.pdf";
        } elseif ($exportType === 'monthly') {
            return "Laporan_Bulanan_{$studentName}_{$date}.pdf";
        }
        
        return "Laporan_Pembelajaran_{$studentName}_{$date}.pdf";
    }
}
