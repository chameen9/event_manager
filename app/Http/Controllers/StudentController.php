<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Student;

class StudentController extends Controller
{
    public function checkEligibility(Request $request)
    {
        $studentID = $request->input('studentID');

        $student = Student::where('student_id', $studentID)->first();
        if ($student) {
            return redirect()->route('student.registerStudent', ['studentID' => $studentID]);
            //return back()->with('success', 'Congratulations! You are eligible for the Annual Awards Ceremony 2026.');
        }
        else{
            return back()->with('error', 'Sorry, you are not eligible for the Annual Awards Ceremony 2026.');
        }
    }

    public function registerStudent($studentID)
    {
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
        $studentID = $request->student_id;
        //return back()->with('error','hello');
        return redirect()
            ->route('student.registerStudent', ['studentID' => $studentID])
            ->withInput()
            ->with('error', 'Registrations are not yet opened. Please wait!')
            ->withFragment('summary');
    }
}
