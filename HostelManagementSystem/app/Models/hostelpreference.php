<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class hostelpreference extends Model
{
    use HasFactory;

    public $timestamps = false;

  protected $primaryKey = 'hostel_preference_id';

  protected $table = 'tbl_hostel_preference';

  protected $fillable = ['hostel_preference_id', 'hostel_id','active_period_id', 'activated_by','floor_id', 'date_activated', 'residence_session_id'];
}
