<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class roomTypeCost extends Model
{
    use HasFactory;
    public $timestamps=false;

    protected $primaryKey='room_type_cost_id';
    Protected $table='tbl_room_type_costs';
    protected $fillable =['room_type_cost_id','room_type_id','active_period_id','room_price','set_by','date_set'];
}
