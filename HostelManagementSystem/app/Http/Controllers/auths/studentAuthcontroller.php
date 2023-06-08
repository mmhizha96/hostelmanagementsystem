<?php

namespace App\Http\Controllers\auths;

use App\Http\Controllers\Controller;
use App\Models\student;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class studentAuthcontroller extends Controller
{
    //login function

    public function login(Request $request)
    {
        $validator = $request->validate(['reg_number' => 'required|string', 'password' => 'required|string']);
        $regnumber = $validator['reg_number'];
        $password = $validator['password'];

        try {
            $students = student::join('tblstudent_passwords', 'tblstudent.reg_number', '=', 'tblstudent_passwords.regnum')
                ->where('tblstudent.reg_number', $regnumber)
                ->where('tblstudent_passwords.password', $password)
                ->first();

            if ($students) {
                // $is_blacklisted = app('App\http\Controllers\studentDetailsController')->is_blacklisted($regnumber);
                // if($is_blacklisted==1){
                //     return response()->json(['success' => false, 'message' => 'sorry you are currently blacklisted please contact your administrator for more '], 401);
                // }

                $student_details = app('App\http\Controllers\studentDetailsController')->getStudentDetails($regnumber);

                $student_id = DB::connection('mysql_2')->select("SELECT
                tblstudent.student_id
            FROM
                tblstudent
            WHERE
                tblstudent.reg_number = '$regnumber'");
                $student_id = $student_id[0]->student_id;


                // $is_registered = app('App\http\Controllers\studentDetailsController')->is_student_registered($student_id);


               $hasapplication = app('App\Http\Controllers\roomApplicationController')->getStudentPendingApplications($regnumber);

                // if ($is_registered == 0) {
                //     return response()->json(['success' => false, 'message' => 'you are currently not registered'], 401);
                // }else if($is_registered<0){
                //     return response()->json(['success' => false, 'message' => 'currently there are no periods to apply'], 401);
                // }



                $fees_balance = app('App\http\Controllers\studentDetailsController')->getFeesBalance($regnumber);

                // $period_id = $student_details[0]->period_id;
                // $minimum_threshhold = app('App\http\Controllers\studentDetailsController')->getminimum_treshold($period_id);


                // if($minimum_threshhold>$fees_balance){
                //     return response()->json(['success' => false, 'message' => "mari ishoma " ], 403);
                // }



                $token = $students->createToken('Laravel Password Grant Client')->accessToken;

                return response()->json(['student' => $student_details[0], 'hasapplied' => $hasapplication[0]->count, 'fees' => $fees_balance, 'access_token' => $token]);
            }

            return response()->json(['success' => false, 'message' => 'username or password not correct'], 401);
        } catch (QueryException $ex) {
            return response()->json(['success' => false, $ex->getMessage()], 401);
        }
    }
}
