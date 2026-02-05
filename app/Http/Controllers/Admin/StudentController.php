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
use App\Models\EventData;
use App\Models\Payment;
use App\Models\EventLog;
use App\Models\Program;
use App\Models\Batch;
use App\Models\Notification;
use App\Models\PhotoPackage;

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

        $photoPackages = PhotoPackage::get();
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

        /* -------------------------------------------------
        | Additional seat availability
        ------------------------------------------------- */
        $eventId = 1;
        $maxAdditionalSeats = EventData::where('event_id', $eventId)
            ->value('max_additional_seat_count');

        $usedAdditionalSeats = EventSeat::whereHas('eventRegistration', function ($q) use ($eventId) {
            $q->where('event_id', $eventId);
        })->sum('additional_seat_count');

        $additionalSeatsAvailable = $usedAdditionalSeats < $maxAdditionalSeats;

        /* -------------------------------------------------
        | Shuttle seat availability
        ------------------------------------------------- */
        $maxShuttleSeats = EventData::where('event_id', $eventId)
            ->value('max_shuttle_seat_count');

        $usedShuttleSeats = EventShuttle::whereHas('eventRegistration', function ($q) use ($eventId) {
            $q->where('event_id', $eventId);
        })->sum('shuttle_seat_count');

        $shuttleSeatsAvailable = $usedShuttleSeats < $maxShuttleSeats;

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

        return view('admin.students.edit', compact([
            'studentData',
            'eventData',
            'eventPhotos',
            'eventPayment',
            'additionalSeatsAvailable',
            'shuttleSeatsAvailable',
            'photoPackages'
            ]));

    }

    public function showImport(){
        $programs = Program::all();
        $batches = Batch::all();
        return view('admin.students.import', compact(['programs','batches']));
    }

    public function update(Request $request){
        $student = Student::findOrFail($request->input('id'));

        // Store old values before update
        $oldData = $student->only(['full_name', 'email', 'phone']);

        // Validate
        $validated = $request->validate([
            'full_name' => 'required|string|max:255',
            'email'     => 'required|email',
            'phone'     => 'required|string|max:20',
        ]);

        // Update student
        $student->update($validated);

        /* ----------------------------------
        | Build change log
        ---------------------------------- */
        $changes = [];

        foreach ($validated as $field => $newValue) {
            $oldValue = $oldData[$field];

            if ($oldValue != $newValue) {
                $changes[] = ucfirst(str_replace('_', ' ', $field))
                    . ": '{$oldValue}' → '{$newValue}'";
            }
        }

        /* ----------------------------------
        | Save log if something changed
        ---------------------------------- */
        if (!empty($changes)) {

            EventLog::create([
                'event_registration_id' => optional($student->eventRegistrations->first())->id,
                'action'      => 'Student Update',
                'description' => implode(', ', $changes) . ' by ~' . auth()->user()->name,
                'created_at'  => now(),
            ]);
        }

        return back()->with('success', 'Student details updated.');
    }

}
