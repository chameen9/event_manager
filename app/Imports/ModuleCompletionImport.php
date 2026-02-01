<?php

namespace App\Imports;

use App\Models\Student;
use App\Models\Registration;
use App\Models\Module;
use App\Models\ModuleCompletion;
use App\Models\Batch;
use App\Models\BatchStudent;
use App\Models\Program;
use App\Models\EventLog;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Carbon\Carbon;

class ModuleCompletionImport implements ToCollection, WithHeadingRow
{
    private int $programId;
    private int $batchId;
    private int $userId;

    public function __construct(int $programId, int $batchId, int $userId)
    {
        $this->programId = $programId;
        $this->batchId   = $batchId;
        $this->userId    = $userId;
    }

    public function collection(Collection $rows)
    {
        DB::transaction(function () use ($rows) {

            foreach ($rows as $index => $row) {
                //dd($row);

                $rowNumber = $index + 1; // Excel data starts at row 2

                //dd(trim($row['status']));
                // Only ACTIVE students
                if (trim($row['status'] ?? '') !== 'Active') {
                    continue;
                }

                //dd($row);
                // Student ID
                $studentId = trim($row['student_id'] ?? '');
                $excelName = trim($row['name'] ?? '');

                //dd($studentId, $excelName);
                if ($studentId === '') {
                    throw new \Exception("Row {$rowNumber}: Student ID is empty");
                }

                $student = Student::where('student_id', $studentId)->first();
                //dd($student);

                if (!$student) {
                    //Create student
                    $student = Student::create([
                        'student_id' => $studentId,
                        'full_name' => $excelName,
                        'created_at' => now()
                    ]);

                    // throw new \Exception(
                    //     "Row {$rowNumber}: Student ID {$studentId} not found"
                    // );
                }
                else{
                    //Do not create
                }
                //dd($this->batchId);
                $existingBatchStudent = BatchStudent::where([
                    'student_id' => $student->id,
                    'batch_id' => $this->batchId,
                ])->first();

                if(!$existingBatchStudent){
                    BatchStudent::create([
                        'student_id' => $student->id,
                        'batch_id' => $this->batchId,
                        'joined_at' => now(),
                        'left_at' => now(),
                        'status' => trim($row['status'])
                    ]);
                }
                else{
                    $existingBatch = Batch::where('id',$this->batchId)->first();
                    //dd($existingBatch);
                    throw new \Exception(
                        "Row {$rowNumber}: Student {$student->student_id} already exists in batch {$existingBatch->batch_code}"
                    );
                }

                // Update full name if changed
                // if ($excelName !== '' && $student->full_name !== $excelName) {
                //     $student->full_name = $excelName;
                //     $student->save();
                // }

                // Find registration
                $registration = Registration::where([
                    'student_id' => $student->id,
                    'program_id' => $this->programId,
                ])->first();

                if ($registration) {
                    $program = Program::find($this->programId)->first();
                    throw new \Exception(
                        "Row {$rowNumber}: {$program->name} Registration already found for Student {$studentId}"
                    );
                }
                else{
                    $registration = Registration::create([
                        'student_id' => $student->id,
                        'program_id' => $this->programId,
                        'registered_at' => Carbon::now(),
                        'status' => trim($row['status'])
                    ]);
                }

                // Loop through module columns
                foreach ($row as $column => $value) {

                    if (!str_starts_with(strtolower($column), 'module_')) {
                        continue;
                    }

                    if (strtoupper(trim($value)) !== 'Y') {
                        continue;
                    }

                    $moduleOrder = (int) str_replace('module_', '', strtolower($column));

                    if ($moduleOrder <= 0) {
                        continue;
                    }

                    $module = Module::where([
                        'program_id'   => $this->programId,
                        'module_order' => $moduleOrder,
                    ])->first();

                    if (!$module) {
                        throw new \Exception(
                            "Row {$rowNumber}: Module order {$moduleOrder} not found"
                        );
                    }

                    ModuleCompletion::updateOrCreate(
                        [
                            'registration_id' => $registration->id,
                            'module_id'       => $module->id,
                        ],
                        [
                            'completed_at' => now(),
                        ]
                    );
                }
            }
        });
    }
}