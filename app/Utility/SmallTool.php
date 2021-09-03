<?php


namespace App\Utility;


use App\Models\OtRequest;
use Carbon\Carbon;
use PhpOffice\PhpSpreadsheet\Shared\Date;

class SmallTool
{
    /**
     * @param $value
     * @param string $format
     * @return string|null
     */
    public static function convertToDate($value, $format = 'Y-m-d')
    {
        if (!$value) {
            return null;
        }
        if (is_int($value)) {
            try {
               $date = Date::excelToDateTimeObject($value);
               return $date->format($format);
            } catch (\Exception $e) {
                return null;
            }
        }
        $date = new Carbon(strtotime($value));
        return $date->format($format);
    }

    public static function getDayType($value) {
        switch (strtolower($value)) {
            case "holiday":
                return OtRequest::TYPE_HOLIDAY;
            case "weekend":
                return OtRequest::TYPE_WEEKEND;
            default:
                return OtRequest::TYPE_WORKING_DAY;
        }
    }
}
