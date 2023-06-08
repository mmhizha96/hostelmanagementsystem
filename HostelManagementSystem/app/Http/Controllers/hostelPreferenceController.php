<?php

namespace App\Http\Controllers;

use App\Models\floor;
use App\Models\hostelpreference;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class hostelPreferenceController extends Controller
{
    public function setHostelPreference(Request $request)
    {
$hostelpvalues = $request->validate(['hostel_id' => 'required', 'floor_id' => 'required', 'activated_by' => 'required', 'residence_session_id' => 'required', 'floor_id' => 'required']);

$floor_id = $request['floor_id'];
$hostel_id = $request['hostel_id'];
$residence_session_id = $request['residence_session_id'];

if ($floor_id == 'All') {

    $floors = floor::where('hostel_id', $hostel_id)->get()->toArray();


    foreach ($floors as $floor) {
        try {
        $hostelpreference = new hostelpreference();
        $hostelpreference->hostel_id = $hostel_id;
        $hostelpreference->active_period_id = $request['active_period_id'];
        $hostelpreference->activated_by = $request['activated_by'];
        $hostelpreference->floor_id = $floor['floor_id'];
        $hostelpreference->residence_session_id = $residence_session_id;
        $hostelpreference->save();
    } catch (QueryException $ex) {
        if ($ex->errorInfo[1] == 1062) {

        } else {
            return response()->json(['message' => $ex->getMessage(), 'success' => false], 501);
        }
    }

}


} else {
    try {
        $hostelpreference = new hostelpreference();
        $hostelpreference->hostel_id = $hostel_id;

$hostelpreference->active_period_id = $request['active_period_id'];
$hostelpreference->activated_by = $request['activated_by'];
$hostelpreference->date_activated = $request['date_activated'];
$hostelpreference->floor_id = $request['floor_id'];
$hostelpreference->residence_session_id = $request['residence_session_id'];

$hostelpreference->save();

    } catch (QueryException $ex) {
        if ($ex->errorInfo[1] == 1062) {
            return response()->json(['message' => 'hostel  floor already added for this batch', 'success' => false], 403);
        } else {
            return response()->json(['message' => $ex->getMessage(), 'success' => false], 501);
        }
    }
}

$hostelpreference = hostelpreference::where('residence_session_id', $residence_session_id)->orderBy('hostel_preference_id', 'desc')->get();

return response()->json(['message' => 'succesfully created  hostel preference', 'hostelpreference' => $hostelpreference, 'success' => true], 200);

    }

public function deleteHostelPreference(Request $request)
{
    $hostelpreference_id = $request['hostel_preference_id'];
    $residence_session_id = $request['residence_session_id'];

try {
    $hostelpreference = hostelpreference::find($hostelpreference_id);
$hostelpreference->delete();
$hostelpreference = DB::select("SELECT
	tbl_hostels.hostel_name,
	tbl_hostels.hostel_id,
	tbl_locations.location_name,
	tbl_hostel_preference.hostel_id,
	tbl_hostel_preference.residence_session_id,
	tbl_hostel_preference.hostel_preference_id
FROM
tbl_hostel_preference
INNER JOIN tbl_hostels ON tbl_hostel_preference.hostel_id = tbl_hostels.hostel_id
INNER JOIN tbl_locations ON tbl_hostels.location_id = tbl_locations.location_id
WHERE
tbl_hostel_preference.residence_session_id = $residence_session_id");
// get hostels to add
$hostelstoadd = DB::select("SELECT
tbl_hostels.hostel_id,
tbl_hostel_preference.hostel_preference_id,
tbl_hostel_preference.residence_session_id,
tbl_hostels.hostel_name,
tbl_hostels.location_id
FROM
tbl_hostels
LEFT JOIN
tbl_hostel_preference
ON
    tbl_hostels.hostel_id = tbl_hostel_preference.hostel_id
WHERE
tbl_hostel_preference.hostel_preference_id IS NULL OR
tbl_hostel_preference.residence_session_id <> $residence_session_id");

return response()->json(['message' => 'succesfully deleted hostel prefetence', 'success' => true, 'hostelpreference' => $hostelpreference, 'hostelstoadd' => $hostelstoadd], 200);
} catch (QueryException $ex) {
    return response()->json(['message' => $ex->getMessage(), 'success' => false], 500);
}

}

public function getHostelsToadd(Request $request)
{
    $residence_session_id = $request['residence_session_id'];
    $hostelstoadd = DB::select("SELECT
	tbl_hostels.hostel_id,
	tbl_hostel_preference.hostel_preference_id,
	tbl_hostel_preference.residence_session_id,
	tbl_hostels.hostel_name,
	tbl_hostels.location_id
FROM
	tbl_hostels
	LEFT JOIN
	tbl_hostel_preference
	ON
		tbl_hostels.hostel_id = tbl_hostel_preference.hostel_id
WHERE
	tbl_hostel_preference.hostel_preference_id IS NULL OR
	tbl_hostel_preference.residence_session_id <> $residence_session_id");

    return response()->json($hostelstoadd, 200);
}

public function getHostelPreference(Request $request)
{
    $residence_session_id = $request['residence_session_id'];

    $hostelpreference = DB::select("SELECT
	tbl_hostel_preference.residence_session_id,
	tbl_hostel_preference.hostel_preference_id,
	tbl_floors.floor_name,
	tbl_hostels.hostel_name,
	tbl_locations.location_name
FROM
	tbl_hostel_preference
	INNER JOIN tbl_floors ON tbl_hostel_preference.floor_id = tbl_floors.floor_id
	INNER JOIN tbl_hostels ON tbl_floors.hostel_id = tbl_hostels.hostel_id
	INNER JOIN tbl_locations ON tbl_hostels.location_id = tbl_locations.location_id
WHERE
	tbl_hostel_preference.residence_session_id = $residence_session_id
ORDER BY
	tbl_hostel_preference.hostel_preference_id DESC");



    return response()->json($hostelpreference, 200);

}
}
