<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class room extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $table = 'tbl_rooms';

    protected $primaryKey = 'room_id';

     protected $fillable = ['room_id', 'room_type_id', 'room_capacity',
         'room_number', 'floor_id', 'hostel_id', 'room_gender'];
}
