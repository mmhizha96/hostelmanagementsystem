<?php

namespace App\Http\Controllers;

use App\Models\hostel;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class hostelController extends Controller
{
    //
public function createHostel(Request $request)
{

$hostel = new hostel();
$request->validate(['hostel_name' => 'required',
    'location_id' => 'required',
]);

try {
    $hostel->hostel_name = $request->input('hostel_name');
$hostel->location_id = $request->input('location_id');
$hostel->save();
$hostellist = hostel::join('tbl_locations', 'tbl_hostels.location_id', '=', 'tbl_locations.location_id')
->get();
return response()->json(['message' => 'successfully created hostel', 'success' => true, 'hostels' =>$hostellist], 200);
} catch (QueryException $ex) {
if ($ex->errorInfo[1] == 1062) {
    return response()->json(['message' => 'hostel already exist', 'success' => false], 500);
} elseif ($ex->errorInfo[1] == 1452) {
    return response()->json(['message' => 'location not found ', 'success' => false], 502);
} else {
    return response()->json(['message' => $ex->getMessage(), 'success' => false], 501);
}

}



}

 public function deleteHostel(Request $request)
 {
try {
    $hostel_id = $request['hostel_id'];
    $hostel = hostel::where('hostel_id', '=', $hostel_id);

$hostel->delete();
$hostellist = hostel::join('tbl_locations', 'tbl_hostels.location_id', '=', 'tbl_locations.location_id')
->get();

return response()->json(['message' => 'successfully deleted hostel', 'success' => true, 'hostels' =>$hostellist], 200);

} catch (QueryException $e) {
if ($e->errorInfo[1] == 1451) {
        return response()->json(['success' => false, 'message' => 'hostel cannot be deleted, other parts of the system are dependent on this hostel'], 501);

    } else {
    return response()->json(['error' => $e->getMessage(), 'success' => false], 500);
    }

}

 }

 public function findhostels(Request $request){

    try {

        $location_id=$request['location_id'];
    $hostels=hostel::where('location_id',$location_id)->get();

        return response()->json( $hostels,200);

        } catch (QueryException $ex) {
            return response()->json(['error' => $ex->getMessage()]);
        }


 }



public function updateHostel(Request $request)
{
    $request->validate(['hostel_name' => 'required',

        'hostel_id' => 'required',
    ]);

   try {
    $hostel_id = $request['hostel_id'];
    $hostel_name = $request->input('hostel_name');
    $result = DB::unprepared("update tbl_hostels set hostel_name='$hostel_name' where hostel_id='$hostel_id'");
    $hostellist = hostel::join('tbl_locations', 'tbl_hostels.location_id', '=', 'tbl_locations.location_id')
    ->get();

    return response()->json(['message' => 'successfully updated hostel', 'success' => true, 'hostels' => $hostellist], 200);

   } catch (QueryException $e) {
    if ($e->errorInfo[1] == 1062) {
        return response()->json(['success' => false, 'message' => 'hohstel name already exist '], 501);

    }

    return response()->json(['error' => $e->getMessage(), 'success' => false], 500);

   }

}

public function getHostels()
{

try {

$hostellist = hostel::join('tbl_locations', 'tbl_hostels.location_id', '=', 'tbl_locations.location_id')
    ->get();

return response()->json($hostellist,200);

} catch (QueryException $ex) {
    return response()->json(['error' => $ex->getMessage()],501);
}

}
}
