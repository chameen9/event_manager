<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\Admin\StudentController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\PaymentController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/register', function () {
    return view('student.register');
})->name('student.register');

Route::post('/check-eligibility', [EventController::class, 'checkEligibility'])->name('student.checkEligibility');
Route::get('/register-student-{studentID}', [EventController::class, 'registerStudent'])->name('student.registerStudent');
Route::post('/register-submit', [EventController::class, 'registerSubmit'])->name('student.register.submit');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    //Manage Students
    Route::get('/students', [StudentController::class, 'index'])->name('admin.students.index');
    Route::get('/students-add', [StudentController::class, 'showAdd'])->name('admin.students.showAdd');
    Route::get('/students-edit-{id}', [StudentController::class, 'showEdit'])->name('admin.students.showEdit');
    Route::get('/students-import', [StudentController::class, 'showImport'])->name('admin.students.showImport');
    Route::post('/students-update-{id}', [StudentController::class, 'update'])->name('admin.students.update');

    //Manage Payments
    Route::post('/admin/payments/add', [PaymentController::class, 'store'])->name('admin.payments.store');

    Route::post('/admin/import/module-completions', [\App\Http\Controllers\Admin\ModuleImportController::class, 'import'])
        ->name('admin.moduleCompletion.import');

    Route::get('/admin/program/{program}/batches', function ($programId) {
            return \App\Models\Batch::where('program_id', $programId)
                ->orderBy('batch_code')
                ->get(['id', 'batch_code']);
        })->name('admin.program.batches');
});

require __DIR__.'/auth.php';
