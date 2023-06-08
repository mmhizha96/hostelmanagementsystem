<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class roomapplication extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $table = 'tbl_room_allocation_applications';

    protected $primaryKey = 'room_allocation_application_id';

    protected $fillable = ['room_allocation_application_id', 'room_id', 'student_id', 'applied_by', 'date_of_application', 'reg_number', 'payment_status', 'expiration_date', 'residence_session_id', 'active_period_id'];
}
