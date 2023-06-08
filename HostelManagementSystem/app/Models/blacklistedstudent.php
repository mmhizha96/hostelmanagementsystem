<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class blacklistedstudent extends Model
{
    use HasFactory;
   protected $primaryKey='blacklisted_students_id';
   protected $table='tbl_blacklisted_students';
   protected $fillable=['blacklisted_students_id','reason','student_regnumber','blacklisted_by','created_at','updated_at'];
}
