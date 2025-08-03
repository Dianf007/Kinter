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
                    margin: 20mm;
                    size: A4;
                }
                
                body { 
                    font-family: "Arial", "Helvetica", sans-serif; 
                    margin: 0; 
                    padding: 0; 
                    background: #ffffff;
                    color: #2c3e50;
                    line-height: 1.4;
                    font-size: 12px;
                }
                
                .document {
                    max-width: 100%;
                    margin: 0 auto;
                    background: white;
                }
                
                .header { 
                    background: #f8f9fa;
                    border-bottom: 3px solid #3498db;
                    padding: 25px 30px;
                    margin-bottom: 25px;
                    position: relative;
                }
                
                .header-content {
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
                    width: 70px;
                    height: 70px;
                    background: linear-gradient(135deg, #3498db, #2980b9);
                    border-radius: 8px;
                    display: flex;
                    flex-direction: column;
                    align-items: center;
                    justify-content: center;
                    font-weight: bold;
                    color: white;
                    font-size: 9px;
                    text-align: center;
                    line-height: 1.1;
                    flex-shrink: 0;
                    box-shadow: 0 2px 8px rgba(52, 152, 219, 0.3);
                }
                
                .logo-main {
                    font-size: 10px;
                    font-weight: 800;
                    margin-bottom: 2px;
                }
                
                .logo-sub {
                    font-size: 8px;
                    font-weight: 600;
                    opacity: 0.9;
                }
                
                .organization-info h1 {
                    margin: 0;
                    font-size: 18px;
                    font-weight: bold;
                    color: #2c3e50;
                    letter-spacing: 0.5px;
                }
                
                .organization-info p {
                    margin: 3px 0 0 0;
                    font-size: 11px;
                    color: #7f8c8d;
                }
                
                .report-meta {
                    text-align: right;
                    font-size: 11px;
                    color: #7f8c8d;
                }
                
                .report-meta h2 {
                    margin: 0 0 5px 0;
                    font-size: 16px;
                    color: #2c3e50;
                    font-weight: 600;
                }
                
                .content {
                    padding: 0 30px;
                }
                
                .student-info { 
                    background: #f8f9fa; 
                    border: 1px solid #e9ecef;
                    border-radius: 6px;
                    padding: 20px; 
                    margin-bottom: 25px; 
                }
                
                .student-info h3 {
                    margin: 0 0 15px 0;
                    color: #2c3e50;
                    font-size: 14px;
                    font-weight: 600;
                    border-bottom: 2px solid #3498db;
                    padding-bottom: 8px;
                }
                
                .info-table {
                    width: 100%;
                    border-collapse: collapse;
                }
                
                .info-table td {
                    padding: 6px 0;
                    border-bottom: 1px solid #ecf0f1;
                    vertical-align: top;
                }
                
                .info-table td:first-child {
                    font-weight: 600;
                    color: #34495e;
                    width: 120px;
                }
                
                .info-table td:last-child {
                    color: #2c3e50;
                }
                
                .stats-section { 
                    margin-bottom: 30px;
                }
                
                .stats-grid {
                    display: grid;
                    grid-template-columns: repeat(4, 1fr);
                    gap: 15px;
                    margin-bottom: 25px;
                }
                
                .stat-box { 
                    text-align: center; 
                    padding: 18px 12px; 
                    background: #ffffff; 
                    border: 1px solid #e9ecef;
                    border-radius: 6px;
                    box-shadow: 0 1px 3px rgba(0,0,0,0.05);
                }
                
                .stat-value {
                    font-size: 24px;
                    font-weight: bold;
                    color: #3498db;
                    margin-bottom: 5px;
                    display: block;
                }
                
                .stat-label {
                    color: #7f8c8d;
                    font-size: 10px;
                    font-weight: 600;
                    text-transform: uppercase;
                    letter-spacing: 0.5px;
                }
                
                .section-title {
                    font-size: 16px;
                    font-weight: 600;
                    color: #2c3e50;
                    margin: 30px 0 15px 0;
                    padding-bottom: 8px;
                    border-bottom: 2px solid #3498db;
                }
                
                .reports-table {
                    width: 100%;
                    border-collapse: collapse;
                    margin-bottom: 20px;
                    font-size: 11px;
                }
                
                .reports-table th {
                    background: #34495e;
                    color: white;
                    padding: 12px 8px;
                    text-align: left;
                    font-weight: 600;
                    font-size: 10px;
                    text-transform: uppercase;
                    letter-spacing: 0.5px;
                }
                
                .reports-table td {
                    padding: 10px 8px;
                    border-bottom: 1px solid #ecf0f1;
                    vertical-align: top;
                }
                
                .reports-table tr:nth-child(even) {
                    background: #f8f9fa;
                }
                
                .date-cell {
                    font-weight: 600;
                    color: #2c3e50;
                    white-space: nowrap;
                    width: 100px;
                }
                
                .activity-cell {
                    font-weight: 600;
                    color: #3498db;
                    width: 120px;
                }
                
                .description-cell {
                    line-height: 1.4;
                    color: #34495e;
                }
                
                .rating-cell {
                    text-align: center;
                    width: 80px;
                }
                
                .rating-badge {
                    display: inline-block;
                    padding: 4px 8px;
                    border-radius: 12px;
                    font-size: 9px;
                    font-weight: 600;
                    text-transform: uppercase;
                    letter-spacing: 0.5px;
                }
                
                .rating-1 { background: #ffeaa7; color: #d63031; }
                .rating-2 { background: #fdcb6e; color: #e17055; }
                .rating-3 { background: #a7e7a7; color: #00b894; }
                .rating-4 { background: #74b9ff; color: #0984e3; }
                .rating-5 { background: #fd79a8; color: #e84393; }
                
                .duration-cell {
                    text-align: center;
                    font-weight: 600;
                    color: #7f8c8d;
                    width: 60px;
                }
                
                .notes-cell {
                    font-style: italic;
                    color: #7f8c8d;
                    font-size: 10px;
                }
                
                .summary-section {
                    margin-top: 30px;
                    padding: 20px;
                    background: #f8f9fa;
                    border-radius: 6px;
                    border: 1px solid #e9ecef;
                }
                
                .summary-title {
                    font-size: 14px;
                    font-weight: 600;
                    color: #2c3e50;
                    margin-bottom: 10px;
                }
                
                .summary-text {
                    color: #34495e;
                    line-height: 1.5;
                    font-size: 11px;
                }
                
                .footer { 
                    margin-top: 40px; 
                    padding-top: 20px;
                    border-top: 1px solid #e9ecef;
                    text-align: center; 
                    font-size: 10px; 
                    color: #95a5a6;
                }
                
                .footer-info {
                    margin-bottom: 8px;
                }
                
                .footer-timestamp {
                    font-weight: 600;
                    color: #7f8c8d;
                }
                
                .no-reports {
                    text-align: center;
                    padding: 40px 20px;
                    color: #95a5a6;
                    font-style: italic;
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
