<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\ModuleCompletionImport;

class ModuleImportController extends Controller
{
    public function import(Request $request)
    {
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
