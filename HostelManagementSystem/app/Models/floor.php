<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class floor extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $primaryKey = 'floor_id';

    protected $table = 'tbl_floors';

    protected $fillable = ['floor_id', 'floor_name', 'hostel_id'];
}
