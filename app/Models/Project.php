<?php

namespace App\Models;

//use Encore\Admin\Auth\Database\Administrator;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Project extends BaseModel
{
    use HasFactory;

    protected $casts = [
//        'from_date' => 'date',
//        'to_date' => 'date',
    ];

    protected $fillable = [
        'department_id',
        'title',
        'code',
        'owner_id',
        'pm_id',
        'from_date',
        'to_date',
        'status',
        'rate',
        'description',
    ];

    public function owner() {
        $this->hasOne(Admin::class,
            'owner_id', 'id');
    }

    public static function getStatusOptions() {
        return [
            'bidding' => __('Bidding'),
            'on-going' => __('On-Going'),
            'finished' => __('Finished'),
            'canceled' => __('Canceled'),
        ];
    }


}
