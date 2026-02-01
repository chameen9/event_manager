<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Student;
use App\Models\EventRegistration;
use App\Models\EventPhotoPackage;
use App\Models\EventSeat;
use App\Models\EventShuttle;
use App\Models\Payment;
use App\Models\EventLog;
use App\Models\Notification;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class EventController extends Controller
{
    public function checkEligibility(Request $request){
        $studentID = $request->input('studentID');
        $student = Student::where('student_id', $studentID)->first();
        //dd($studentID,$student);
        if ($student) {
            $registration = EventRegistration::where('student_id', $student->id)->first();
            //dd($registration);
            if($registration){
                return back()->with('error', 'You have already registered for the Annual Awards Ceremony 2026.');
            }

            return redirect()->route('student.registerStudent', ['studentID' => $studentID]);
            //return back()->with('success', 'Congratulations! You are eligible for the Annual Awards Ceremony 2026.');
        }
        else{
            return back()->with('error', 'Sorry, you are not eligible for the Annual Awards Ceremony 2026.');
        }
    }

    public function registerStudent($studentID){
        $student = Student::where('student_id', $studentID)
            ->with([
                'registrations.program.modules',
                'registrations.moduleCompletions'
            ])
            ->first();

        if (!$student) {
            return redirect()
                ->route('student.register')
                ->with('error', 'Invalid student ID.');
        }

        $registrationsData = $student->registrations->map(function ($registration) {

            $programModules = $registration->program->modules;

            $completedModuleIds = $registration->moduleCompletions
                ->pluck('module_id')
                ->toArray();

            $modulesStatus = $programModules->map(function ($module) use ($completedModuleIds) {
                return [
                    'module_order'      => $module->module_order,
                    'name'      => $module->name,
                    'completed' => in_array($module->id, $completedModuleIds),
                ];
            });

            return [
                'program_name' => $registration->program->name,
                'registration' => $registration,
                'modules'      => $modulesStatus,
                'isEligible'   => $modulesStatus->where('completed', false)->isEmpty(),
            ];
        });
        //dd($registrationsData);
        $hasIncompletePrograms = $registrationsData
            ->contains(fn ($data) => $data['isEligible'] === false);

        return view('student.register_form', [
            'student'           => $student,
            'registrationsData' => $registrationsData,
            'hasIncompletePrograms' => $hasIncompletePrograms,
        ]);
    }

    public function registerSubmit(Request $request){
        //dd($request->all());
        //Event Config
        
        $thisEventId = 1;
        $packagePrice = 6500;

        $studentID = $request->student_id;

        $student = Student::where('student_id', $studentID)->first();
        if(!$student){
            return redirect()
                ->route('student.register')
                ->with('error', 'Your Student ID does not match with our records. Contact the campus and clarify the issue.');
        }

        $isExists = EventRegistration::where('student_id', $student->id)->where('event_id', $thisEventId)->first();
        if($isExists){
            return redirect()
                ->route('student.registerStudent', ['studentID' => $studentID])
                ->withInput()
                ->with('error', 'Already registered for this event')
                ->withFragment('summary');
        }

        // Determine eligibility again (DO NOT trust frontend)
        $hasIncompletePrograms = $this->hasIncompleteModules($studentID);

        $rules = [
            'student_id' => ['required', 'string'],
            'email'      => ['required', 'email'],
            'phone'      => ['required', 'digits:10'],

            'burst_photo_count'   => ['required', 'integer', 'min:0', 'max:10'],
            'full_photo_count'    => ['required', 'integer', 'min:0', 'max:10'],
            'family_photo_count'  => ['required', 'integer', 'min:0', 'max:10'],

            'additional_seat_count' => ['required', 'integer', 'min:0', 'max:5'],
            'shuttle_seat_count'    => ['required', 'integer', 'min:0', 'max:5'],

            'final_amount' => ['required', 'integer', 'min:0'],
        ];

        // Conditional declaration rule
        if ($hasIncompletePrograms) {
            $rules['completion_acknowledgement'] = ['accepted'];
        }

        $validator = Validator::make(
            $request->all(),
            $rules,
            [
                'completion_acknowledgement.accepted' =>
                    'You must accept the declaration to proceed with incomplete programs.',
            ]
        );

        if ($validator->fails()) {
            return redirect()
                ->route('student.registerStudent', ['studentID' => $studentID])
                ->withErrors($validator)
                ->withInput()
                ->withFragment('summary');
        }

        $validated = $request->validate($rules, [
            'completion_acknowledgement.accepted' =>
                'You must accept the declaration to proceed with incomplete programs.',
        ]);

        // -----------------------------------------
        // Optional: validate final amount integrity
        // -----------------------------------------
        $expectedAmount = (int) round(
            ($validated['burst_photo_count'] * photo_price('12x8" Burst Photo')) +
            ($validated['full_photo_count'] * photo_price('12x8" Full Photo')) +
            ($validated['family_photo_count'] * photo_price('12x8" Family Photo')) +
            ($validated['additional_seat_count'] * additional_seat_price('Additional')) +
            ($validated['shuttle_seat_count'] * shuttle_seat_price('Shuttle')) +
            $packagePrice
        );

        $submittedAmount = (int) $validated['final_amount'];

        if ($submittedAmount !== $expectedAmount) {
            return redirect()
                ->route('student.registerStudent', ['studentID' => $studentID])
                ->withInput()
                ->with('error', 'Payment calculation mismatch. Please review your selections.')
                ->withFragment('summary');
        }

        // -----------------------------------------
        // Save data / trigger emails / generate qr
        // -----------------------------------------

        // 1. Generate QR
        $qrCode = 'QR-' . Str::uuid() . '-AWARD-2026';

        // 2. Prepare file name
        $fileName = $qrCode . '.png';
        $directory = public_path('qrcodes');

        // 3. Ensure directory exists
        if (!file_exists($directory)) {
            mkdir($directory, 0755, true);
        }

        $filePath = $directory . '/' . $fileName;

        // 4. Generate QR image FIRST
        QrCode::format('png')
            ->size(300)
            ->margin(2)
            ->generate($qrCode, $filePath);

        // 5. Save record in Event Registrations
        $eventRegistration = EventRegistration::create([
            'event_id'  => $thisEventId,
            'student_id'=> $student->id,
            'qr_code'   => $qrCode,
            'status'    => 'registered',
            'created_at'=> Carbon::now('Asia/Colombo')
        ]);

        EventLog::create([
            'event_registration_id' => $eventRegistration->id,
            'action' => 'Registration',
            'description' => "Registration created under #".$eventRegistration->id,
            'created_at' => now()
        ]);

        if($request->input('email') !== $student->email){
            if($student->email){
                EventLog::create([
                    'event_registration_id' => $eventRegistration->id,
                    'action' => 'Registration',
                    'description' => "email updated. old: ".$student->email,
                    'created_at' => now()
                ]);
            }
            $student->update([
                'email' => $request->input('email'),
            ]);
        }

        if($request->input('phone') !== $student->phone){
            if($student->phone){
                EventLog::create([
                    'event_registration_id' => $eventRegistration->id,
                    'action' => 'Registration',
                    'description' => "phone updated. old: ".$student->phone,
                    'created_at' => now()
                ]);
            }
            $student->update([
                'phone' => $request->input('phone'),
            ]);
        }

        // 6. Create pending payment
        $thisPayment = Payment::create([
            'event_registration_id' => $eventRegistration->id,
            'paid' => 0.00,
            'amount' => $expectedAmount,
            'status' => 'pending'
        ]);

        EventLog::create([
            'event_registration_id' => $eventRegistration->id,
            'action' => 'Payment',
            'description' => 'Payment created #'.$thisPayment->id,
            'created_at' => now()
        ]);

        // 7. Save photos taken
        if($validated['burst_photo_count'] > 0){
            EventPhotoPackage::create([
                'event_registration_id' => $eventRegistration->id,
                'photo_package_id' => 1, // For Burst Photos
                'quantity' => $validated['burst_photo_count'],
                'price' => ($validated['burst_photo_count'] * photo_price('12x8" Burst Photo')),
                'created_at' => now()
            ]);
        }
        if($validated['full_photo_count'] > 0){
            EventPhotoPackage::create([
                'event_registration_id' => $eventRegistration->id,
                'photo_package_id' => 2, // For Full Photos
                'quantity' => $validated['full_photo_count'],
                'price' => ($validated['full_photo_count'] * photo_price('12x8" Full Photo')),
                'created_at' => now()
            ]);
        }
        if($validated['family_photo_count'] > 0){
            EventPhotoPackage::create([
                'event_registration_id' => $eventRegistration->id,
                'photo_package_id' => 3, // For Family Photos
                'quantity' => $validated['family_photo_count'],
                'price' => ($validated['family_photo_count'] * photo_price('12x8" Family Photo')),
                'created_at' => now()
            ]);
        }

        // Handle additional seats and seat number
        $seatNumber = $this->getNextSeatNumber($thisEventId);

        if($validated['additional_seat_count'] > 0){
            $additionalSeatCount = $validated['additional_seat_count'];
            $additionalSeatPrice = ($validated['additional_seat_count'] * additional_seat_price('Additional'));
            EventLog::create([
                'event_registration_id' => $eventRegistration->id,
                'action' => 'Registration',
                'description' => $additionalSeatCount. ' additional seats reserved',
                'created_at' => now()
            ]);
        }
        else{
            $additionalSeatCount = 0;
            $additionalSeatPrice = 0.00;
        }

        $seat = EventSeat::create([
            'event_registration_id' => $eventRegistration->id,
            'seat_number' => $seatNumber,
            'additional_seat_count' => $additionalSeatCount,
            'price' => $additionalSeatPrice,
            'created_at' => now()
        ]);
        EventLog::create([
            'event_registration_id' => $eventRegistration->id,
            'action' => 'Registration',
            'description' => 'Seat No: '.$seatNumber. ' reserved',
            'created_at' => now()
        ]);

        // Handle shuttle seats
        if($validated['shuttle_seat_count'] > 0){
            EventShuttle::create([
                'event_registration_id' => $eventRegistration->id,
                'shuttle_seat_count' => $validated['shuttle_seat_count'],
                'price' => ($validated['shuttle_seat_count'] * shuttle_seat_price('Shuttle')),
                'created_at' => now()
            ]);
            EventLog::create([
                'event_registration_id' => $eventRegistration->id,
                'action' => 'Registration',
                'description' => $validated['shuttle_seat_count']. ' seats reserved in shuttle service',
                'created_at' => now()
            ]);
        }
        if($hasIncompletePrograms){
            $message = 'Incomplete';
        }
        else{
            $message = 'Completed';
        }

        Notification::create([
            'student_id' => $student->id,
            'channel' => 'email',
            'subject' => 'Registration Confirmed',
            'message' => $message,
            //'sent_at' => null
        ]);

        return redirect()
            ->route('student.registerStudent', ['studentID' => $studentID])
            ->with('success', 'Registration submitted successfully.')
            ->withFragment('summary');
    }

    private function hasIncompleteModules(string $studentID): bool{
        $student = Student::where('student_id', $studentID)
            ->with([
                'registrations.program.modules',
                'registrations.moduleCompletions'
            ])
            ->first();

        if (!$student) {
            // If student not found, treat as incomplete
            return true;
        }

        foreach ($student->registrations as $registration) {

            $programModules = $registration->program->modules;

            $completedModuleIds = $registration->moduleCompletions
                ->pluck('module_id')
                ->toArray();

            foreach ($programModules as $module) {
                if (!in_array($module->id, $completedModuleIds)) {
                    // Found at least one incomplete module
                    return true;
                }
            }
        }

        // All modules in all registrations are completed
        return false;
    }

    private function getNextSeatNumber(int $eventId): int{
        return \App\Models\EventRegistration::where('event_id', $eventId)->count() + 1;
    }
}
