<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class period extends Model
{
    use HasFactory;

    protected $connection = 'mysql_2';

    public $timestamps = false;

    protected $table = 'tblperiod';

    protected $fillable = ['period_id', 'period_name', 'period_start_date', 'period_end_date','period','active'];
}
