<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class roomtype extends Model
{
    use HasFactory;
    public $timestamps =false;
    protected $primaryKey='room_type_id';
    protected $table='tbl_room_types';
    protected $fillable=['room_type_id','room_type','room_capacity'];
}
