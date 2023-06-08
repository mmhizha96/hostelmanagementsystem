<?php

namespace App\Http\Controllers;

use App\Models\resavation;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class resavationsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $resevations = DB::select('SELECT
	tbl_locations.location_name,
	tbl_hostels.hostel_name,
	tbl_floors.floor_name,
	tbl_rooms.room_type_id,
	tbl_rooms.room_gender,
	tbl_rooms.room_capacity,
	tbl_rooms.room_number,
	tbl_resevations.resavation_id,
	tbl_resevations.room_id,
	tbl_residence_sessions.session_name
FROM
	tbl_resevations
	INNER JOIN tbl_rooms ON tbl_resevations.room_id = tbl_rooms.room_id
	INNER JOIN tbl_floors ON tbl_rooms.floor_id = tbl_floors.floor_id
	INNER JOIN tbl_hostels ON tbl_floors.hostel_id = tbl_hostels.hostel_id
	INNER JOIN tbl_locations ON tbl_hostels.location_id = tbl_locations.location_id
	INNER JOIN tbl_residence_sessions ON tbl_resevations.residence_session_id = tbl_residence_sessions.residence_session_id
ORDER BY
	tbl_resevations.resavation_id DESC');

            return response()->json($resevations, 200);
        } catch (QueryException $ex) {
            return response()->json(['message' => $ex->getMessage(), 'success' => false], 501);
        }

    }

    public function findResevation(Request $request)
    {
$data = $request->validate(['residence_session_id' => 'required']);

$residence_session_id = $data['residence_session_id'];

 try {
    $resavation = DB::select("SELECT
	tbl_locations.location_name,
	tbl_hostels.hostel_name,
	tbl_floors.floor_name,
	tbl_rooms.room_type_id,
	tbl_rooms.room_gender,
	tbl_rooms.room_capacity,
	tbl_rooms.room_number,
	tbl_resevations.resavation_id,
	tbl_resevations.room_id,
	tbl_residence_sessions.session_name
FROM
	tbl_resevations
	INNER JOIN tbl_rooms ON tbl_resevations.room_id = tbl_rooms.room_id
	INNER JOIN tbl_floors ON tbl_rooms.floor_id = tbl_floors.floor_id
	INNER JOIN tbl_hostels ON tbl_floors.hostel_id = tbl_hostels.hostel_id
	INNER JOIN tbl_locations ON tbl_hostels.location_id = tbl_locations.location_id
	INNER JOIN tbl_residence_sessions ON tbl_resevations.residence_session_id = tbl_residence_sessions.residence_session_id
WHERE
	tbl_resevations.residence_session_id = $residence_session_id
ORDER BY
	tbl_resevations.resavation_id DESC");

      return response()->json($resavation, 200);
 } catch (QueryException $ex) {
    return response()->json(['message' => $ex->getMessage(), 'success' => false], 501);
 }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate(['room_id', 'room_number',  'reserved_by','residence_session_id'=>'required']);

        try {
            $resevations = new resavation();
            $resevations->room_id = $request['room_id'];
            $resevations->residence_session_id = $request['residence_session_id'];
            $resevations->reserved_by = $request['reserved_by'];
            $resevations->room_number = $request['room_number'];
            $resevations->save();

            return response()->json(['message' => 'room succesfully reserved', 'success' => true], 200);

        } catch (QueryException $ex) {
            if ($ex->errorInfo[1] == 1062) {
                return response()->json(['message' => 'room  already reserved', 'success' => false], 500);
            } else {
                return response()->json(['message' => $ex->getMessage(), 'success' => false], 501);
            }

    }
    }

    public function unReserve(Request $request)
    {
        $request->validate(['resavation_id']);
        $resavation_id = $request['resavation_id'];
        try {
        $resevations = resavation::find($resavation_id);
        $resevations->delete();


        return response()->json(['message' => 'room succesfully reserved', 'success' => true], 201);
        } catch (QueryException $ex) {

                return response()->json(['message' => $ex->getMessage(), 'success' => false], 501);

    }
    }

    public function getToomsToreserve(Request $request)
    {
    $data = $request->validate(['residence_session_id' => 'required']);

$residence_session_id = $data['residence_session_id'];
    try {
        $result = DB::select(
            "SELECT
            tbl_rooms.room_id,
            tbl_rooms.room_number,
            tbl_rooms.room_gender,
            tbl_rooms.room_capacity,
            tbl_floors.floor_name,
            tbl_hostels.hostel_name,
            tbl_locations.location_name
        FROM
            tbl_rooms
            INNER JOIN tbl_floors ON tbl_rooms.floor_id = tbl_floors.floor_id
            INNER JOIN tbl_hostels ON tbl_floors.hostel_id = tbl_hostels.hostel_id
            INNER JOIN tbl_locations ON tbl_hostels.location_id = tbl_locations.location_id
        WHERE
            tbl_rooms.room_id NOT IN ((
                SELECT
                    tbl_resevations.room_id
                FROM
                    tbl_resevations
                WHERE
                tbl_resevations.residence_session_id =$residence_session_id
            ))"
        );

        return response()->json($result, 200);
    } catch (QueryException $ex) {
        return response()->json(['message' => $ex->getMessage(), 'success' => false], 501);

    }

    }
}
