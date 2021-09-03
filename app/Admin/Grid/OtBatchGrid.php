<?php


namespace App\Admin\Grid;


use App\Models\Admin;
use App\Models\Employee;
use App\Models\Project;
use Encore\Admin\Grid;

class OtBatchGrid extends Grid
{
    public function applyFilter($toArray = true)
    {
        $collection = parent::applyFilter($toArray);
        // @todo add more info
        $adminIds = [];
        foreach ($collection as $item) {
            $adminIds[$item->requested_by_id] = $item->requested_by_id;
            if ($item->approved_by_id) {
                $adminIds[$item->approved_by_id] = $item->approved_by_id;
            }
            if ($item->rejected_by_id) {
                $adminIds[$item->rejected_by_id] = $item->rejected_by_id;
            }
        }
        $adminArray = [];
        if (count($adminIds)) {
            $adminArray = Admin::query()->whereIn('id', $adminIds)
                ->get()->pluck('username', 'id')->toArray();
        }
        foreach ($collection as $item) {
            if (isset($adminArray[$item->requested_by_id])) {
                $item->requested_by = $adminArray[$item->requested_by_id];
            }
            if ($item->approved_by_id && isset($adminArray[$item->approved_by_id])) {
                $item->approved_by = $adminArray[$item->approved_by_id];
            } else {
                $item->approved_by = 'N/A';
            }
            if ($item->rejected_by_id && isset($adminArray[$item->rejected_by_id])) {
                $item->rejected_by = $adminArray[$item->rejected_by_id];
            } else {
                $item->rejected_by = 'N/A';
            }
        }

        return $collection;
    }
}
