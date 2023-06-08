<?php

namespace App\Http\Controllers;

use App\Models\room;
use App\Models\roomAllocation;
use App\Models\roomapplication;
use App\Models\student;
use Carbon\Carbon;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class roomAllocationController extends Controller
{
    public function store(Request $request)
    {
        $room_allocation = $request->validate([
            'active_period_id' => 'required', 'room_id' => 'required', 'reg_number' => 'required|string',
            'allocated_by' => 'required', 'residence_session_id' => 'required',
        ]);

        try {

            $regnumber = $request['reg_number'];
            $student = student::join('tblstudent_personal', 'tblstudent_personal.student_id', '=', 'tblstudent.student_id')->where('reg_number', $regnumber)->first();
            $room_allocation['student_id'] = $student->student_id;
            $room_allocation['approved_status'] = '0';

            $residenceSessionId = $request['residence_session_id'];
            $room_id = $request['room_id'];

            $room = room::find($room_id);
            $student_gender = '';
            if ($student->sex == 'MALE') {
                $student_gender = 'm';
            } elseif ($student->sex == 'FEMALE') {
                $student_gender = 'f';
            }

            if ($room->room_gender != $student_gender) {
                return response()->json(['success' => false, 'message' => 'room gender error'], 403);
            }

            $matchconditions = ['residence_session_id' => $residenceSessionId, 'room_id' => $room_id];
            $roomoccupants = roomAllocation::where($matchconditions)->where('approved_status', '!=', '2')->count();

            $applicants = roomapplication::where($matchconditions)->where('application_status', '=', '0')->count();

            $room_capacity = room::where('room_id', $room_id)->first();
            $room_capacity = $room_capacity->room_capacity;

            //   check if room is full occupied

            if (($roomoccupants + $applicants) >= $room_capacity) {
                return response()->json(['success' => false, 'message' => 'room is fully occupied '], 403);
            }

            roomAllocation::create($room_allocation);
            // check if capacity is still available
            $roomoccupants = roomAllocation::where($matchconditions)->where('approved_status', '!=', '2')->count();

            $applicants = roomapplication::where($matchconditions)->where('application_status', '!=', '2')->count();

            $room_capacity = room::where('room_id', $room_id)->first();
            $room_capacity = $room_capacity->room_capacity;
            $room_status['residence_session_id'] = $residenceSessionId;

            $room_status['room_id'] = $room_id;
            $room_status['active_period_id'] = $room_allocation['active_period_id'];

            if (($roomoccupants + $applicants) >= $room_capacity) {

                $room_status['room_status'] = '0';
            } else {
                $room_status['room_status'] = '1';
            }

            $result = app('App\Http\Controllers\roomstatusController')->updateRoomStatus($room_status);
            $residenceSessionId = $request['residence_session_id'];

            $roomallocations = DB::select("SELECT
            *
            FROM
            tbl_room_allocations
            INNER JOIN tbl_rooms ON tbl_room_allocations.room_id = tbl_rooms.room_id
            INNER JOIN tbl_floors ON tbl_rooms.floor_id = tbl_floors.floor_id
            INNER JOIN tbl_room_types ON tbl_rooms.room_type_id = tbl_room_types.room_type_id
            INNER JOIN tbl_hostels ON tbl_floors.hostel_id = tbl_hostels.hostel_id
            INNER JOIN tbl_locations ON tbl_hostels.location_id = tbl_locations.location_id
            WHERE
            tbl_room_allocations.residence_session_id = $residenceSessionId
            AND tbl_room_allocations.approved_status = 1
            ORDER BY
            tbl_room_allocations.room_allocation_id DESC");

            $allocationsPending = DB::select("SELECT
            *
            FROM
            tbl_room_allocations
            INNER JOIN tbl_rooms ON tbl_room_allocations.room_id = tbl_rooms.room_id
            INNER JOIN tbl_floors ON tbl_rooms.floor_id = tbl_floors.floor_id
            INNER JOIN tbl_room_types ON tbl_rooms.room_type_id = tbl_room_types.room_type_id
            INNER JOIN tbl_hostels ON tbl_floors.hostel_id = tbl_hostels.hostel_id
            INNER JOIN tbl_locations ON tbl_hostels.location_id = tbl_locations.location_id
            WHERE
            tbl_room_allocations.residence_session_id = $residenceSessionId
            AND tbl_room_allocations.approved_status = 0
            ORDER BY
            tbl_room_allocations.room_allocation_id DESC");

            if ($result == 1) {
                return response()->json(['message' => 'Room allocation successful', 'success' => true, 'rooms' => $roomallocations], 200);
            } else {
                return $result;
            }
        } catch (QueryException $ex) {
            if ($ex->errorInfo[1] == 1062) {
                return response()->json(['success' => false, 'message' => 'student already allocated room'], 501);
            } else {
                return response()->json(['error' => $ex->getMessage(), 'success' => false], 500);
            }
        }
    }

    public function Autoalocation($room_application)
    {
        $room_allocation = new roomAllocation();
        $room_allocation->student_id = $room_application->student_id;
        $room_allocation->reg_number = $room_application->reg_number;
        $room_allocation->room_id = $room_application->room_id;
        $room_allocation->active_period_id = $room_application->active_period_id;
        $room_allocation->residence_session_id = $room_application->residence_session_id;
        $room_allocation->application_id = $room_application->room_allocation_application_id;
        $room_allocation->approved_status = '1';
        $room_allocation->approved_by = 'system';
        $room_allocation->allocated_by = 'system';
        $room_allocation->save();
        $room_application->application_status = 1;
        $room_application->update();
    }

    public function getroomsToallocate(Request $request)
    {
        $request->validate(['room_gender' => 'required', 'batch_id' => 'required', 'active_period_id' => 'required']);

        $roomgender = $request['room_gender'];
        $active_period_id = $request['active_period_id'];
        $residence_session_id = $request['batch_id'];
        $datenow = $datenow = Carbon::now();
        $newDate = Carbon::createFromFormat('Y-m-d H:i:s', $datenow)
            ->format('Y/m/d');
        try {
            $rooms = DB::select("SELECT
            tbl_rooms.room_id,
            tbl_rooms.room_gender,
            tbl_rooms.room_number,
            tbl_rooms.room_capacity,
            tbl_room_types.room_type,
            tbl_room_type_costs.room_price,
            tbl_floors.floor_name,
            tbl_hostels.hostel_name,
            tbl_locations.location_name
        FROM
            tbl_rooms
            INNER JOIN
            tbl_room_types
            ON
                tbl_rooms.room_type_id = tbl_room_types.room_type_id
            INNER JOIN
            tbl_room_type_costs
            ON
                tbl_room_types.room_type_id = tbl_room_type_costs.room_type_id
            INNER JOIN
            tbl_floors
            ON
                tbl_rooms.floor_id = tbl_floors.floor_id
            INNER JOIN
            tbl_hostels
            ON
                tbl_floors.hostel_id = tbl_hostels.hostel_id
            INNER JOIN
            tbl_locations
            ON
                tbl_hostels.location_id = tbl_locations.location_id
        WHERE
            tbl_rooms.hostel_id IN ((
                SELECT
                    tbl_hostel_preference.hostel_id
                FROM
                    tbl_hostel_preference
                    INNER JOIN tbl_residence_sessions ON tbl_hostel_preference.residence_session_id = tbl_residence_sessions.residence_session_id
                    INNER JOIN tbl_active_period_hostel_online_application ON tbl_residence_sessions.active_period_id = tbl_active_period_hostel_online_application.active_period_id
                WHERE
                    tbl_residence_sessions.end_date > '$newDate' and
                    tbl_residence_sessions.residence_session_id = $residence_session_id
                    AND tbl_active_period_hostel_online_application.is_active = 1
                    AND tbl_residence_sessions.available_status = 1
                )) AND
            tbl_rooms.floor_id IN ((
                SELECT
                    tbl_hostel_preference.floor_id
                FROM
                    tbl_hostel_preference
                    INNER JOIN tbl_residence_sessions ON tbl_hostel_preference.residence_session_id = tbl_residence_sessions.residence_session_id
                    INNER JOIN tbl_active_period_hostel_online_application ON tbl_residence_sessions.active_period_id = tbl_active_period_hostel_online_application.active_period_id
                WHERE
                    tbl_residence_sessions.end_date > '$newDate'
                                    and
                                    tbl_residence_sessions. residence_session_id = $residence_session_id
                    AND tbl_active_period_hostel_online_application.is_active = 1
                    AND tbl_residence_sessions.available_status = 1
                )) AND
            tbl_room_type_costs.active_period_id = $active_period_id AND
            tbl_rooms.room_id NOT IN ((
                SELECT
                    tbl_room_status.room_id
                FROM
                    tbl_room_status
                    INNER JOIN tbl_residence_sessions ON tbl_room_status.residence_session_id = tbl_residence_sessions.residence_session_id
                WHERE
                    tbl_room_status.room_status <> 1
                    AND tbl_room_status.residence_session_id = $residence_session_id
                )) AND
            tbl_rooms.room_id NOT IN ((
                SELECT
                    tbl_resevations.room_id
                FROM
                    tbl_resevations
                WHERE
                tbl_resevations.residence_session_id = $residence_session_id
            )) AND
            tbl_rooms.room_gender = '$roomgender'");

            return response()->json($rooms, 200);
        } catch (QueryException $ex) {
            return response()->json(['message' => $ex->getMessage(), 'success' => false], 500);
        }
    }

    public function show(Request $request)
    {

        $request->validate(['residence_session_id' => 'required']);
        $residenceSessionId = $request['residence_session_id'];

        try {
            $roomallocations = DB::select("SELECT
            *
        FROM
            tbl_room_allocations
            INNER JOIN tbl_rooms ON tbl_room_allocations.room_id = tbl_rooms.room_id
            INNER JOIN tbl_floors ON tbl_rooms.floor_id = tbl_floors.floor_id
            INNER JOIN tbl_room_types ON tbl_rooms.room_type_id = tbl_room_types.room_type_id
            INNER JOIN tbl_hostels ON tbl_floors.hostel_id = tbl_hostels.hostel_id
            INNER JOIN tbl_locations ON tbl_hostels.location_id = tbl_locations.location_id
        WHERE
            tbl_room_allocations.residence_session_id = $residenceSessionId
            AND tbl_room_allocations.approved_status = 1
        ORDER BY
            tbl_room_allocations.room_allocation_id DESC");

$allocationsPending = DB::select("SELECT
*
FROM
tbl_room_allocations
INNER JOIN tbl_rooms ON tbl_room_allocations.room_id = tbl_rooms.room_id
INNER JOIN tbl_floors ON tbl_rooms.floor_id = tbl_floors.floor_id
INNER JOIN tbl_room_types ON tbl_rooms.room_type_id = tbl_room_types.room_type_id
INNER JOIN tbl_hostels ON tbl_floors.hostel_id = tbl_hostels.hostel_id
INNER JOIN tbl_locations ON tbl_hostels.location_id = tbl_locations.location_id
WHERE
tbl_room_allocations.residence_session_id = $residenceSessionId
AND tbl_room_allocations.approved_status = 0
ORDER BY
tbl_room_allocations.room_allocation_id DESC");

            return response()->json(['allocations_appproved' => $roomallocations, 'allocations_pending' => $allocationsPending], 200);
        } catch (QueryException $ex) {
            return response()->json(['success' => false, 'message' => $ex->getMessage()], 500);
        }
    }

   public function approve_reject(Request $request)
   {
   $data = $request->validate(['approved_status' => 'required', 'room_allocation_id' => 'required', 'residence_session_id' => 'required']);
 $approved_status = $data['approved_status'];
 $room_allocation_id = $data['room_allocation_id'];

 $residenceSessionId = $data['residence_session_id'];

 $room_allocation = roomAllocation::find($room_allocation_id);
 $room_allocation->approved_status = $approved_status;
$room_allocation->update();
$roomallocations = DB::select("SELECT
*
FROM
tbl_room_allocations
INNER JOIN tbl_rooms ON tbl_room_allocations.room_id = tbl_rooms.room_id
INNER JOIN tbl_floors ON tbl_rooms.floor_id = tbl_floors.floor_id
INNER JOIN tbl_room_types ON tbl_rooms.room_type_id = tbl_room_types.room_type_id
INNER JOIN tbl_hostels ON tbl_floors.hostel_id = tbl_hostels.hostel_id
INNER JOIN tbl_locations ON tbl_hostels.location_id = tbl_locations.location_id
WHERE
tbl_room_allocations.residence_session_id = $residenceSessionId
AND tbl_room_allocations.approved_status = 1
ORDER BY
tbl_room_allocations.room_allocation_id DESC");

$allocationsPending = DB::select("SELECT
*
FROM
tbl_room_allocations
INNER JOIN tbl_rooms ON tbl_room_allocations.room_id = tbl_rooms.room_id
INNER JOIN tbl_floors ON tbl_rooms.floor_id = tbl_floors.floor_id
INNER JOIN tbl_room_types ON tbl_rooms.room_type_id = tbl_room_types.room_type_id
INNER JOIN tbl_hostels ON tbl_floors.hostel_id = tbl_hostels.hostel_id
INNER JOIN tbl_locations ON tbl_hostels.location_id = tbl_locations.location_id
WHERE
tbl_room_allocations.residence_session_id = $residenceSessionId
AND tbl_room_allocations.approved_status = 0
ORDER BY
tbl_room_allocations.room_allocation_id DESC");

return response()->json(['allocations_appproved' => $roomallocations, 'message' => 'successfully updated', 'allocations_pending' => $allocationsPending], 200);

   }

public function getstudentallocation(Request $request)
{
$request->validate(['regnumber' => 'required']);
    $regnumber = $request['regnumber'];
    $datenow = $datenow = Carbon::now();
        $datenow = $datenow = Carbon::now();
        $newDate = Carbon::createFromFormat('Y-m-d H:i:s', $datenow)
            ->format('Y/m/d');

    $Myapplication = DB::select("SELECT
	tbl_locations.location_name,
	tbl_hostels.hostel_name,
	tbl_rooms.room_id,
	tbl_rooms.room_gender,
	tbl_rooms.room_number,
	tbl_rooms.room_capacity,
	tbl_room_allocation_applications.date_of_application,
	tbl_room_allocation_applications.expiration_date,
	tbl_residence_sessions.session_name
FROM
	tbl_room_allocation_applications
	INNER JOIN tbl_rooms ON tbl_room_allocation_applications.room_id = tbl_rooms.room_id
	INNER JOIN tbl_hostels ON tbl_rooms.hostel_id = tbl_hostels.hostel_id
	INNER JOIN tbl_locations ON tbl_hostels.location_id = tbl_locations.location_id
	INNER JOIN tbl_residence_sessions ON tbl_room_allocation_applications.residence_session_id = tbl_residence_sessions.residence_session_id
WHERE
	tbl_room_allocation_applications.application_status = 0
	AND tbl_room_allocation_applications.reg_number = '$regnumber'");

    $my_allocation = DB::select("SELECT
	tbl_locations.location_name,
	tbl_hostels.hostel_name,
	tbl_floors.floor_name,
	tbl_rooms.room_number,
	tbl_room_allocations.date_allocated,
	tbl_residence_sessions.session_name
FROM
	tbl_room_allocations
	INNER JOIN tbl_rooms ON tbl_room_allocations.room_id = tbl_rooms.room_id
	INNER JOIN tbl_residence_sessions ON tbl_room_allocations.residence_session_id = tbl_residence_sessions.residence_session_id
	INNER JOIN tbl_floors ON tbl_rooms.floor_id = tbl_floors.floor_id
	INNER JOIN tbl_hostels ON tbl_floors.hostel_id = tbl_hostels.hostel_id
	INNER JOIN tbl_locations ON tbl_hostels.location_id = tbl_locations.location_id
WHERE
	tbl_room_allocations.reg_number = '$regnumber'
	AND tbl_residence_sessions.end_date > '$newDate'
	AND tbl_residence_sessions.available_status = 1");

if ($Myapplication) {

}

return response()->json($my_allocation, 200);

}

    // public function get_pending_allocation($regnumber)
    // {
    //     $datenow = $datenow = Carbon::now();
    //     $datenow = $datenow = Carbon::now();
    //     $newDate = Carbon::createFromFormat('Y-m-d H:i:s', $datenow)
    //         ->format('Y/m/d');
    //     $activeallocation = DB::select("SELECT
    //     COUNT( tbl_room_allocations.room_allocation_id ) AS count
    // FROM
    //     tbl_room_allocations
    //     INNER JOIN tbl_residence_sessions ON tbl_room_allocations.residence_session_id = tbl_residence_sessions.residence_session_id
    // WHERE
    //     tbl_residence_sessions.end_date > '$newDate'
    //     AND tbl_room_allocations.reg_number = '$regnumber'");

    //     return  $activeallocation;
    // }
}
