<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Student;
use App\Models\EventRegistration;
use App\Models\EventPhotoPackage;
use App\Models\EventSeat;
use App\Models\EventShuttle;
use App\Models\Payment;
use App\Models\EventLog;
use App\Models\Program;
use App\Models\Batch;
use App\Models\Notification;

class StudentController extends Controller
{
    public function index(Request $request){
        $students = Student::with([
            'batches',
            'registrations.program.modules',
            'registrations.moduleCompletions',
            'eventRegistrations.payment'
        ])->get();

        $studentCount = Student::count();

        $studentsData = $students->map(function ($student) {

            $eventRegistration = $student->eventRegistrations->first();

            // Payment logic
            $paymentStatus = 'Not Registered';

            if ($eventRegistration) {
                $payment = $eventRegistration->payment;

                if (!$payment) {
                    $paymentStatus = 'Pending';
                } elseif ($payment->status === 'paid') {
                    $paymentStatus = 'Paid';
                } elseif ($payment->status === 'pending') {
                    $paymentStatus = 'Pending';
                } else {
                    $paymentStatus = ucfirst($payment->status);
                }
            }

            // Program completion
            $programs = $student->registrations->map(function ($registration) {

                $programModules = $registration->program->modules;

                $completedModuleIds = $registration->moduleCompletions
                    ->pluck('module_id')
                    ->toArray();

                $totalModules = $programModules->count();

                $completedModules = $programModules
                    ->whereIn('id', $completedModuleIds)
                    ->count();

                return [
                    'program_name'      => $registration->program->name,
                    'completed'         => $completedModules === $totalModules,
                    'completed_modules' => $completedModules,
                    'total_modules'     => $totalModules,
                ];
            });

            return [
                'student'        => $student,
                'batches'        => $student->batches,
                'is_registered'  => $eventRegistration !== null,
                'payment_status' => $paymentStatus,
                'programs'       => $programs,
            ];
        });

        return view('admin.students.index', compact([
            'studentsData',
            'studentCount'
        ]));
    }

    public function showAdd(){
        return view('admin.students.add');
    }

    public function showEdit($id){
        $student = Student::where('id', $id)
            ->with([
                'batches',
                'registrations.program.modules',
                'registrations.moduleCompletions',
                'eventRegistrations.payment'
            ])
            ->firstOrFail();

        /*
        |--------------------------------------------------------------------------
        | Event registration & payment status
        |--------------------------------------------------------------------------
        */
        $eventRegistration = $student->eventRegistrations->first();

        $paymentStatus = 'Not Registered';

        if ($eventRegistration) {
            $payment = $eventRegistration->payment;

            if (!$payment) {
                $paymentStatus = 'Pending';
            } elseif ($payment->status === 'paid') {
                $paymentStatus = 'Paid';
            } else {
                $paymentStatus = ucfirst($payment->status);
            }
        }

        /*
        |--------------------------------------------------------------------------
        | Programs + modules with completion state
        |--------------------------------------------------------------------------
        */
        $programs = $student->registrations->map(function ($registration) {

            $program = $registration->program;

            // Completed module IDs for this registration
            $completedModuleIds = $registration->moduleCompletions
                ->pluck('module_id')
                ->toArray();

            // All modules with per-module status
            $modules = $program->modules
                ->sortBy('module_order')
                ->map(function ($module) use ($completedModuleIds) {
                    return [
                        'module_id'    => $module->id,
                        'module_order' => $module->module_order,
                        'module_name'  => $module->name,
                        'completed'    => in_array($module->id, $completedModuleIds),
                    ];
                });

            return [
                'registration_id' => $registration->id,
                'program_id'      => $program->id,
                'program_name'    => $program->name,
                'is_completed'    => $modules->where('completed', false)->isEmpty(),
                'modules'         => $modules,
            ];
        });

        /*
        |--------------------------------------------------------------------------
        | Final payload to view
        |--------------------------------------------------------------------------
        */
        $studentData = [
            'student'        => $student,
            'batches'        => $student->batches,
            'is_registered'  => $eventRegistration !== null,
            'payment_status' => $paymentStatus,
            'programs'       => $programs,
        ];
        //dd($studentData);
        // $student = Student::with('eventRegistrations')
        //     ->where('id', $id)
        //     ->firstOrFail();

        $eventRegistration = $student->eventRegistrations
            ->where('event_id', 1) // change if dynamic
            ->first();

        $eventData = null;
        $eventPhotos = null;
        $eventPayment = null;

        if ($eventRegistration) {
            $eventData = EventRegistration::with([
                'payment',
                'seat',
                'shuttleSeats',
                'logs'
            ])->findOrFail($eventRegistration->id);

            $eventPhotos = EventPhotoPackage::where('event_registration_id',$eventRegistration->id)
                ->join('photo_packages','photo_packages.id','event_registration_photo_packages.photo_package_id')
                ->select('event_registration_photo_packages.*','photo_packages.name')   
                ->get();

            $eventPayment = Payment::where('event_registration_id',$eventRegistration->id)->first();
        }

        //dd($eventData->seat);

        return view('admin.students.edit', compact(['studentData','eventData','eventPhotos','eventPayment']));

    }

    public function showImport(){
        $programs = Program::all();
        $batches = Batch::all();
        return view('admin.students.import', compact(['programs','batches']));
    }
}
