<?php

namespace App\Exports;

use App\Models\AppModelsEmployee;
use App\Models\Employee;
use Maatwebsite\Excel\Concerns\FromCollection;

class EmployeesExport implements FromCollection
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return Employee::with('department', 'jobTitle')->get()->map(
            function ($employee) {
                return [
                    'id' => $employee->id, 
                    'name' => $employee->name,
                    'registration_number' => $employee->registration_number,
                    'job_title' => $employee->jobTitle->name,
                    'department' => $employee->department->name,
                    'link' => $employee->link,
                    'created_at' => $employee->created_at,
                    'updated_at' => $employee->updated_at,
                ];
            }
        );
    }

    public function headings(): array
    {
        return [
            'ID', 
            'Name', 
            'Registration Number', 
            'Job Title', 
            'Department', 
            'Link', 
            'Created At', 
            'Updated At',
        ];
    }
}
