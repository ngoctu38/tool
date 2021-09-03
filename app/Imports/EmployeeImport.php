<?php

namespace App\Imports;

use App\Models\Department;
use App\Models\Employee;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use Illuminate\Support\Collection;

class EmployeeImport implements ToModel, WithValidation, WithHeadingRow
{
    use Importable;

    protected $departList;

    public function __construct()
    {
        $this->departList = Department::all()->pluck('id', 'name');
    }

    public function rules(): array
    {
        // TODO: Implement rules() method.
        return [
            'business' => 'required',
            'empl_code' => 'required',
            'name' => 'required',
        ];
    }

    protected function convertExcelToDate($value) {
        $item = new Carbon(strtotime($value));
        return $item->format('Y-m-d');
    }

    public function collection(Collection $rows)
    {
        foreach ($rows as $row)
        {
            $item = Employee::query()
                ->where('email', strtolower($row['business']))->firstOrNew([]);

            $contractType = $row['empl_class'] == 'OFF' || $row['empl_class'] == 'PRO'?
                    Employee::CONTRACT_TYPE_FULL : (
                        $row['empl_class'] == 'APP' ? Employee::CONTRACT_TYPE_OJT
                            : Employee::CONTRACT_TYPE_STUDENT);
            $departId = isset($this->departList[strtoupper($row['child_department_1'])])?
                $this->departList[strtoupper($row['child_department_1'])] : null;
            $subDepartId = isset($this->departList[strtoupper($row['child_department_2'])])?
                $this->departList[strtoupper($row['child_department_2'])] : null;

            $item->fill([
                'email' => strtolower($row['business']),
                'full_name' => $row['name'],
                'empl_code' => $row['empl_code'],
                'department_id' => $departId,
                'sub_department_id' => $subDepartId,
                'contract_type' => $contractType,
                'contract_from' => $this->convertExcelToDate($row['contract_begin_date']),
                'contract_to' => !empty($row['contract_end_date']) ?
                    $this->convertExcelToDate($row['contract_end_date']): null,
                'dob' => !empty($row['date_of_birth']) ?
                    $this->convertExcelToDate($row['date_of_birth']) : null,
                'gender' => !empty($row['sex']) ?
                    ($row['sex'] == 'Male' ? Employee::GENDER_MALE : Employee::GENDER_FEMALE) : null,
                'status' => Employee::STATUS_ACTIVE,
            ]);
            $item->save();
        }
    }


    public function model(array $row)
    {
        // TODO: Implement model() method.
        $item = Employee::query()
            ->where('email', strtolower($row['business']))->firstOrNew([]);

        $contractType = $row['empl_class'] == 'OFF' || $row['empl_class'] == 'PRO'?
            Employee::CONTRACT_TYPE_FULL : (
            $row['empl_class'] == 'APP' ? Employee::CONTRACT_TYPE_OJT
                : Employee::CONTRACT_TYPE_STUDENT);
        $departId = isset($this->departList[strtoupper($row['child_department_1'])])?
            $this->departList[strtoupper($row['child_department_1'])] : null;
        $subDepartId = isset($this->departList[strtoupper($row['child_department_2'])])?
            $this->departList[strtoupper($row['child_department_2'])] : null;

        $item->fill([
            'email' => strtolower($row['business']),
            'full_name' => $row['name'],
            'empl_code' => $row['empl_code'],
            'department_id' => $departId,
            'sub_department_id' => $subDepartId,
            'contract_type' => $contractType,
            'contract_from' => $this->convertExcelToDate($row['contract_begin_date']),
            'contract_to' => !empty($row['contract_end_date']) ?
                $this->convertExcelToDate($row['contract_end_date']): null,
            'dob' => !empty($row['date_of_birth']) ?
                $this->convertExcelToDate($row['date_of_birth']) : null,
            'gender' => !empty($row['sex']) ?
                ($row['sex'] == 'Male' ? Employee::GENDER_MALE : Employee::GENDER_FEMALE) : null,
            'status' => Employee::STATUS_ACTIVE,
        ]);
        return $item;
    }
}
