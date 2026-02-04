<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Student;
use App\Models\EventRegistration;
use App\Models\EventData;
use App\Models\EventSeat;
use App\Models\EventShuttle;
use App\Models\Payment;
use App\Models\Program;

class DashboardController extends Controller
{
    public function index(){
        $eventId = 1;
        $studentCount = Student::count();
        $registrationCount = EventRegistration::count();
        $registeredPercentage = $studentCount ? round(($registrationCount / $studentCount) * 100, 2) : 0;

        $seatUsage = $this->getSeatUsage($eventId);
        $additionalSeatUsage = $this->getAdditionalSeatUsage($eventId);
        $paymentStats = $this->getPaymentProgress($eventId);

        //Chart Data
        $students = Student::with([
            'registrations.program',
            'eventRegistrations'
        ])
        ->whereHas('eventRegistrations', function ($q) use ($eventId) {
            $q->where('event_id', $eventId);
        })
        ->get();

        $groups = [];

        foreach ($students as $student) {

            // Get program names for this student
            $programs = $student->registrations
                ->pluck('program.name')
                ->unique()
                ->sort()
                ->values()
                ->toArray();

            if (empty($programs)) {
                continue;
            }

            // Create label: DiIT+DiE / DiIT / DiIT+DiM+DiE etc
            $key = implode(' + ', $programs);

            if (!isset($groups[$key])) {
                $groups[$key] = 0;
            }

            $groups[$key]++;
        }
        //dd($groups);

        return view('admin.dashboard', compact([
            'studentCount',
            'registrationCount',
            'registeredPercentage',
            'seatUsage',
            'paymentStats',
            'additionalSeatUsage',
            'groups',
        ]));
    }

    public function getSeatUsage(int $eventId): array{
        // Max seats from EventData
        $maxSeats = EventData::where('event_id', $eventId)
            ->value('max_seat_count');

        // Used seats (count registrations OR seats table)
        $usedSeats = EventSeat::whereHas('eventRegistration', function ($q) use ($eventId) {
            $q->where('event_id', $eventId);
        })->count(); // or ->sum('seat_number') if needed

        // Avoid division by zero
        if ($maxSeats > 0) {
            $percentage = round(($usedSeats / $maxSeats) * 100, 2);
        } else {
            $percentage = 0;
        }

        return [
            'max_seats'   => $maxSeats,
            'used_seats'  => $usedSeats,
            'percentage' => $percentage,
        ];
    }

    public function getAdditionalSeatUsage(int $eventId): array{
        $maxSeats = EventData::where('event_id', $eventId)
            ->value('max_additional_seat_count');

        $usedSeats = EventSeat::whereHas('eventRegistration', function ($q) use ($eventId) {
            $q->where('event_id', $eventId);
        })->sum('additional_seat_count');

        if ($maxSeats > 0) {
            $percentage = round(($usedSeats / $maxSeats) * 100, 2);
        } else {
            $percentage = 0;
        }

        return [
            'max_seats'   => $maxSeats,
            'used_seats'  => $usedSeats,
            'percentage' => $percentage,
        ];
    }

    public function getShuttleSeatUsage(int $eventId): array{
        $maxSeats = EventData::where('event_id', $eventId)
            ->value('max_shuttle_seat_count');

        $usedSeats = EventShuttle::whereHas('eventRegistration', function ($q) use ($eventId) {
            $q->where('event_id', $eventId);
        })->sum('shuttle_seat_count');

        if ($maxSeats > 0) {
            $percentage = round(($usedSeats / $maxSeats) * 100, 2);
        } else {
            $percentage = 0;
        }

        return [
            'max_seats'   => $maxSeats,
            'used_seats'  => $usedSeats,
            'percentage' => $percentage,
        ];
    }

    private function getPaymentProgress(int $eventId): array{
        // Sum of all event payments
        $totalAmount = Payment::whereHas('eventRegistration', function ($q) use ($eventId) {
            $q->where('event_id', $eventId);
        })->sum('amount');

        // Sum of paid amounts
        $totalPaid = Payment::whereHas('eventRegistration', function ($q) use ($eventId) {
            $q->where('event_id', $eventId);
        })->sum('paid');

        // Avoid division by zero
        $percentage = $totalAmount > 0
            ? round(($totalPaid / $totalAmount) * 100, 2)
            : 0;

        return [
            'total'      => $totalAmount,
            'paid'       => $totalPaid,
            'due'        => max(0, $totalAmount - $totalPaid),
            'percentage' => $percentage,
        ];
    }

}
