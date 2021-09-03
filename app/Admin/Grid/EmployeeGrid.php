<?php


namespace App\Admin\Grid;


use Encore\Admin\Grid;

class EmployeeGrid extends Grid
{
    protected $departmentList;

    public function setDepartmentList($value)
    {
        $this->departmentList = $value;
    }
    public function applyFilter($toArray = true)
    {
        $collection = parent::applyFilter($toArray);
        foreach ($collection as $item) {
            if (isset($this->departmentList[$item->department_id])) {
                $item->department_name = $this->departmentList[$item->department_id];
            }
            if (isset($this->departmentList[$item->sub_department_id])) {
                $item->sub_department_name = $this->departmentList[$item->sub_department_id];
            }
        }
        return $collection;
    }
}
