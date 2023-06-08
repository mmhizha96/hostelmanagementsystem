<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class location extends Model
{
    use HasFactory;
public $timestamps=false;
    protected $table ='tbl_locations';
    protected $fillable =['location_id', 'location_name'];

}
