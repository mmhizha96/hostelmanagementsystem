<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class minimumTreshold extends Model
{
    use HasFactory;

    protected $connection = 'mysql_2';

    protected $table = 'tblminimum_fees';

    protected $primaryKey = 'minimum_fees_id';

    public $timeStamps = false;

    protected $fillable = ['set_by', 'minimum_fees_id', 'period_id', 'minimum_threshhold'];
}
