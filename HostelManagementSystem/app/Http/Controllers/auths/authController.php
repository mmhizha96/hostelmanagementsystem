<?php

namespace App\Http\Controllers\auths;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\userright;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class authController extends Controller
{
    //login function

    public function login(Request $request)
    {
        $validator = $request->validate(['email' => 'required|string', 'password' => 'required|string']);
        $user = User::where('email', $request->email)->first();

        if ($user) {
            if (Hash::check($request->password, $user->password)) {
                if ($user->is_active == 0) {
                    return response()->json(['success' => false, 'message' => 'account in active contact you administrator for assistance'], 401);
                } else {
                    $prompt_change = 0;

                    if (Hash::check($user->email, $user->password)) {
                        $prompt_change = 1;
                    }

                    $user_id = $user->user_id;

                    $myrights = userright::where('user_id', $user_id)->first();

                    $token = $user->createToken('Laravel Password Grant Client')->accessToken;

                    return response()->json(['user_details' => $user,  'prompt_change' => $prompt_change, 'myrights' => $myrights, 'access_token' => $token], 200);
                }
            } else {
                return response()->json(['success' => false, 'message' => 'username or password not correct'], 401);
            }
        }

        return response()->json(['success' => false, 'message' => 'username or password not correct'], 401);
    }

    public function changePassword(Request $request)
    {
        $validator = $request->validate([
            'password' => 'required|string|min:10',
            'user_id' => 'required',
        ]);

        try {
            $password = Hash::make($request['password']);
            $user_id = $request['user_id'];
            $user = User::find($user_id);
            $user->password = $password;
            $user->update();

            return response()->json(['success' => true, 'message' => 'successfully created'], 200);
        } catch (QueryException $ex) {
            return response()->json(['success' => false, 'message' => $ex->getMessage()], 500);
        }
    }

    public function register(Request $request)
    {
        try {
            $validator = $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:tbl_users',
                'role' => 'required',
                'ec_number' => 'required',
            ]);
            $ecnumber = $request['ec_number'];
            $employee = DB::select("SELECT
	registry.tblemployees.ec_number
FROM
	registry.tblemployees
WHERE
	registry.tblemployees.ec_number = $ecnumber");

            if ($employee) {
                $request['password'] = Hash::make($request['password']);
                $user = User::create($request->toArray());
                $users = User::orderby('user_id', 'desc')->get();

                return response()->json(['success' => true, 'message' => 'successfully created', 'users' => $users], 200);
            } else {
                return response()->json(['success' => false, 'message' => 'employee not found'], 403);
            }
        } catch (QueryException $ex) {
            return response()->json(['success' => false, 'message' => $ex->getMessage()], 500);
        }
    }

    public function getUsers(Request $request)
    {
        try {

            $users = User::orderby('user_id', 'desc')->get();

            return response()->json($users, 200);
        } catch (QueryException $ex) {
            return response()->json(['success' => false, 'message' => $ex->getMessage()], 500);
        }
    }
}
