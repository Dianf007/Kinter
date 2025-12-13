<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AboutController;
use App\Http\Controllers\ClassController;
use App\Http\Controllers\TeacherController;
use App\Http\Controllers\ShopController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\QuizController;
use App\Http\Controllers\TeacherReportController;
use App\Http\Controllers\AdminAuthController;
use App\Http\Controllers\AdminKidProjectController;
use App\Http\Controllers\AdminScheduleController;
use App\Http\Controllers\AdminManagementController;
use App\Http\Controllers\ClassroomController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/about', [AboutController::class, 'index'])->name('about');

Route::get('/classes', [ClassController::class, 'index'])->name('classes.index');
Route::get('/classes/{class}', [ClassController::class, 'show'])->name('classes.show');

Route::get('/teachers', [TeacherController::class, 'index'])->name('teachers.index');
Route::get('/teachers/{teacher}', [TeacherController::class, 'show'])->name('teachers.show');

Route::get('/shop', [ShopController::class, 'index'])->name('shop.index');
Route::get('/shop/{product}', [ShopController::class, 'show'])->name('shop.show');

Route::get('/cart', [CartController::class, 'index'])->name('cart');
Route::post('/cart/add/{product}', [CartController::class, 'add'])->name('cart.add');
Route::delete('/cart/remove/{item}', [CartController::class, 'remove'])->name('cart.remove');

Route::get('/contact', [ContactController::class, 'index'])->name('contact');
Route::post('/contact', [ContactController::class, 'store'])->name('contact.store');

Route::get('/blog', [BlogController::class, 'index'])->name('blog.index');
Route::get('/blog/{post}', [BlogController::class, 'show'])->name('blog.show');

Route::get('/privacy-policy', function() {
    return view('pages.privacy-policy');
})->name('privacy-policy');

// Quiz routes (user/guest)
Route::get('/quiz', [QuizController::class, 'index'])->name('quiz.index');
Route::get('/quiz/{quiz}', [QuizController::class, 'show'])->name('quiz.show');
Route::post('/quiz/{quiz}/start', [QuizController::class, 'start'])->name('quiz.start');
Route::post('/quiz/{quiz}/submit', [QuizController::class, 'submit'])->name('quiz.submit');
Route::get('/quiz/{quiz}/result/{attempt}', [QuizController::class, 'result'])->name('quiz.result');
Route::get('/quiz/{quiz}/leaderboard', [QuizController::class, 'leaderboard'])->name('quiz.leaderboard');
Route::get('/quiz/{quiz}/attempt/{attempt}', [QuizController::class, 'attempt'])->name('quiz.attempt');
Route::get('/quiz/attempt/{attempt}/certificate', [QuizController::class, 'certificate'])->name('quiz.certificate');

// Teacher Portal Landing Page
Route::get('/teacher', function() {
    return view('teacher.landing');
})->name('teacher.landing');

// Teacher Report Management routes
Route::prefix('teacher')->name('teacher.')->group(function() {
    Route::get('/reports', [TeacherReportController::class, 'index'])->name('reports.index');
    Route::get('/reports/create', [TeacherReportController::class, 'create'])->name('reports.create');
    Route::post('/reports', [TeacherReportController::class, 'store'])->name('reports.store');
    Route::get('/reports/student/{student}', [TeacherReportController::class, 'show'])->name('reports.show');
    Route::post('/reports/{student}/export-pdf', [TeacherReportController::class, 'exportPDF'])->name('reports.export-pdf');
    Route::post('/reports/{student}/send-email', [TeacherReportController::class, 'sendEmail'])->name('reports.send-email');
    Route::get('/students', [TeacherReportController::class, 'students'])->name('students.index');
});

// Admin Auth routes
Route::get('/admin/login', [AdminAuthController::class, 'showLoginForm'])->name('admin.login');
Route::post('/admin/login', [AdminAuthController::class, 'login'])->name('admin.login.submit');
Route::get('/admin/logout', [AdminAuthController::class, 'logout'])->name('admin.logout');

Route::prefix('admin')->name('admin.')->middleware('admin.auth')->group(function () {
    Route::get('/dashboard', [AdminAuthController::class, 'dashboard'])->name('dashboard');

    Route::resource('kid-projects', AdminKidProjectController::class)
        ->except(['show'])
        ->middleware('admin.role:admin,superadmin,ultraadmin');

    Route::resource('schedules', AdminScheduleController::class)
        ->except(['show'])
        ->middleware('admin.role:admin,superadmin,ultraadmin');

    // DataTables server-side endpoint (must be before resource to avoid {classroom}=data)
    Route::get('classrooms/data', [ClassroomController::class, 'data'])
        ->name('classrooms.data')
        ->middleware('admin.role:admin,superadmin');

    // Classroom CRUD
    Route::resource('classrooms', ClassroomController::class)
        ->except(['show'])
        ->middleware('admin.role:admin,superadmin');

    // DataTables server-side endpoint (must be before resource to avoid {admin}=data)
    Route::get('admins/data', [AdminManagementController::class, 'data'])
        ->name('admins.data')
        ->middleware('admin.role:superadmin,ultraadmin');

    Route::resource('admins', AdminManagementController::class)
        ->except(['show'])
        ->middleware('admin.role:superadmin,ultraadmin');
});
