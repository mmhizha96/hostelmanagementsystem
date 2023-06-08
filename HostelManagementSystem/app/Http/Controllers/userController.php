<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\userright;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;


class userController extends Controller
{
    //

    public function DeactivateUser(Request $request)
    {
        $request->validate(['user_id' => 'required']);
        $user_id = $request['user_id'];
        try {
            $user = User::find($user_id);
            $user->is_active = '0';
            $user->update();
            $users = User::orderby('updated_at', 'desc')->get();

            return response()->json(['success' => false, 'message' => 'successfully updated', 'users' => $users], 200);
        } catch (QueryException $ex) {
            return response()->json(['success' => false, 'message' => $ex->getMessage()], 500);
        }
    }

public function getrights(Request $request)
{
    $request->validate(['user_id'=>'required']);
    $user_id = $request['user_id'];
    $myrights=userright::where('user_id',$user_id)->first();


 return response()->json($myrights, 201);
}

public function createrights(Request $request)
{
    $request->validate(['user_id' => 'required', 'rights' => 'required', 'assigned_by'=>'required']);
    $user_id = $request['user_id'];
try {


    $myrights=userright::where('user_id',$user_id)->first();
    if($myrights){
        $myrights->delete();
    }
$userright = new userright();

$userright::create($request->toArray());



return response()->json(['success' => true, 'message' => 'rights created successfully'], 201);

} catch (QueryException $ex) {
        if ($ex->errorInfo[1] == 1062) {
                return response()->json(['success' => false, 'message' => 'user already has right'], 403);
            } else {
                return response()->json(['success' => false, 'message' => $ex->getMessage()], 501);
            }
}

}


    public function activateUser(Request $request)
    {
        $request->validate(['user_id' => 'required']);
        $user_id = $request['user_id'];
        try {
            $user = User::find($user_id);
            $user->is_active = '1';
            $user->update();
            $users = User::orderby('updated_at', 'desc')->get();

            return response()->json(['success' => false, 'message' => 'successfully updated', 'users' => $users], 200);
        } catch (QueryException $ex) {
            return response()->json(['success' => false, 'message' => $ex->getMessage()], 500);
        }
    }
}
