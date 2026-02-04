<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\EventData;
use App\Models\EventRegistration;
use App\Models\EventPhotoPackage;
use App\Models\PhotoPackage;
use App\Models\EventLog;
use App\Models\Payment;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class PhotoController extends Controller
{
    // public function store(Request $request){
    //     $validated = $request->validate([
    //         'event_registration_id' => ['required', 'exists:event_registrations,id'],
    //         'photos' => ['required', 'array'],
    //         'photos.*.photo_package_id' => ['required', 'exists:photo_packages,id'],
    //         'photos.*.new_quantity' => ['required', 'integer', 'min:0'],
    //     ]);

    //     DB::beginTransaction();

    //     try {

    //         $eventRegistrationId = $validated['event_registration_id'];

    //         $payment = Payment::where('event_registration_id', $eventRegistrationId)
    //             ->lockForUpdate()
    //             ->firstOrFail();

    //         foreach ($validated['photos'] as $photoData) {

    //             $packageId   = (int) $photoData['photo_package_id'];
    //             $newQty      = (int) $photoData['new_quantity'];

    //             $photoPackage = PhotoPackage::findOrFail($packageId);
    //             $unitPrice    = (float) $photoPackage->price;

    //             $eventPhoto = EventPhotoPackage::where('event_registration_id', $eventRegistrationId)
    //                 ->where('photo_package_id', $packageId)
    //                 ->lockForUpdate()
    //                 ->first();

    //             $currentQty   = $eventPhoto?->quantity ?? 0;
    //             $difference   = $newQty - $currentQty;

    //             if ($difference === 0) {
    //                 continue;
    //             }

    //             $priceDelta = $difference * $unitPrice;

    //             /* ---------------------------------------------
    //             | DECREMENT (NO REFUND RULE)
    //             --------------------------------------------- */
    //             if ($difference < 0) {

    //                 if ($payment->status === 'paid') {
    //                     DB::rollBack();
    //                     return back()->with(
    //                         'error',
    //                         'Photo quantities cannot be reduced after full payment. Refunds are not allowed.'
    //                     );
    //                 }

    //                 $newTotal = $payment->amount + $priceDelta;

    //                 if ($payment->paid > $newTotal) {
    //                     DB::rollBack();
    //                     return back()->with(
    //                         'error',
    //                         'Cannot reduce photos. Paid amount exceeds revised total.'
    //                     );
    //                 }
    //             }

    //             /* ---------------------------------------------
    //             | UPDATE PHOTO PACKAGE
    //             --------------------------------------------- */
    //             if ($eventPhoto) {
    //                 if ($newQty === 0) {
    //                     $eventPhoto->delete();
    //                 } else {
    //                     $eventPhoto->update([
    //                         'quantity' => $newQty,
    //                         'price'    => $newQty * $unitPrice,
    //                     ]);
    //                 }
    //             } else {
    //                 EventPhotoPackage::create([
    //                     'event_registration_id' => $eventRegistrationId,
    //                     'photo_package_id'      => $packageId,
    //                     'quantity'              => $newQty,
    //                     'price'                 => $newQty * $unitPrice,
    //                 ]);
    //             }

    //             /* ---------------------------------------------
    //             | UPDATE PAYMENT AMOUNT
    //             --------------------------------------------- */
    //             $payment->update([
    //                 'amount' => $payment->amount + $priceDelta,
    //             ]);

    //             /* ---------------------------------------------
    //             | LOG
    //             --------------------------------------------- */
    //             EventLog::create([
    //                 'event_registration_id' => $eventRegistrationId,
    //                 'action'      => 'Photo Package',
    //                 'description' => "{$photoPackage->name} updated from {$currentQty} to {$newQty} by ~".auth()->user()->name,
    //                 'created_at'  => now(),
    //             ]);
    //         }

    //         /* ---------------------------------------------
    //         | RECALCULATE PAYMENT STATUS
    //         --------------------------------------------- */
    //         $due = $payment->amount - $payment->paid;

    //         if ($due <= 0) {
    //             $payment->update([
    //                 'status'  => 'paid',
    //                 'paid_at' => now(),
    //             ]);
    //         } elseif ($payment->paid > 0) {
    //             $payment->update([
    //                 'status'  => 'partial',
    //                 'paid_at' => null,
    //             ]);
    //         } else {
    //             $payment->update([
    //                 'status'  => 'pending',
    //                 'paid_at' => null,
    //             ]);
    //         }

    //         DB::commit();
    //         return back()->with('success', 'Photo packages updated successfully.');

    //     } catch (\Throwable $e) {
    //         DB::rollBack();
    //         throw $e;
    //     }
    // }

    // public function store(Request $request){
    //     $validated = $request->validate([
    //         'event_registration_id' => ['required', 'exists:event_registrations,id'],
    //         'photos'                => ['required', 'array'],
    //         'photos.*'              => ['integer', 'min:0'],
    //     ]);

    //     DB::beginTransaction();

    //     try {

    //         $eventRegistrationId = $validated['event_registration_id'];

    //         $payment = Payment::where('event_registration_id', $eventRegistrationId)
    //             ->lockForUpdate()
    //             ->firstOrFail();

    //         foreach ($validated['photos'] as $photoPackageId => $newQty) {

    //             $photo = EventPhotoPackage::where([
    //                     'event_registration_id' => $eventRegistrationId,
    //                     'photo_package_id'      => $photoPackageId,
    //                 ])
    //                 ->lockForUpdate()
    //                 ->first();

    //             // If not exists yet, create row
    //             if (!$photo) {
    //                 $unitPrice = photo_price($photoPackageId); // helper
    //                 $photo = EventPhotoPackage::create([
    //                     'event_registration_id' => $eventRegistrationId,
    //                     'photo_package_id'      => $photoPackageId,
    //                     'quantity'              => 0,
    //                     'price'                 => 0,
    //                 ]);
    //             }

    //             $currentQty = (int) $photo->quantity;
    //             $newQty     = (int) $newQty;
    //             $diff       = $newQty - $currentQty;

    //             if ($diff === 0) {
    //                 continue;
    //             }

    //             $unitPrice = photo_price($photoPackageId);
    //             $priceDiff = $diff * $unitPrice;

    //             /*
    //             |----------------------------------
    //             | DECREMENT (refund NOT allowed)
    //             |----------------------------------
    //             */
    //             if ($diff < 0) {

    //                 if ($payment->status === 'paid') {
    //                     DB::rollBack();
    //                     return back()->with(
    //                         'error',
    //                         'Cannot reduce photo packages after full payment. Refunds are not allowed.'
    //                     );
    //                 }

    //                 // Prevent paid > new total
    //                 if (($payment->amount + $priceDiff) < $payment->paid) {
    //                     DB::rollBack();
    //                     return back()->with(
    //                         'error',
    //                         'Cannot reduce photos. Paid amount would exceed new total.'
    //                     );
    //                 }

    //                 $photo->update([
    //                     'quantity' => $newQty,
    //                     'price'    => $newQty * $unitPrice,
    //                 ]);

    //                 $payment->decrement('amount', abs($priceDiff));
    //             }

    //             /*
    //             |----------------------------------
    //             | INCREMENT
    //             |----------------------------------
    //             */
    //             if ($diff > 0) {

    //                 $photo->update([
    //                     'quantity' => $newQty,
    //                     'price'    => $newQty * $unitPrice,
    //                 ]);

    //                 $payment->increment('amount', $priceDiff);
    //             }

    //             EventLog::create([
    //                 'event_registration_id' => $eventRegistrationId,
    //                 'action'      => 'Photo Packages',
    //                 'description' => "Photo package {$photoPackageId} updated from {$currentQty} to {$newQty} by ~".auth()->user()->name,
    //                 'created_at'  => now(),
    //             ]);
    //         }

    //         /*
    //         |----------------------------------
    //         | Recalculate payment status
    //         |----------------------------------
    //         */
    //         $due = $payment->amount - $payment->paid;

    //         if ($due <= 0) {
    //             $payment->update([
    //                 'status'  => 'paid',
    //                 'paid_at' => now(),
    //             ]);
    //         } elseif ($payment->paid > 0) {
    //             $payment->update([
    //                 'status'  => 'partial',
    //                 'paid_at' => null,
    //             ]);
    //         } else {
    //             $payment->update([
    //                 'status'  => 'pending',
    //                 'paid_at' => null,
    //             ]);
    //         }

    //         DB::commit();
    //         return back()->with('success', 'Photo packages updated successfully.');

    //     } catch (\Throwable $e) {
    //         DB::rollBack();
    //         throw $e;
    //     }
    // }

    public function store(Request $request){
        $validated = $request->validate([
            'event_registration_id' => ['required', 'exists:event_registrations,id'],
            'photos'                => ['required', 'array'],
            'photos.*'              => ['required', 'integer', 'min:0'],
        ]);

        DB::beginTransaction();

        try {
            $eventRegistrationId = $validated['event_registration_id'];

            $payment = Payment::where('event_registration_id', $eventRegistrationId)
                ->lockForUpdate()
                ->firstOrFail();

            foreach ($validated['photos'] as $photoPackageId => $newQty) {

                $photo = EventPhotoPackage::where([
                        'event_registration_id' => $eventRegistrationId,
                        'photo_package_id'      => $photoPackageId,
                    ])
                    ->lockForUpdate()
                    ->firstOrFail();

                $currentQty  = (int) $photo->quantity;
                $newQty      = (int) $newQty;
                $diff        = $newQty - $currentQty;

                if ($diff === 0) {
                    continue; // no change
                }

                // Unit price (single source of truth)
                $unitPrice = PhotoPackage::where('id', $photoPackageId)->value('price');
                $packageName = PhotoPackage::where('id', $photoPackageId)->value('name');

                $deltaAmount = $diff * $unitPrice;

                /* ---------------------------------------
                | Prevent invalid reduction
                --------------------------------------- */
                if ($diff < 0) {
                    $newTotalAmount = $payment->amount + $deltaAmount;

                    if ($payment->paid > $newTotalAmount) {
                        DB::rollBack();
                        return back()->with(
                            'error',
                            'Cannot reduce photos. Paid amount exceeds revised total.'
                        );
                    }
                }

                /* ---------------------------------------
                | Update photo package
                --------------------------------------- */
                $photo->update([
                    'quantity' => $newQty,
                    'price'    => $newQty * $unitPrice, // ✅ FIX
                ]);

                /* ---------------------------------------
                | Update payment total
                --------------------------------------- */
                $payment->update([
                    'amount' => $payment->amount + $deltaAmount,
                ]);

                EventLog::create([
                    'event_registration_id' => $eventRegistrationId,
                    'action'      => 'Photos',
                    'description' => "{$packageName} count updated from {$currentQty} to {$newQty} by ~" . auth()->user()->name,
                    'created_at'  => now(),
                ]);
            }

            /* ---------------------------------------
            | Recalculate payment status
            --------------------------------------- */
            $due = $payment->amount - $payment->paid;

            $payment->update([
                'status' => match (true) {
                    $due <= 0             => 'paid',
                    $payment->paid > 0    => 'partial',
                    default               => 'pending',
                },
                'paid_at' => $due <= 0 ? now() : null,
            ]);

            DB::commit();
            return back()->with('success', 'Photo packages updated successfully.');

        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e;
        }
    }

}
