<?php

namespace App\Http\Controllers;

use App\Models\room;
use App\Models\roomtype;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;

class roomsController extends Controller
{
    //
    public function createRoom(Request $request)
    {
        $request->validate([
            'room_number' => 'required', 'room_type_id' => 'required', 'floor_id' => 'required','room_gender' => 'required', 'hostel_id' => 'required',
        ]);
        $room = new room();
        try {
            $roomtype = roomtype::find($request['room_type_id']);
            $room->room_capacity = $roomtype->room_capacity;
            $room->room_number = $request->input('room_number');
            $room->room_type_id = $request->input('room_type_id');
            $room->floor_id = $request->input('floor_id');
            $room->room_gender = $request->input('room_gender');

            $room->hostel_id = $request->input('hostel_id');
            $room->save();
            $hostel_id = $request['hostel_id'];
            $rooms = room::join('tbl_hostels', 'tbl_hostels.hostel_id', '=', 'tbl_rooms.hostel_id')
                ->join('tbl_room_types', 'tbl_room_types.room_type_id', '=', 'tbl_rooms.room_type_id')
                ->where('tbl_rooms.hostel_id', $hostel_id)->get();
            return response()->json(['message' => 'successfully created room', 'success' => true, 'rooms' => $rooms], 200);
        } catch (QueryException $e) {
            if ($e->errorInfo[1] == 1062) {
                return response()->json(['success' => false, 'message' => 'room number already exist'], 501);
            } else {
                return response()->json(['message' => $e->getMessage(), 'success' => false], 521);
            }
        }
    }

    public function updateRoom(Request $request)
    {
        $request->validate([
            'room_number' => 'required',  'room_type_id' => 'required', 'room_gender' => 'required', 'hostel_id' => 'required',
        ]);


        try {
            $room_id = $request['room_id'];

            $room = room::find($room_id);
            $room->room_number = $request['room_number'];

            $room->room_type_id = $request['room_type_id'];
            $room->room_gender = $request['room_gender'];

            $room->update();
            $hostel_id = $request['hostel_id'];
            $rooms = room::join('tbl_hostels', 'tbl_hostels.hostel_id', '=', 'tbl_rooms.hostel_id')
                ->join('tbl_room_types', 'tbl_room_types.room_type_id', '=', 'tbl_rooms.room_type_id')
                ->where('tbl_rooms.hostel_id', $hostel_id)->get();
            return response()->json(['message' => 'successfully updated room', 'success' => true, 'rooms' => $rooms], 200);
        } catch (QueryException $ex) {
            if ($ex->errorInfo[1] == 1062) {
                return response()->json(['success' => false, 'message' => 'room number already exist'], 501);
            } else {
                return response()->json(['error' => $ex->getMessage(), 'success' => false], 500);
            }
        }
    }

    public function deleteRoom(Request $request)
    {
        try {

            $room = room::find($request['room_id']);

            $room->delete();
        } catch (QueryException $e) {

            if ($e->errorInfo[1] == 1451) {
                return response()->json(['success' => false, 'message' => 'room cannot be deleted, other parts of the system are dependent on this room'], 501);
            } else {
                return response()->json(['error' => $e->getMessage(), 'success' => false], 500);
            }
        }

        return response()->json(['message' => 'successfully deleted room', 'success' => true, 'rooms' => room::all()], 200);
    }

    public function searchRoom(Request $request)
    {
        try {

            $room = room::find($request['room_id']);

            return response()->json($room);
        } catch (QueryException $ex) {
            return response()->json(['error' => $ex->getMessage()]);
        }
    }

    public function getRooms(Request $request)
    {
        $request->validate(['hostel_id' => 'required']);

        $hostel_id = $request['hostel_id'];
        try {

            $rooms = room::join('tbl_hostels', 'tbl_hostels.hostel_id', '=', 'tbl_rooms.hostel_id')
                ->join('tbl_room_types', 'tbl_room_types.room_type_id', '=', 'tbl_rooms.room_type_id')
                ->where('tbl_rooms.hostel_id', $hostel_id)->get();

            return response()->json($rooms);
        } catch (QueryException $ex) {
            return response()->json(['error' => $ex->getMessage()]);
        }
    }
}
