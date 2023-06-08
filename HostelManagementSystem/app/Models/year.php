<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class year extends Model
{
    use HasFactory;
    public $timestamps=false;
    protected $table='tbl_academic_years';
    protected $fillable=['year_id','year'];


}
