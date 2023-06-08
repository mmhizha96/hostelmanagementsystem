<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class userright extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $primaryKey = 'user_right_id';

    protected $table = 'tbl_user_rights';

    protected $fillable = ['user_right_id', 'user_id', 'rights', 'assigned_by'];
}
