<?php

namespace App\Http\Controllers;

use App\Models\room;
use Illuminate\Http\Request;
use App\Models\roompreference;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\QueryException;

class roomPreferenceController extends Controller
{
    //

    public function createRoomPreference(Request $request)
    {
        $request->validate(['hostel_id' => 'required', 'room_id' => 'required', 'active_period_id' => 'required', 'activated_by' => 'required', 'residence_session_id' => 'required']);

        $roompreference = new roompreference();
        try {
            $roompreference->room_id = $request['room_id'];
            $roompreference->hostel_id = $request['hostel_id'];
            $roompreference->active_period_id = $request['active_period_id'];
            $roompreference->activated_by = $request['activated_by'];

            $roompreference->residence_session_id = $request['residence_session_id'];
            $roompreference->save();
        } catch (QueryException $ex) {
            if ($ex->errorInfo[1] == 1062) {
                return response()->json(['message' => 'room preference already exist ', 'success' => false], 500);
            } else {
                return response()->json(['message' => $ex->getMessage(), 'success' => false], 501);
            }
        }

        return response()->json(['message' => 'successfully set room preference ', 'success' => true], 200);
    }

    public function deleteRoomPreference(Request $request)
    {
        $room_preference_id = $request['room_preference_id'];

        $roompreference = roompreference::find($room_preference_id);
        $roompreference->delete();

        return response()->json(['message' => 'successfully removed room ', 'success' => true], 200);
    }


    public function getRoomPreference(Request $request)
    {
        $residence_session_id = $request['residence_session_id'];
        $roompreference = DB::select("SELECT
        *,
        tbl_hostels.hostel_name,
        tbl_rooms.room_number,
        tbl_rooms.hostel_id,
        tbl_room_preference.room_preference_id,
        tbl_rooms.room_id,
        tbl_floors.floor_name
    FROM
        tbl_room_preference
        INNER JOIN tbl_rooms ON tbl_room_preference.room_id = tbl_rooms.room_id
        INNER JOIN tbl_hostels ON tbl_room_preference.hostel_id = tbl_hostels.hostel_id
        AND tbl_rooms.hostel_id = tbl_hostels.hostel_id
        INNER JOIN tbl_floors ON tbl_rooms.floor_id = tbl_floors.floor_id
    WHERE
        tbl_room_preference.residence_session_id = $residence_session_id");

        return response()->json($roompreference, 200);
    }
    public function getRoomsToAdd(Request $request)
    {
        $sessionid = $request['residence_session_id'];
        $roomstoadd = DB::select("SELECT
        tbl_rooms.room_number,
        tbl_rooms.room_capacity,
        tbl_hostels.hostel_name,
        tbl_floors.floor_name,
        tbl_locations.location_name,
        tbl_rooms.room_id,
        tbl_rooms.hostel_id
    FROM
        tbl_rooms
        LEFT JOIN
        tbl_room_preference
        ON
            tbl_rooms.room_id = tbl_room_preference.room_id
        INNER JOIN
        tbl_hostels
        ON
            tbl_rooms.hostel_id = tbl_hostels.hostel_id
        INNER JOIN
        tbl_floors
        ON
            tbl_rooms.floor_id = tbl_floors.floor_id
        INNER JOIN
        tbl_locations
        ON
            tbl_hostels.location_id = tbl_locations.location_id
    WHERE
        tbl_room_preference.room_preference_id IS NULL OR
        tbl_room_preference.residence_session_id <> $sessionid");



return response()->json($roomstoadd,200);
    }
}
