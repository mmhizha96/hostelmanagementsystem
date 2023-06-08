<?php

namespace App\Http\Controllers;

use App\Models\year;
use Exception;
use Illuminate\Http\Request;

class yearController extends Controller
{
    //
    function getAcademicyear(){
        try{
        $years=year::all();
            return response()->json($years);
        }catch(Exception $ex)
        {
            return response()->json(["error"=>$ex->getMessage()]);
        }

    }
}
