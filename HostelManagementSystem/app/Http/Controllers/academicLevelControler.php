<?php

namespace App\Http\Controllers;

use App\Models\academicLevel;
use Exception;
use Illuminate\Http\Request;

class academicLevelControler extends Controller
{
    //

    function getAcademicLevel(){
        try{
            $academiclevel=academicLevel::join('tbl_academic_years','tbl_academic_years.year_id','=','tbl_academic_levels.year_id')->get();
            return response()->json($academiclevel);
        }catch(Exception $ex)
        {
            return response()->json(["error"=>$ex->getMessage()]);
        }

    }
}
