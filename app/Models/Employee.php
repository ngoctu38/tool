<?php


namespace App\Models;


class Employee extends BaseModel
{
    const GENDER_MALE = 1;
    const GENDER_FEMALE = 0;

    const STATUS_ACTIVE = 1;
    const STATUS_INACTIVE = 0;

    const CONTRACT_TYPE_FULL = 1;
    const CONTRACT_TYPE_OJT = 2;
    const CONTRACT_TYPE_STUDENT = 3;


    protected $fillable = [
        'email',
        'full_name',
        'current_team',
        'job_code',
        'job_level',
        'contract_type',
        'contract_from',
        'contract_to',
        'dob',
        'gender',
        'status',
        'empl_code',
        'department_id',
        'sub_department_id',
    ];
}
