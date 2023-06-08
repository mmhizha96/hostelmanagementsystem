<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class roompreference extends Model
{
    use HasFactory;

    public $timestamps=false;
    protected $primaryKey='room_preference_id';
    protected $table='tbl_room_preference';
    protected $fillable=['room_preference_id','room_id','hostel_id','active_period_id','activated_by','date_activated','residence_session_id'];
}
