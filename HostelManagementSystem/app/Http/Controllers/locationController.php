<?php

namespace App\Http\Controllers;

use App\Models\location;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;

class locationController extends Controller
{
    public function getLocation()
    {
        $locations = '';
try {
$locations = location::all();
} catch (QueryException $ex) {
    return response()->json(['error' => $ex->getMessage()], 500);
}

        return response()->json($locations, 200);
    }

    

    public function addlocation(Request $request)
    {
        $request->validate(['location_name' => 'required']);

        try {
            $location = new location();
            $location->location_name = $request->input('location_name');
            $location->save();

        } catch (QueryException $ex) {
            if ($ex->errorInfo[1] == 1062) {
                return response()->json(['message' => 'location already exist', 'success' => false], 500);
            } else {
                return response()->json(['message' => $ex->getMessage(), 'success' => false], 501);
            }
        }

        return response()->json(['message' => 'success created location', 'success' => true], 200);
    }
}
