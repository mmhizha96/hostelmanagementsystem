<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class resavation extends Model
{
    use HasFactory;
    protected $primaryKey='resavation_id';
    protected $table ='tbl_resevations';
    protected $fillable=['resavation_id','room_id','room_number','residence_session_id','created_at','updated_at','reserved_by'];
}
