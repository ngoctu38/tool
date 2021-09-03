<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmployeeAllocate extends Model
{
    use HasFactory;
    protected $table = 'employee_allocations';

    protected $fillable = [
        'project_id',
        'employee_id',
        'job',
        'from_date',
        'to_date',
        'hours',
        'calendar_effort',
        'billable',
        'note',
    ];
}
