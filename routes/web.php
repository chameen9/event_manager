<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\StudentController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::get('/register', function () {
    return view('student.register');
})->name('student.register');

Route::post('/check-eligibility', [StudentController::class, 'checkEligibility'])->name('student.checkEligibility');
Route::get('/register-student-{studentID}', [StudentController::class, 'registerStudent'])->name('student.registerStudent');
Route::post('/register-submit', [StudentController::class, 'registerSubmit'])->name('student.register.submit');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
