<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class active_period_hostel_online_application extends Model
{
    use HasFactory;
    public $timestamps=false;
    protected $table='tbl_active_period_hostel_online_application';
    protected $primaryKey='active_period_id';
    protected $fillable=['active_period_id','period_id','is_active','date_activated','activated_by'];

}
