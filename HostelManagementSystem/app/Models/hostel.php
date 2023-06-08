<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class hostel extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $table ='tbl_hostels';

    protected $fillable=['hostel_id','hostel_name','location_id'];

}
