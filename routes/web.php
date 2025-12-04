<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\GroupController;
use App\Http\Controllers\GradeController;
use App\Http\Controllers\ProfessorController;
use App\Http\Controllers\GuardianController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\ReceiptController;
use App\Http\Controllers\RegistrationController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\TeacherSubjectController;
use App\Http\Controllers\AssignmentController;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Session;




Route::get('attendance-sheet', [AttendanceController::class, 'attendanceSheet'])->name('attendance.sheet');
Route::get('attendance/calendar/pdf', [AttendanceController::class, 'attendanceSheetExportToPdf'])->name('attendancesheet.pdf');

// Simple error page
Route::get('/error', fn() => view('error'))->name('error');

// Public utility
Route::get('/asif', fn() => 'Hello from Laravel!');


// Authentication
// Redirect “/” straight to the login page:

Route::get('/', fn() => redirect()->route('login'));

// Show the login form
Route::get('login', [AuthController::class, 'login'])
    ->name('login');
Route::post('login', [AuthController::class, 'authlogin'])->name('auth.login');
Route::get('logout', [AuthController::class, 'authlogout'])->name('auth.logout');

// Registration
Route::get('register', [AuthController::class, 'register'])->name('auth.register');
Route::post('register', [AuthController::class, 'store'])->name('auth.store');

// Password reset
Route::get('admin/recover-password', [AuthController::class, 'forgotpassword'])->name('auth.forgotpassword');
Route::post('admin/recover-password', [AuthController::class, 'recoverpassword'])->name('auth.recoverpassword');


/**Route::get('reset/{token}',           [AuthController::class, 'reset'])->name('auth.reset');
Route::post('reset/{token}',          [AuthController::class, 'resetpass'])->name('auth.resetpass');*/

Route::get('admin/reports/fees', [ReportController::class, 'fees'])
    ->name('admin.reports.fees');
Route::get('admin/reports/fees/export', [ReportController::class, 'exportFees'])
    ->name('admin.reports.fees.export');


// QR & Barcode
Route::get('qrcode/{id}', [StudentController::class, 'generateQRCode'])->name('student.qrcode');
Route::get('barcode/{id}', [StudentController::class, 'generateBarcode'])->name('student.barcode');

// -----------------------------------------------------------------------------
// Protected Admin Routes
// -----------------------------------------------------------------------------
Route::middleware(['auth', 'admin'])->prefix('admin')->group(function () {

    Route::get('reports/aging-report', [ReportController::class, 'agingReport'])
        ->name('admin.reports.agingreport');
    // Dashboard
    Route::get('dashboard', [DashboardController::class, 'dashboard'])->name('admin.dashboard');

    // Admin CRUD

    Route::get('admin/list', [AdminController::class, 'index'])->name('admin.admin.list');
    Route::get('admin/create', [AdminController::class, 'create'])->name('admin.admin.create');
    Route::post('admin/create', [AdminController::class, 'store'])->name('admin.admin.add');
    Route::post('admin/profile', [AdminController::class, 'update'])->name('admin.admin.update');
    Route::delete('admin/delete/{uuid}', [AdminController::class, 'destroy'])->name('admin.admin.delete');
    Route::get('admin/profile', [AdminController::class, 'profile'])->name('admin.admin.profile');
    Route::get('attendance', [AttendanceController::class, 'adminList'])->name('admin.attendance.index');
    Route::get('attendance/export', [AttendanceController::class, 'exportCsv'])->name('admin.attendance.export');

    // Student CRUD
    Route::prefix('student')->group(function () {
        Route::get('list', [StudentController::class, 'index'])->name('admin.student.list');
        Route::get('create', [StudentController::class, 'create'])->name('admin.student.create');
        Route::post('create', [StudentController::class, 'store'])->name('admin.student.add');
        Route::get('profile/{uuid}', [StudentController::class, 'show'])->name('admin.student.profile');
        Route::post('profile', [StudentController::class, 'update'])->name('admin.student.update');
        Route::delete('delete/{uuid}', [StudentController::class, 'destroy'])->name('admin.student.delete');
        Route::post('deposit', [StudentController::class, 'deposit'])->name('admin.student.deposit');
        Route::get('deposit/delete/{id}', [StudentController::class, 'deposit_delete'])->name('admin.student.deposit.delete');
        Route::get('receipt/{id}', [StudentController::class, 'receipt'])->name('admin.student.receipt');


        // Show edit form
        Route::get('{uuid}/edit', [StudentController::class, 'edit'])->name('admin.student.edit');

        // Update student (PUT/PATCH)
        Route::put('{uuid}', [StudentController::class, 'update'])->name('admin.student.update');


        // View student attendance (read-only)
        Route::get('attendance/chart/{user_id}', [AttendanceController::class, 'userAttendanceBarChart'])->name('admin.attendance.chart');
    });

    // Registration
    Route::prefix('registration')->group(function () {
        Route::get('list', [RegistrationController::class, 'index'])->name('admin.registration.list');
        Route::get('create', [RegistrationController::class, 'create'])->name('admin.registration.create');
        Route::post('create', [RegistrationController::class, 'store'])->name('admin.registration.add');
        Route::delete('delete/{uuid}', [RegistrationController::class, 'destroy'])->name('admin.registration.delete');
        Route::get('registration/{uuid}/edit', [RegistrationController::class, 'edit'])->name('admin.registration.edit');
        Route::put('registration/{uuid}', [RegistrationController::class, 'update'])->name('admin.registration.update');

    });

    // Class (Group) CRUD
    Route::prefix('class')->group(function () {
        Route::get('list', [GroupController::class, 'index'])->name('admin.class.list');
        Route::get('create', [GroupController::class, 'create'])->name('admin.class.create');
        Route::post('create', [GroupController::class, 'store'])->name('admin.class.add');
        Route::get('show/{uuid}', [GroupController::class, 'show'])->name('admin.class.profile');
        Route::delete('delete/{uuid}', [GroupController::class, 'destroy'])->name('admin.class.delete');
        Route::get('edit/{uuid}', [GroupController::class, 'edit'])->name('admin.class.edit');
        Route::put('update/{uuid}', [GroupController::class, 'update'])->name('admin.class.update');
    });

    // Course CRUD
    Route::prefix('course')->group(function () {
        Route::get('list', [CourseController::class, 'index'])->name('admin.course.list');
        Route::get('create', [CourseController::class, 'create'])->name('admin.course.create');
        Route::post('create', [CourseController::class, 'store'])->name('admin.course.add');
        Route::delete('delete/{uuid}', [CourseController::class, 'destroy'])->name('admin.course.delete');
        Route::get('edit/{uuid}', [CourseController::class, 'edit'])->name('admin.course.edit');
        Route::put('update/{uuid}', [CourseController::class, 'update'])->name('admin.course.update');
    });

    // Professor CRUD
    Route::prefix('professor')->group(function () {
        Route::get('list', [ProfessorController::class, 'index'])->name('admin.professor.list');
        Route::get('create', [ProfessorController::class, 'create'])->name('admin.professor.new');
        Route::post('create', [ProfessorController::class, 'store'])->name('admin.professor.add');
        Route::delete('delete/{uuid}', [ProfessorController::class, 'destroy'])->name('admin.professor.delete');

        // Show edit form
        Route::get('{uuid}/edit', [ProfessorController::class, 'edit'])->name('admin.professor.edit');
        // Handle update
        Route::put('{uuid}', [ProfessorController::class, 'update'])->name('admin.professor.update');

    });

    // Guardian CRUD
    Route::prefix('guardian')->group(function () {
        Route::get('list', [GuardianController::class, 'index'])->name('admin.guardian.list');
        Route::get('create', [GuardianController::class, 'create'])->name('admin.guardian.create');
        Route::post('create', [GuardianController::class, 'store'])->name('admin.guardian.add');
        Route::get('profile/{uuid}', [GuardianController::class, 'show'])->name('admin.guardian.profile');
        Route::post('profile', [GuardianController::class, 'update'])->name('admin.guardian.update');
        Route::delete('delete/{uuid}', [GuardianController::class, 'destroy'])->name('admin.guardian.delete');

        // Show edit form
        Route::get('{uuid}/edit', [GuardianController::class, 'edit'])->name('admin.guardian.edit');

        // Handle update
        Route::put('{uuid}', [GuardianController::class, 'update'])->name('admin.guardian.put');

    });
});

// -----------------------------------------------------------------------------
// Student routes
// -----------------------------------------------------------------------------
Route::middleware(['auth', 'student'])->group(function () {
    Route::get('student/dashboard', [DashboardController::class, 'dashboard'])->name('student.dashboard');
    Route::get('student/receipt', [StudentController::class, 'downloadReceipt'])->name('student.receipt');
    Route::get('/student/grades', [GradeController::class, 'myGrades'])
        ->name('student.grades');

    // Student access
    Route::get('/student/assignments', [AssignmentController::class, 'studentAssignments'])->name('student.assignments');
});

// -----------------------------------------------------------------------------
// Teacher / Professor routes
// -----------------------------------------------------------------------------
Route::middleware(['auth', 'teacher'])->group(function () {
    Route::get('teacher/dashboard', [DashboardController::class, 'dashboard'])->name('teacher.dashboard');


    Route::get('/get-courses-by-group/{group}', [AttendanceController::class, 'getCoursesByGroup']);

    Route::get('/get-courses-by-course-and-group/{group}/{course}', [AttendanceController::class, 'getStudentsByCoursesGroup']);

    // Attendance (for classes assigned to this teacher)
    Route::get('teacher/attendance', [AttendanceController::class, 'index'])->name('teacher.attendance.list');
    Route::post('teacher/attendance', [AttendanceController::class, 'storeAttendance'])->name('teacher.attendance.store');

    Route::get('teacher/subjects', [TeacherSubjectController::class, 'index'])->name('teacher.subjects.list');
    Route::get('/grades/create/{student}/{course}', [GradeController::class, 'create'])->name('grades.create');
    // Show “select course” page
    Route::get('/teacher/grade/select-course', [GradeController::class, 'selectCourse'])
        ->name('grades.select.course');

    // Show “select student for this course” page
    Route::get('/teacher/grade/select-student/{course}', [GradeController::class, 'selectStudent'])
        ->name('grades.select.student');
    // Finally, submitting the grade might go to:
    Route::post('/teacher/grade/store', [GradeController::class, 'store'])
        ->name('grades.store');

    // Show a bulk-grading page for a particular course:
    Route::get('/teacher/grade/bulk/{course}', [GradeController::class, 'bulkForm'])
        ->name('grades.bulk.form');

    // Handle submission of multiple grade entries in one request:
    Route::post('/teacher/grade/bulk/{course}', [GradeController::class, 'bulkStore'])
        ->name('grades.bulk.store');

    // View all grades for this teacher
    Route::get('/teacher/grades', [GradeController::class, 'viewAll'])
        ->name('grades.view');

    // Show “Edit Grade” form
    Route::get('/teacher/grades/{grade}/edit', [GradeController::class, 'edit'])
        ->name('grades.edit');

    // Handle “Update Grade” submission
    Route::put('/teacher/grades/{grade}', [GradeController::class, 'update'])
        ->name('grades.update');

    // Delete a grade
    Route::delete('/teacher/grades/{grade}', [GradeController::class, 'destroy'])
        ->name('grades.delete');


    // Teacher CRUD
    Route::get('/assignments', [AssignmentController::class, 'index'])->name('assignments.index');
    Route::get('/assignments/create', [AssignmentController::class, 'create'])->name('assignments.create');
    Route::post('/assignments', [AssignmentController::class, 'store'])->name('assignments.store');
    Route::get('/assignments/{assignment}/edit', [AssignmentController::class, 'edit'])->name('assignments.edit');
    Route::put('/assignments/{assignment}', [AssignmentController::class, 'update'])->name('assignments.update');
    Route::delete('/assignments/{assignment}', [AssignmentController::class, 'destroy'])->name('assignments.destroy');
});

// -----------------------------------------------------------------------------
// Parent routes
// -----------------------------------------------------------------------------
Route::middleware(['auth', 'parent'])->group(function () {
    Route::get('parent/dashboard', [DashboardController::class, 'dashboard'])->name('parent.dashboard');
    // Download a single child’s receipt
    Route::get('parent/child/{uuid}/receipt', [GuardianController::class, 'downloadReceipt'])->name('parent.child.receipt');
});