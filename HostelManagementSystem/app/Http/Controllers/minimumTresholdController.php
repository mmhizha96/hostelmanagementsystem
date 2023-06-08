<?php

namespace App\Http\Controllers;

use App\Models\minimumTreshold;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;

class minimumTresholdController extends Controller
{



    public function store(Request $request)
    {
        $request->validate(['set_by'=>'required', 'minimum_threshhold_id'=>'required', 'active_period_id'=>'required', 'minimum_threshhold'=>'required']);

      try{
         $minimumtreshhold=minimumTreshold::create($request->array());

         $minimumtreshhold=minimumTreshold::join('tbl_active_period_hostel_online_application','tbl_active_period_hostel_online_application.active_period_id','tbl_minimum_threshhold.active_period_id')
->where('active_period_id',$request['active_period_id']);
return response()->json(['success'=>true,'message'=>'succesfully set minimum treshhold','minimumteshold'=>$minimumtreshhold],200);
        }catch(QueryException $ex){
        return response()->json(['success'=>false,'message'=>$ex->getMessage()],200);
      }
    }

    public function show(Request $request)
    {
        $request->validate([ 'active_period_id'=>'required']);

        try{
            $minimumtreshhold=minimumTreshold::join('tbl_active_period_hostel_online_application','tbl_active_period_hostel_online_application.active_period_id','tbl_minimum_threshhold.active_period_id')
            ->where('active_period_id',$request['active_period_id']);

            return response()->json($minimumtreshhold,200);
        }catch(QueryException $ex){
          return response()->json(['success'=>false,'message'=>$ex->getMessage()],200);
        }

    }


    public function update(Request $request)
    {
        $request->validate(['set_by'=>'required', 'minimum_threshhold_id'=>'required', 'active_period_id'=>'required', 'minimum_threshhold'=>'required']);



        try{
            $minimumtreshhold=minimumTreshold::find($request['minimum_threshhold_id']);
$minimumtreshhold->minimum_threshhold=$request['minimum_threshhold'];
$minimumtreshhold->set_by=$request['set_by'];
$minimumtreshhold->update();
return response()->json(['success'=>true,'message'=>'succesfully set minimum treshhold','minimumteshold'=>$minimumtreshhold],200);
        }catch(QueryException $ex){
          return response()->json(['success'=>false,'message'=>$ex->getMessage()],200);
        }
    }


}
