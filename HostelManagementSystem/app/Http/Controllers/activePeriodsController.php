<?php

namespace App\Http\Controllers;

use App\Models\active_period_hostel_online_application;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;

class activePeriodsController extends Controller
{
    public function createActivePeriod(Request $request)
    {
        $request->validate([
            'period_id' => 'required',
        ]);

        try {
            $activeperiod = new active_period_hostel_online_application();
            $activeperiod->period_id = $request['period_id'];
            $activeperiod->activated_by = $request['activated_by'];
            $activeperiod->is_active = $request['is_active'];
            $activeperiod->save();
            $allPeriods =active_period_hostel_online_application::join('registry.tblperiod', 'registry.tblperiod.period_id', '=', 'tbl_active_period_hostel_online_application.period_id')->orderBy('active_period_id', 'desc')->get();

            return response()->json(['message' => 'successfully created period', 'success' => true, 'periods' => $allPeriods], 201);
        } catch (QueryException $e) {

            if ($e->errorInfo[1] == 1062) {
                return response()->json(['success' => false, 'message' => 'period already exist'], 500);

            } else {
                return response()->json(['success' => false, 'message' => $e->getMessage()], 501);
            }
        }

    }

    public function deactivatePeriod(Request $request)
    {
        $request->validate([
            'active_period_id' => 'required',

        ]);

        $activeperiod = active_period_hostel_online_application::find($request['active_period_id']);
        $allPeriods = null;
        try {

            $activeperiod->is_active = '0';

            $activeperiod->update();
            $allPeriods = active_period_hostel_online_application::join('registry.tblperiod', 'registry.tblperiod.period_id', '=', 'tbl_active_period_hostel_online_application.period_id')->orderBy('active_period_id', 'desc')->get();
        } catch (QueryException $ex) {
            return response()->json(['error' => $ex->getMessage()], 500);
        }

        return response()->json(['message' => 'sucessfully deactivated period', 'success' => false, 'periods' => $allPeriods], 200);
    }

    public function deletePeriod(Request $request)
    {
        $request->validate([
            'active_period_id' => 'required',

        ]);

        $activeperiod = active_period_hostel_online_application::find($request['active_period_id']);
        $allPeriods = null;
        try {
            $activeperiod->delete();
            $allPeriods = active_period_hostel_online_application::join('registry.tblperiod', 'registry.tblperiod.period_id', '=', 'tbl_active_period_hostel_online_application.period_id')->orderBy('active_period_id', 'desc')->get();
        } catch (QueryException $e) {

            if ($e->errorInfo[1] == 1451) {
                return response()->json(['success' => false, 'message' => 'room cannot be deleted, other parts of the system are dependent on this period'], 501);

            } else {
            return response()->json(['error' => $e->getMessage(), 'success' => false], 500);
            }
        }

        return response()->json(['message' => 'sucessfully deleted period', 'success' => false, 'periods' => $allPeriods], 200);
    }

    public function activatePeriod(Request $request)
    {
        $request->validate([
            'active_period_id' => 'required',

        ]);

        $activeperiod = active_period_hostel_online_application::find($request['active_period_id']);
        $allPeriods = null;
        try {

            $activeperiod->is_active = '1';

            $activeperiod->update();
            $allPeriods = active_period_hostel_online_application::join('registry.tblperiod', 'registry.tblperiod.period_id', '=', 'tbl_active_period_hostel_online_application.period_id')->orderBy('active_period_id', 'desc')->get();
        } catch (QueryException $ex) {
            return response()->json(['error' => $ex->getMessage()], 500);
        }

        return response()->json(['message' => 'sucessfully activated period', 'success' => false, 'periods' => $allPeriods], 200);
    }

    public function updatePeriod(Request $request)
    {
        $request->validate([
            'active_period_id' => 'required',
            'start_date' => 'date|required',
            'end_date' => 'required|date', 'period_name' => 'required',
        ]);

        $activeperiod = active_period_hostel_online_application::find($request['active_period_id']);
        $allPeriods = null;
        try {
            $activeperiod->period_name = $request['period_name'];

            $activeperiod->start_date = $request['start_date'];
            $activeperiod->end_date = $request['end_date'];
            if ($activeperiod->end_date < $activeperiod->start_date) {
                return response()->json(['success' => false, 'message' => 'end date must be greater than start date'], 403);
            } else {
            $activeperiod->update();
            $allPeriods = active_period_hostel_online_application::join('registry.tblperiod', 'registry.tblperiod.period_id', '=', 'tbl_active_period_hostel_online_application.period_id')->orderBy('active_period_id', 'desc')->get();
            }

        } catch (QueryException $e) {

            if ($e->errorInfo[1] == 1062) {
                return response()->json(['success' => false, 'message' => 'period Name already exist'], 500);

            } else {
                return response()->json(['success' => false, 'message' => $e->getMessage()], 501);
            }
        }

        return response()->json(['message' => 'sucessfully updated period',  'success' => true, 'periods' => $allPeriods], 200);
    }

    public function getactiveperiod()
    {
        try {

            $activeperiod = active_period_hostel_online_application::join('registry.tblperiod', 'registry.tblperiod.period_id', '=', 'tbl_active_period_hostel_online_application.period_id')->orderBy('active_period_id', 'desc')->get();

            return response()->json($activeperiod);
        } catch (QueryException $ex) {
            return response()->json(['error' => $ex->getMessage()]);
        }
    }

    public function getcurrentactiveperiod()
    {
    $currentdate = now()->format('Y-m-d');
        try {
            // $activeperiod = active_period_hostel_online_application::orderBy('active_period_id','desc')->limit(3)->get();
            $activeperiod = active_period_hostel_online_application::join('registry.tblperiod', 'registry.tblperiod.period_id', '=', 'tbl_active_period_hostel_online_application.period_id')->orderBy('active_period_id', 'desc')->limit(3)->get();

            return response()->json($activeperiod);
        } catch (QueryException $ex) {
            return response()->json(['error' => $ex->getMessage()]);
        }
    }
}
