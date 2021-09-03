<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OtBatch extends Model
{
    use HasFactory;
    protected $table = 'ot_batch';

    protected $fillable = [
        'note',
        'date',
        'project_id',
        'project_code',
        'requested_by_id',
        'approved_by_id',
        'rejected_by_id',
        'status',
        'updated_at',
    ];
}
