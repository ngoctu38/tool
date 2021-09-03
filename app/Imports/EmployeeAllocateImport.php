<?php

namespace App\Imports;

use App\Models\Department;
use App\Models\Employee;
use App\Models\EmployeeAllocate;
use App\Models\Project;
use App\Utility\SmallTool;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class EmployeeAllocateImport implements ToCollection, WithHeadingRow, WithValidation
{
    /**
    * @param Collection $collection
    */
    public function collection(Collection $collection)
    {
        //
        $projectCodeArray = Project::query()->where('status', 'on-going')
            ->get()->pluck('id', 'code')->toArray();
        $departList = Department::all()->pluck('id', 'name');

        foreach ($collection as $row) {
            $fromDate = SmallTool::convertToDate($row['from_date']);
            $toDate = SmallTool::convertToDate($row['to_date']);
            $departmentId = isset($row['project_group'])
                && isset($departList[$row['project_group']]) ? $departList[$row['project_group']] : null;

            if (!isset($projectCodeArray[$row['project_code']])) {
                // Insert new project
                $pr = new Project();
                $pr->fill([
                    'title' => $row['project_code'],
                    'code' => $row['project_code'],
                    'customer_code' => $row['customer_code'],
                    'from_date' => $fromDate,
                    'to_date' => $toDate,
                    'department_id' => $departmentId,
                    'status' => 'on-going',
                ]);
                $pr->save();
                $projectCodeArray[$pr->code] = $pr->id;
                unset($pr);
            }
            $employee = Employee::query()->where('email',
                strtolower($row['username']).'@fsoft.com.vn')->first();
            if (!$employee) {
                continue;
            }
            $data = [
                'project_id' => $projectCodeArray[$row['project_code']],
                'employee_id' => $employee->id,
                'job' => $row['job'],
                'from_date' => $fromDate,
                'to_date' => $toDate,
                'hours' => $row['hours'],
                'calendar_effort' => $row['calendar_effort'],
                'billable' => $row['billable'] == 'YES' ? 1 : 0,
                'note' => $row['note'],
            ];
            $item = new EmployeeAllocate();
            $item->fill($data);
            $item->save();
            unset($item, $employee);
        }
    }

    public function rules(): array
    {
        // TODO: Implement rules() method.
        return [
            'project_code' => 'required',
            'project_status' => 'required',
            'username' => 'required',
            'from_date' => 'required',
            'to_date' => 'required',
            'hours' => 'required',
            'billable' => 'required',
            'calendar_effort' => 'required',
//            'user_group',
//            'note',
        ];
    }
}
