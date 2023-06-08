<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\residenceSession;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\QueryException;

class residenceSessionsController extends Controller
{
    //
    public function getBatches(Request $request)
    {
        try {
            $id = $request['active_period_id'];
            $residence_session = DB::select("SELECT
            tbl_residence_sessions.*
        FROM
            tbl_residence_sessions
        WHERE
            tbl_residence_sessions.active_period_id = $id
        ORDER BY
            tbl_residence_sessions.residence_session_id DESC");

            return response()->json($residence_session, 200);
        } catch (QueryException $ex) {
            return response()->json(['error' => $ex->getMessage()], 403);
        }
    }
    public function getAllBatches(){
        $batches= residenceSession::all();
        return response()->json($batches, 200);
    }


    public function getStudentBatches(Request $request)
    {
        $request->validate([

            'level' => 'required',
            'semester' => 'required',
            'period_id' => 'required'
        ]);
        $datenow = $datenow = Carbon::now();
        $newDate = Carbon::createFromFormat('Y-m-d H:i:s', $datenow)
            ->format('Y/m/d');
        $level = $request['level'];
        $period = $request['period_id'];
        $semester = $request['semester'];
        $batches = DB::select("SELECT
        tbl_residence_sessions.session_name,
        tbl_residence_sessions.active_period_id,
        tbl_active_period_hostel_online_application.period_id,
        tbl_residence_sessions.semester,
        tbl_residence_sessions.`level`,
        tbl_residence_sessions.residence_session_id,
        tbl_residence_sessions.start_date,
        tbl_residence_sessions.available_status,
        tbl_residence_sessions.end_date
    FROM
        tbl_residence_sessions
        INNER JOIN tbl_active_period_hostel_online_application ON tbl_residence_sessions.active_period_id = tbl_active_period_hostel_online_application.active_period_id
    WHERE
        tbl_residence_sessions.`level` = $level
        AND tbl_residence_sessions.semester = $semester
        AND tbl_active_period_hostel_online_application.period_id =$period AND tbl_residence_sessions.available_status = 1
        AND tbl_residence_sessions.end_date > '$newDate'");


        return response()->json($batches, 200);
    }
    public function deleteBatch(Request $request)
    {
        $request->validate([
            'residence_session_id' => 'required',
        ]);
        try {
            $residence_session = residenceSession::find($request['residence_session_id']);
            $residence_session->delete();
            $id = $request['active_period_id'];
            $residence_session = DB::select("SELECT
            tbl_residence_sessions.*
        FROM
            tbl_residence_sessions
        WHERE
            tbl_residence_sessions.active_period_id = $id
        ORDER BY
            tbl_residence_sessions.residence_session_id DESC");

            return response()->json(['message' => 'successfully deleted', 'success' => true, 'residence_session' =>  $residence_session], 200);
        } catch (QueryException $e) {

            if ($e->errorInfo[1] == 1451) {
                return response()->json(['success' => false, 'message' => 'batch cannot be deleted, other parts of the system are dependent on this batch'], 501);
            } else {
                return response()->json(['error' => $e->getMessage(), 'success' => false], 500);
            }
        }
    }

    public function activateBatch(Request $request)

    {
        $request->validate([
            'residence_session_id' => 'required', 'available_status' => 'required',

        ]);
        try {
            $residence_session = residenceSession::find($request['residence_session_id']);
            $residence_session->available_status = 1;

            $residence_session->update();
            $id = $request['active_period_id'];
            $residence_session = DB::select("SELECT
            tbl_residence_sessions.*
        FROM
            tbl_residence_sessions
        WHERE
            tbl_residence_sessions.active_period_id = $id
        ORDER BY
            tbl_residence_sessions.residence_session_id DESC");

            return response()->json(['message' => 'successfully activated batch', 'success' => true, 'residence_session' =>  $residence_session], 200);
        } catch (QueryException $e) {

            return response()->json(['error' => $e->getMessage(), 'success' => false], 500);
        }
    }

    public function deactivateBatch(Request $request)
    {
        $request->validate([
            'residence_session_id' => 'required', 'available_status' => 'required',
        ]);
        try {
            $residence_session = residenceSession::find($request['residence_session_id']);
            $residence_session->available_status = 0;

            $residence_session->update();
            $id = $request['active_period_id'];
            $residence_session = DB::select("SELECT
            tbl_residence_sessions.*
        FROM
            tbl_residence_sessions
        WHERE
            tbl_residence_sessions.active_period_id = $id
        ORDER BY
            tbl_residence_sessions.residence_session_id DESC");

            return response()->json(['message' => 'successfully deactivated batch ', 'success' => true, 'residence_session' =>  $residence_session], 200);
        } catch (QueryException $e) {

            return response()->json(['error' => $e->getMessage(), 'success' => false], 500);
        }
    }

    public function updateBatch(Request $request)
    {

        $request->validate([
            'residence_session_id' => 'required',
            'level' => 'required', 'semester' => 'required', 'start_date' => 'date|required',
            'end_date' => 'required|date', 'session_name' => 'required',
        ]);

        $batch = residenceSession::find($request['residence_session_id']);

        try {
            $batch->session_name = $request['session_name'];
            $batch->level = $request['level'];
            $batch->semester = $request['semester'];
            $batch->start_date = $request['start_date'];
            $batch->end_date = $request['end_date'];
            if ($batch->end_date < $batch->start_date) {
                return response()->json(['success' => false, 'message' => "end date must be greater than start date"], 403);
            } else {


                $batch->update();
                $id = $request['active_period_id'];
                $residence_session = DB::select("SELECT
            tbl_residence_sessions.*
        FROM
            tbl_residence_sessions
        WHERE
            tbl_residence_sessions.active_period_id = $id
        ORDER BY
            tbl_residence_sessions.residence_session_id DESC");
            }
            $allPeriods = residenceSession::all();
        } catch (QueryException $e) {

            if ($e->errorInfo[1] == 1062) {
                return response()->json(['success' => false, 'message' => 'batch Name already exist'], 500);
            } else {
                return response()->json(['success' => false, 'message' => $e->getMessage()], 501);
            }
        }

        return response()->json(['message' => 'sucess updated batch', 'success' => true, 'residence_session' =>  $residence_session], 200);
    }

    public function createBatch(Request $request)
    {
        $request->validate([
            'session_name' => 'required', 'is_program_driven' => 'required', 'active_period_id' => 'required', 'start_date' => 'required', 'end_date' => 'required',
            'available_status' => 'required', 'level' => 'required', 'semester' => 'required',
        ]);

        $residence_session = new residenceSession();
        try {
            $residence_session->session_name = $request['session_name'];

            $residence_session->is_program_driven = $request['is_program_driven'];
            $residence_session->level = $request['level'];
            $residence_session->semester = $request['semester'];
            $residence_session->active_period_id = $request['active_period_id'];
            $residence_session->start_date = $request['start_date'];
            $residence_session->end_date = $request['end_date'];
            $residence_session->available_status = $request['available_status'];
            if ($residence_session->end_date < $residence_session->start_date) {
                return response()->json(['success' => false, 'message' => "end date must be greater than start date"], 403);
            } else {
                $residence_session->save();

                $id = $request['active_period_id'];
                $residence_session = DB::select("SELECT
            tbl_residence_sessions.*
        FROM
            tbl_residence_sessions
        WHERE
            tbl_residence_sessions.active_period_id = $id
        ORDER BY
            tbl_residence_sessions.residence_session_id DESC");

                return response()->json([
                    'message' => 'batch succesfully created ',
                    'success' => true,
                    'residence_session' =>  $residence_session
                ], 200);
            }
        } catch (QueryException $e) {

            if ($e->errorInfo[1] == 1062) {
                return response()->json(['success' => false, 'message' => 'Batch Name already exist'], 500);
            } else {
                return response()->json(['success' => false, 'message' => $e->getMessage()], 501);
            }
        }
    }
}
