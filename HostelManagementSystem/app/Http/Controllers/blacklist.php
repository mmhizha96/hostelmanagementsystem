<?php

namespace App\Http\Controllers;

use App\Models\blacklistedstudent;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;

class blacklist extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $blacklist = blacklistedstudent::orderBy('blacklisted_students_id', 'desc')->get();

        return response()->json($blacklist, 200);
    }


    public function store(Request $request)
    {
        try {
            $blacklist = new  blacklistedstudent();
            $blacklist->student_regnumber = $request['reg_number'];
            $blacklist->reason = $request['reason'];
            $blacklist->blacklisted_by = $request['blacklisted_by'];
            $blacklist->save();
            return response()->json(['message' => 'Successfully saved!', 'success' => true], 201);
        } catch (QueryException $ex) {
            if ($ex->errorInfo[1] == 1062) {
                return response()->json(['message' => 'student already blacklisted', 'success' => false], 403);
            } else {
                return response()->json(['message' => $ex->getMessage(), 'success' => false], 501);
            }
        }
    }


    public function destroy(Request $request)
    {
        try {
            $id = $request['id'];
            $blacklist = blacklistedstudent::find($id);
            $blacklist->delete();
            $blacklist = blacklistedstudent::orderBy('blacklisted_students_id', 'desc')->get();
            return response()->json(['message' => 'Successfully saved!', 'blacklist' => $blacklist, 'success' => true], 201);
        } catch (QueryException $ex) {
            return response()->json(['message' => $ex->getMessage(), 'success' => false], 501);
        }
    }
}
