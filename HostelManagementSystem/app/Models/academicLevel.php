<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class academicLevel extends Model
{
    use HasFactory;
    public $timestamps=false;
    protected $table='tbl_academic_levels';
    protected $fillable=['level_id','year_id','semester'];
}
