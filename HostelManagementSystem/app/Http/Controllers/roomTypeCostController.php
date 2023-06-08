<?php

namespace App\Http\Controllers;

use App\Models\roomTypeCost;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class roomTypeCostController extends Controller
{
    public function getRoomTypeCost(Request $request)
    {

     $data=   $request->validate(['active_period_id'=>'required']);
     $roomtypecosts=null;

     $active_period_id=$data['active_period_id'];
if( $active_period_id=="max"){
    $roomtypecosts = DB::select('SELECT
    hostelmanagement.tbl_room_types.room_type,
    hostelmanagement.tbl_room_type_costs.room_type_cost_id,
    hostelmanagement.tbl_room_type_costs.room_price,
    hostelmanagement.tbl_room_type_costs.date_set,
    registry.tblperiod.period_name
FROM
    hostelmanagement.tbl_room_types
    LEFT JOIN hostelmanagement.tbl_room_type_costs ON tbl_room_types.room_type_id = tbl_room_type_costs.room_type_id
    INNER JOIN hostelmanagement.tbl_active_period_hostel_online_application ON tbl_room_type_costs.active_period_id = tbl_active_period_hostel_online_application.active_period_id
    INNER JOIN registry.tblperiod ON hostelmanagement.tbl_active_period_hostel_online_application.period_id = registry.tblperiod.period_id
WHERE
    tbl_room_type_costs.active_period_id = (
    SELECT
        max( active_period_id )
FROM
    tbl_active_period_hostel_online_application)');
}else{
    $roomtypecosts = DB::select("SELECT
    hostelmanagement.tbl_room_types.room_type,
    hostelmanagement.tbl_room_type_costs.room_type_cost_id,
    hostelmanagement.tbl_room_type_costs.room_price,
    hostelmanagement.tbl_room_type_costs.date_set,
    registry.tblperiod.period_name
FROM
    hostelmanagement.tbl_room_types
    LEFT JOIN hostelmanagement.tbl_room_type_costs ON tbl_room_types.room_type_id = tbl_room_type_costs.room_type_id
    INNER JOIN hostelmanagement.tbl_active_period_hostel_online_application ON tbl_room_type_costs.active_period_id = tbl_active_period_hostel_online_application.active_period_id
    INNER JOIN registry.tblperiod ON hostelmanagement.tbl_active_period_hostel_online_application.period_id = registry.tblperiod.period_id
WHERE
    tbl_room_type_costs.active_period_id =  $active_period_id");


}



        return response()->json($roomtypecosts);
    }

    //
    public function setRoomTypeCost(Request $request)
    {
        $request->validate(['room_type_id' => 'required', 'active_period_id' => 'required', 'room_price' => 'required', 'set_by' => 'required']);
        $room_type_id = $request['room_type_id'];
        $active_period_id = $request['active_period_id'];
        try {
            $matchConditions = ['room_type_id' => $room_type_id, 'active_period_id' => $active_period_id];
            $roomtypecost = roomTypeCost::where($matchConditions)->first();
            if (! $roomtypecost) {
                $roomtypecost = new roomTypeCost();
                $roomtypecost->room_type_id = $request['room_type_id'];
                $roomtypecost->active_period_id = $request['active_period_id'];
                $roomtypecost->room_price = $request['room_price'];
                $roomtypecost->set_by = $request['set_by'];
                $roomtypecost->save();

            } else {
                $roomtypecost->room_type_id = $request['room_type_id'];
                $roomtypecost->active_period_id = $request['active_period_id'];
                $roomtypecost->room_price = $request['room_price'];
                $roomtypecost->set_by = $request['set_by'];
                $roomtypecost->update();
            }
            $roomtypecosts = DB::select("SELECT
            hostelmanagement.tbl_room_types.room_type,
            hostelmanagement.tbl_room_type_costs.room_type_cost_id,
            hostelmanagement.tbl_room_type_costs.room_price,
            hostelmanagement.tbl_room_type_costs.date_set,
            registry.tblperiod.period_name
        FROM
            hostelmanagement.tbl_room_types
            LEFT JOIN hostelmanagement.tbl_room_type_costs ON tbl_room_types.room_type_id = tbl_room_type_costs.room_type_id
            INNER JOIN hostelmanagement.tbl_active_period_hostel_online_application ON tbl_room_type_costs.active_period_id = tbl_active_period_hostel_online_application.active_period_id
            INNER JOIN registry.tblperiod ON hostelmanagement.tbl_active_period_hostel_online_application.period_id = registry.tblperiod.period_id
        WHERE
            tbl_room_type_costs.active_period_id =  $active_period_id");


return response()->json(['message' => 'succesfully successfully set room costs','roomcost'=>$roomtypecosts, 'success' => true], 200);
        } catch (QueryException $ex) {
            if ($ex->errorInfo[1] == 1062) {
                return response()->json(['success' => false, 'message' => 'room price already set kindly update'], 500);

            } else {
                return response()->json(['message' => $ex->getMessage(), 'success' => false], 501);
            }

        }

        return response()->json(['message' => 'succesfully successfully set room costs', 'success' => true], 200);
    }
}
