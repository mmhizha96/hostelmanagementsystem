<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class residenceSession extends Model
{
    use HasFactory;

protected $table='tbl_residence_sessions';
public $timestamps=false;
protected $primaryKey='residence_session_id';
protected $fillable=['session_name','residence_session_id','is_program_driven',
'active_period_id','level','semester','start_date','end_date','available_status'];

}
