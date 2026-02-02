<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PaymentHistory;
use App\Models\Payment;
use App\Models\EventLog;
use App\Models\Notification;

class PaymentController extends Controller
{
    public function store(Request $request){
        $validated = $request->validate([
            'event_registration_id' => ['required', 'exists:event_registrations,id'],
            'amount'                => ['required', 'numeric', 'min:1'],
            'remarks'               => ['nullable', 'string', 'max:100'],
            'method'                => ['required', 'string', 'max:20'],
        ]);

        $eventRegistrationId = $request->input('event_registration_id');
        $payment = Payment::where('event_registration_id', $eventRegistrationId)->first();
        $paidAmount = (float) $request->input('amount');
        $due        = (float) ($payment->amount - $payment->paid);
        $method     = $request->input('method');

        if ($paidAmount > $due) {
            return back()->with(
                'error',
                'Payment due was LKR. ' . number_format($due, 2) .
                ' and paid amount is LKR. ' . number_format($paidAmount, 2)
            );
        }

        //dd($due, $paidAmount);

        $history = PaymentHistory::create([
            'event_registration_id' => $validated['event_registration_id'],
            'payment_id'            => $payment->id,
            'amount'                => $validated['amount'],
            'method'                => $method,
            'handled_by'            => auth()->user->name ?? 'system',
        ]);
        if ($history) {

            // Update paid amount
            $payment->increment('paid', $paidAmount);

            // Recalculate due
            $newPaid = $payment->paid + $paidAmount;
            $due     = $payment->amount - $newPaid;

            // Update payment status
            if ($newPaid >= $payment->amount) {
                $payment->update([
                    'status'  => 'paid',
                    'paid_at' => now(),
                ]);
            } else {
                $payment->update([
                    'status' => 'partial',
                ]);
            }

            EventLog::create([
                'event_registration_id' => $validated['event_registration_id'],
                'action'                => 'Payment',
                'description'           => 'New payment received: LKR. ' . number_format($paidAmount, 2) . ' via '. $method,
                'created_at'            => now(),
                'updated_at'            => now(),
            ]);

            return back()->with('success', 'Payment added successfully.');
        }
    }

}
