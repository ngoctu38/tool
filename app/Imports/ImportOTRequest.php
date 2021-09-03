<?php

namespace App\Imports;

use App\Models\Employee;
use App\Models\OtBatch;
use App\Models\OtRequest;
use App\Utility\SmallTool;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class ImportOTRequest implements ToCollection, WithValidation, WithHeadingRow
{
    protected $batchData;
    public function __construct($batchData)
    {
        $this->batchData = $batchData;
    }

    /**
     * @param Collection $collection
     * @throws \Exception
     */
    public function collection(Collection $collection)
    {
        //
        try {
            DB::beginTransaction();
            $batch = new OtBatch();
            $batch->fill($this->batchData);
            $batch->save();
            foreach ($collection as $row) {
                $item = new OtRequest();
                $empl = Employee::query()->where('email', $row['username']
                    .'@fsoft.com.vn')->first();
                if ($empl) {
                    $item->fill([
                        'batch_id' => $batch->id,
                        'date' => SmallTool::convertToDate($row['date']),
                        'day_type' => SmallTool::getDayType($row['day_type']),
                        'employee_id' => $empl->id,
                        'project_id' => $batch->project_id,
                        'project_code' => $this->batchData['project_code'],
                        'account' => $row['username'],
//                        'project_code' => $row['day_type'],
//                    'entry_time' => $row['date_type'],
//                    'leave_time' => $row['date_type'],
                        'ot_time' => $row['ot_time'],
                        'on_time' => $row['on_time'],
                        'ot_reason' => $row['ot_reason'],
                        'requested_by_id' => $this->batchData['requested_by_id'],
                    ]);
                    $item->save();
                }

            }
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function rules(): array
    {
        // TODO: Implement rules() method.
        return [
            'username' => 'required',
            'date' => 'required',
            'day_type' => 'required',
            'ot_time' => 'required',
            'on_time' => 'required',
            'ot_reason' => 'nullable',
        ];
    }
}
