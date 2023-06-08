<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class roomstatus extends Model
{
    use HasFactory;
    public $timestamps=false;
    protected $table ='tbl_room_status';
    protected $primaryKey='room_status_id';
    protected $fillable=['room_status_id','room_status', 'room_id','period_id','residence_session_id'];
}
