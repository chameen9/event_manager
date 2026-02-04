<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\ModuleCompletionImport;
use App\Models\ModuleCompletion;
use App\Models\Module;
use App\Models\EventLog;
use App\Models\Registration;
use DB;

class ModuleController extends Controller
{
    public function toggleModule(Request $request){
        $validated = $request->validate([
            'event_registration_id' => ['required', 'exists:event_registrations,id'],
            'registration_id' => 'required|exists:registrations,id',
            'module_id'       => 'required|exists:modules,id',
        ]);
        //dd($request);

        DB::beginTransaction();

        try {

            $eventRegistrationId = $validated['event_registration_id'];
            $registrationId = $validated['registration_id'];
            $moduleId       = $validated['module_id'];

            // Check if already completed
            $completion = ModuleCompletion::where([
                'registration_id' => $registrationId,
                'module_id'       => $moduleId,
            ])->first();

            $module = Module::findOrFail($moduleId);

            /* ---------------------------------
            | TOGGLE
            --------------------------------- */
            if ($completion) {

                // Remove completion (Mark Pending)
                $completion->delete();

                $status = 'Pending';

            } else {

                // Mark Complete
                ModuleCompletion::create([
                    'registration_id' => $registrationId,
                    'module_id'       => $moduleId,
                    'completed_at'    => now(),
                ]);

                $status = 'Completed';
            }

            /* ---------------------------------
            | LOG
            --------------------------------- */
            EventLog::create([
                'event_registration_id' => $eventRegistrationId,

                'action'      => 'Module Update',

                'description' =>
                    "Module '{$module->name}' marked as {$status} by ~" .
                    auth()->user()->name,

                'created_at'  => now(),
            ]);

            DB::commit();

            return back()->with('success', "Module status updated.");

        } catch (\Throwable $e) {

            DB::rollBack();
            throw $e;
        }
    }

    public function import(Request $request){
        //dd($request->all());
        $request->validate([
            'program_id' => ['required', 'exists:programs,id'],
            'batch_id'   => ['required', 'exists:batches,id'],
            'file'       => ['required', 'file', 'mimes:xlsx,xls'],
        ]);

        try {
            Excel::import(
                new ModuleCompletionImport(
                    $request->program_id,
                    $request->batch_id,
                    auth()->id()
                ),
                $request->file
            );
        } catch (\Throwable $e) {
            return back()->with('error', $e->getMessage());
        }

        return back()->with('success', 'Module completion data imported successfully.');
    }
}
