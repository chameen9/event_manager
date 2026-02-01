<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Student;
use App\Models\EventRegistration;
use App\Models\Payment;

class DashboardController extends Controller
{
    public function index(){
        $studentCount = Student::count();
        $registrationCount = EventRegistration::count();
        $registeredPercentage = ($registrationCount / $studentCount) * 100;

        $completedPaymentsCount = Payment::where('status','paid')->count();
        $allPaymentsCount = Payment::count();
        $completedPaymentsPercentage = $allPaymentsCount > 0
            ? round(($completedPaymentsCount / $allPaymentsCount) * 100, 2)
            : 0;
        return view('admin.dashboard', compact([
            'studentCount',
            'registrationCount',
            'registeredPercentage',
            'completedPaymentsCount',
            'allPaymentsCount',
            'completedPaymentsPercentage',
        ]));
    }
}
