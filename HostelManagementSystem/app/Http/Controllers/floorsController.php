<?php

namespace App\Http\Controllers;

use App\Models\floor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\QueryException;

class floorsController extends Controller
{
public function deleteFloor(Request $request)
{

    $request->validate(['floor_id' => 'required']);

$floor_id = $request['floor_id'];
$hostel_id = $request['hostel_id'];
try {
    $floor = floor::find($floor_id);

    $floor->delete();
    $floors = floor::where('hostel_id', $hostel_id)->get();

    return response()->json(['floors' => $floors, 'message' => 'successfully removed floor', 'success' => true], 201);

} catch (QueryException $ex) {
    if ($ex->errorInfo[1] == 1451) {
        return response()->json(['success' => false, 'message' => 'floor cannot be deleted, other parts of the system are dependent on this floor'], 501);
    } else {
        return response()->json(['error' => $ex->getMessage(), 'success' => false], 500);
    }
}

}

    //
    public function createFloor(Request $request)
    {
        $floor = new floor();
        try {
            $floor->floor_name = $request->input('floor_name');
            $floor->hostel_id = $request->input('hostel_id');
            $floor->save();
            $hostel_id = $request['hostel_id'];
            $floor = floor::where('hostel_id', $hostel_id)->get();

            return response()->json(['message' => 'success created floor', 'success' => false, 'floors' => $floor], 200);
        } catch (QueryException $ex) {
            if ($ex->errorInfo[1] == 1062) {
                return response()->json(['message' => 'floor already exist', 'success' => false], 500);
            } elseif ($ex->errorInfo[1] == 1452) {
                return response()->json(['message' => 'hostel not found ', 'success' => false], 502);
            } else {
                return response()->json(['message' => $ex->getMessage(), 'success' => false], 501);
            }
        }

        return response()->json(['message' => 'success created floor', 'success' => false], 200);
    }

    public function getBatchFloors(Request $request){
        $request->validate(["hostel_id"=>"required","residence_session_id"=>"required"]);
        $hostel_id = $request['hostel_id'];
        $residence_session_id = $request['residence_session_id'];
        $floor = DB::select("SELECT
        *
    FROM
        tbl_floors
    WHERE
        floor_id NOT IN ((
            SELECT
                tbl_floors.floor_id
            FROM
                tbl_floors
                INNER JOIN tbl_hostel_preference ON tbl_floors.floor_id = tbl_hostel_preference.floor_id
            WHERE
                tbl_hostel_preference.residence_session_id = $residence_session_id
            ))
        AND hostel_id = $hostel_id");

        return response()->json($floor, 200);

    }

    public function getFloors(Request $request)
    {
        $request->validate(["hostel_id"=>"required"]);
        $hostel_id = $request['hostel_id'];


        $floor=floor::where("hostel_id",$hostel_id)->get();

        return response()->json($floor, 200);
    }
}
