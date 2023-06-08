<?php

namespace App\Http\Controllers;

use App\Models\roomtype;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;

class roomTypeController extends Controller
{
    public function createRoomType(Request $request)
    {
        $request->validate(['room_type' => 'required', 'room_capacity' => 'required']);
        $roomType = new roomtype();
        try {

            $roomType->room_type = $request['room_type'];
            $roomType->room_capacity = $request['room_capacity'];

            $roomType->save();
        } catch (QueryException $ex) {
            if ($ex->errorInfo[1] == 1062) {
                return response()->json(['message' => 'room type already exist', 'success' => false], 500);
            } else {
                return response()->json(['message' => $ex->getMessage(), 'success' => false], 501);
            }
        }
        $roomtypes = roomtype::all();

        return response()->json(['message' => 'successfully created room type', 'success' => true,'roomtypes'=>$roomtypes], 200);
     
    }

    public function show()
    {
        $roomtypes = roomtype::all();

        return response()->json($roomtypes, 200);
    }

    public function updateRoomType(Request $request)
    {
        //
        $request->validate(['room_type' => 'required', 'room_type_id' => 'required', 'room_capacity' => 'required']);
        $room_type_id = $request['room_type_id'];
        $roomType = roomtype::find($room_type_id);
        try {

            $roomType->room_type = $request['room_type'];
            $roomType->room_capacity = $request['room_capacity'];

            $roomType->update();
        } catch (QueryException $ex) {
            if ($ex->errorInfo[1] == 1062) {
                return response()->json(['message' => 'room type already exist', 'success' => false], 500);
            } else {
                return response()->json(['message' => $ex->getMessage(), 'success' => false], 501);
            }
            }

        return response()->json(['message' => 'successfully updated room type', 'success' => true], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function deleteRoomType(Request $request)
    {
        //
        try {

            $room_type_id = $request['room_type_id'];
        $roomType = roomtype::find($room_type_id);
        if (! $roomType) {
            return response()->json(['error' => 'room type not found', 'success' => false], 501);

        }
        $roomType->delete();
        } catch (QueryException $ex) {
            if ($ex->errorInfo[1] == 1451) {
                return response()->json(['success' => false, 'message' => 'room type cannot be deleted, other parts of the system are dependent on it'], 501);

            } else {
                return response()->json(['error' => $ex->getMessage(), 'success' => false], 501);
            }

        }
 $roomtypes = roomtype::all();

        return response()->json(['message' => 'successfully deleted room type', 'success' => true,'roomtypes'=>$roomtypes], 200);
    }
}
