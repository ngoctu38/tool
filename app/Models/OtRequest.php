<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OtRequest extends Model
{
    use HasFactory;
    protected $table = 'ot_requests';

    const TYPE_WORKING_DAY = 1;
    const TYPE_WEEKEND = 2;
    const TYPE_HOLIDAY = 3;

    const STATUS_REQUESTED = 1;
    const STATUS_APPROVED = 2;
    const STATUS_REJECTED = 3;

    protected $fillable = [
        'batch_id',
        'date',
        'day_type',
        'employee_id',
        'project_id',
        'account',
        'project_code',
        'entry_time',
        'leave_time',
        'ot_time',
        'on_time',
        'ot_reason',
        'requested_by_id',
        'approved_by_id',
        'rejected_by_id',
        'status',
        'updated_at',
    ];

    public static function getSttLabel($stt) {
        switch ($stt) {
            case self::STATUS_REQUESTED:
                return __('Requested');
            case self::STATUS_APPROVED:
                return __('Approved');
            case self::STATUS_REJECTED:
                return __('Rejected');
            default:
                return 'N/A';
        }
    }

    public static function getStatusOptions()
    {
        return [
          self::STATUS_REQUESTED => 'Requested',
          self::STATUS_APPROVED => 'Approved',
          self::STATUS_REJECTED => 'Rejected',
        ];
    }
    public static function getWorkingDayOptions()
    {
        return [
            self::TYPE_WORKING_DAY => 'Working day',
            self::TYPE_WEEKEND => 'Weekend',
            self::TYPE_HOLIDAY => 'Holiday',
        ];
    }

    public static function getWorkingDayLabel($value) {
        switch ($value) {
            case self::TYPE_WORKING_DAY:
                return __('Working day');
            case self::TYPE_WEEKEND:
                return __('Weekend');
            case self::TYPE_HOLIDAY:
                return __('Holiday');
            default:
                return 'N/A';
        }
    }
}
