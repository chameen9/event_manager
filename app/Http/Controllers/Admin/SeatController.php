<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\EventData;
use App\Models\EventRegistration;
use App\Models\EventSeat;
use App\Models\EventShuttle;
use App\Models\EventLog;
use App\Models\Payment;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class SeatController extends Controller
{
    public function store(Request $request){
        $validated = $request->validate([
            'event_registration_id'    => ['required', 'exists:event_registrations,id'],
            'new_additional_seat_count'=> ['required', 'integer', 'min:0'],
        ]);

        $eventId   = 1; // make dynamic later
        $seatPrice = additional_seat_price('Additional');

        DB::beginTransaction();

        try {

            $eventSeat = EventSeat::where('event_registration_id', $validated['event_registration_id'])
                ->lockForUpdate()
                ->firstOrFail();

            $payment = Payment::where('event_registration_id', $validated['event_registration_id'])
                ->lockForUpdate()
                ->firstOrFail();

            $currentSeats = (int) $eventSeat->additional_seat_count;
            $newSeats     = (int) $validated['new_additional_seat_count'];
            $diff         = $newSeats - $currentSeats;

            if ($diff === 0) {
                DB::rollBack();
                return back()->with('info', 'No changes made to additional seats.');
            }

            /* -------------------------------
            | DECREMENT (remove seats)
            | Refund NOT allowed
            -------------------------------- */
            if ($diff < 0) {

                $removeCount  = abs($diff);
                $removeCost   = $removeCount * $seatPrice;
                $currentDue   = $payment->amount - $payment->paid;

                if ($payment->status === 'paid') {
                    DB::rollBack();
                    return back()->with(
                        'error',
                        'Seats cannot be reduced after full payment. Refunds are not allowed.'
                    );
                }

                if ($currentDue < $removeCost) {
                    DB::rollBack();
                    return back()->with(
                        'error',
                        'Cannot reduce seats because the removed value exceeds the current due amount.'
                    );
                }

                $eventSeat->update([
                    'additional_seat_count' => $newSeats,
                    'price' => $eventSeat->price - $removeCost,
                ]);

                $payment->decrement('amount', $removeCost);

                EventLog::create([
                    'event_registration_id' => $validated['event_registration_id'],
                    'action' => 'Additional Seats',
                    'description' =>
                        "{$removeCount} additional seats removed (LKR. {$removeCost}) by ~"
                        . auth()->user()->name,
                    'created_at' => now(),
                ]);
            }

            /* -------------------------------
            | INCREMENT (add seats)
            -------------------------------- */
            if ($diff > 0) {

                $maxSeats = EventData::where('event_id', $eventId)
                    ->value('max_additional_seat_count');

                $usedSeats = EventSeat::whereHas('eventRegistration', function ($q) use ($eventId) {
                    $q->where('event_id', $eventId);
                })->sum('additional_seat_count');

                $remaining = $maxSeats - $usedSeats;

                if ($diff > $remaining) {
                    DB::rollBack();
                    return back()->with(
                        'error',
                        "Only {$remaining} additional seats are available."
                    );
                }

                $extraCost = $diff * $seatPrice;

                $eventSeat->increment('additional_seat_count', $diff);
                $eventSeat->increment('price', $extraCost);
                $payment->increment('amount', $extraCost);

                EventLog::create([
                    'event_registration_id' => $validated['event_registration_id'],
                    'action' => 'Additional Seats',
                    'description' =>
                        "{$diff} additional seats added (LKR. {$extraCost}) by ~"
                        . auth()->user()->name,
                    'created_at' => now(),
                ]);
            }

            /* -------------------------------
            | Recalculate payment status
            -------------------------------- */
            $due = $payment->amount - $payment->paid;

            if ($due <= 0) {
                $payment->update([
                    'status'  => 'paid',
                    'paid_at'=> now(),
                ]);
            } elseif ($payment->paid > 0) {
                $payment->update([
                    'status'  => 'partial',
                    'paid_at'=> null,
                ]);
            } else {
                $payment->update([
                    'status'  => 'pending',
                    'paid_at'=> null,
                ]);
            }

            DB::commit();
            return back()->with('success', 'Additional seats updated successfully.');

        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function shuttleStore(Request $request){
        $validated = $request->validate([
            'event_registration_id'  => ['required', 'exists:event_registrations,id'],
            'new_shuttle_seat_count' => ['required', 'integer', 'min:0'],
        ]);

        $eventId = 1; // make dynamic later
        $unitPrice = shuttle_seat_price('Shuttle');

        DB::beginTransaction();

        try {

            $eventRegistration = EventRegistration::findOrFail(
                $validated['event_registration_id']
            );

            $payment = Payment::where('event_registration_id', $eventRegistration->id)
                ->lockForUpdate()
                ->firstOrFail();

            $shuttle = EventShuttle::firstOrCreate(
                ['event_registration_id' => $eventRegistration->id],
                ['shuttle_seat_count' => 0, 'price' => 0]
            );

            $currentCount = (int) $shuttle->shuttle_seat_count;
            $newCount     = (int) $validated['new_shuttle_seat_count'];
            $diff         = $newCount - $currentCount;
            $costDelta    = abs($diff) * $unitPrice;

            /* -------------------------------
            | Capacity check (increase only)
            -------------------------------- */
            if ($diff > 0) {

                $maxSeats = EventData::where('event_id', $eventId)
                    ->value('max_shuttle_seat_count');

                $usedSeats = EventShuttle::whereHas('eventRegistration', function ($q) use ($eventId) {
                    $q->where('event_id', $eventId);
                })->sum('shuttle_seat_count');

                if (($usedSeats + $diff) > $maxSeats) {
                    DB::rollBack();
                    return back()->with('error', 'Not enough shuttle seats available.');
                }
            }

            /* -------------------------------
            | Decrement rules
            -------------------------------- */
            if ($diff < 0) {

                $currentDue = $payment->amount - $payment->paid;

                if ($payment->status === 'paid') {
                    DB::rollBack();
                    return back()->with(
                        'error',
                        'Cannot reduce shuttle seats after full payment. Refunds are not allowed.'
                    );
                }

                if ($currentDue < $costDelta) {
                    DB::rollBack();
                    return back()->with(
                        'error',
                        'Cannot reduce shuttle seats because the removed value exceeds the current due.'
                    );
                }
            }

            /* -------------------------------
            | Update shuttle
            -------------------------------- */
            $shuttle->update([
                'shuttle_seat_count' => $newCount,
                'price' => $newCount * $unitPrice,
            ]);

            /* -------------------------------
            | Update payment
            -------------------------------- */
            $newTotalAmount = $payment->amount + ($diff * $unitPrice);

            $newStatus = match (true) {
                $payment->paid >= $newTotalAmount => 'paid',
                $payment->paid > 0                => 'partial',
                default                            => 'pending',
            };

            $payment->update([
                'amount' => $newTotalAmount,
                'status' => $newStatus,
            ]);

            EventLog::create([
                'event_registration_id' => $eventRegistration->id,
                'action'      => 'Shuttle Seats',
                'description' =>
                    "Shuttle seats updated from {$currentCount} to {$newCount} by ~"
                    . auth()->user()->name,
                'created_at'  => now(),
            ]);

            DB::commit();
            return back()->with('success', 'Shuttle seats updated successfully.');

        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e;
        }
    }
}