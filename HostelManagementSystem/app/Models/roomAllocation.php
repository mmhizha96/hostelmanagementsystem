<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class roomAllocation extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $primaryKey = 'room_allocation_id';

    protected $table = 'tbl_room_allocations';

    protected $fillable = ['active_period_id', 'room_allocation_id', 'room_id', 'application_id',
        'approved_status', 'approved_by', 'student_id', 'date_allocated', 'allocated_by', 'residence_session_id', 'reg_number'];
}
