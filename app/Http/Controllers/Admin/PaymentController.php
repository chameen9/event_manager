<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PaymentHistory;
use App\Models\Payment;
use App\Models\EventLog;
use App\Models\Notification;
use App\Models\EventRegistration;
use Illuminate\Support\Facades\Auth;

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
        $remarks    = $request->input('remarks');
        $handledBy  = Auth::user()->name;

        if ($paidAmount > $due) {
            return back()->with(
                'error',
                'Payment due was LKR. ' . number_format($due, 2) .
                ' and paid amount is LKR. ' . number_format($paidAmount, 2)
            );
        }

        $methodMap = [
            'cash'           => 'Cash',
            'online'         => 'Online Payment',
            'bank_transfer'  => 'Bank Transfer',
            'other'          => 'Other',
        ];

        $methodlable = $methodMap[$method] ?? ucfirst(str_replace('_', ' ', $method));

        $history = PaymentHistory::create([
            'event_registration_id' => $validated['event_registration_id'],
            'payment_id'            => $payment->id,
            'amount'                => $validated['amount'],
            'method'                => $method,
            'remarks'               => $remarks,
            'handled_by'            => $handledBy ?? 'system',
        ]);
        if ($history) {

            // Add paid amount
            $payment->increment('paid', $paidAmount);

            // Reload fresh value from DB
            $payment->refresh();

            // Calculate due
            $due = $payment->amount - $payment->paid;

            // Update payment status
            if ($payment->paid >= $payment->amount) {
                $payment->update([
                    'status'  => 'paid',
                    'paid_at' => now(),
                ]);
            } else {
                $payment->update([
                    'status' => 'partial',
                ]);
            }

            if($remarks){
                $message = 'LKR. ' . number_format($paidAmount, 2) . ' recieved via '. $methodlable . ' [' . $remarks . ']';
            }
            else{
                $message = 'LKR. ' . number_format($paidAmount, 2) . ' recieved via '. $methodlable;
            }

            EventLog::create([
                'event_registration_id' => $validated['event_registration_id'],
                'action'                => 'Payment',
                'description'           => $message. ' #'. $history->id . ' ~' . $handledBy,
                'created_at'            => now(),
                'updated_at'            => now(),
            ]);

            $studentId = EventRegistration::find($eventRegistrationId)->value('student_id');

            Notification::create([
                'student_id' => $studentId,
                'channel'    => 'email',
                'subject'    => 'Payment Recieved',
                'message'    => $message
            ]);

            return back()->with('success', 'Payment added successfully.');
        }
    }

}
