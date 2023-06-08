<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;

class student extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $connection = 'mysql_2';

    public $timestamps = false;

    protected $table = 'tblstudent';

    protected $primaryKey = 'student_id';

    protected $fillable = ['student_id',
        'reg_number',
        'first_name',
        'surname',
        'previous_surname'];
}
