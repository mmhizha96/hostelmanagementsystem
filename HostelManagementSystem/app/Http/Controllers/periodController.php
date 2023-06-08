<?php

namespace App\Http\Controllers;

use App\Models\period;
use Exception;
use Illuminate\Http\Request;

class periodController extends Controller
{
    //
    public function getPeriods()
    {
        try {

            $period = period::orderBy('period_id', 'desc')->limit(5)->get();

            return response()->json($period, 200);
        } catch (Exception $ex) {
            return response()->json(['error' => $ex->getMessage()], 500);
        }
    }

    public function updatePeriod(Request $request)
    {
        $request->validate([
            'period_id' => 'required', 'period_name' => 'required', 'period_start_date' => 'required', 'period_end_date' => 'required',
        ]);
        $period = period::find($request['id']);
        try {
            $period->period_name = $request->input('period_name');
            $period->period_start_date = $request->input('period_start_date');
            $period->period_end_date = $request->input('period_end_date');

            $period->update();
        } catch (Exception $ex) {
            return response()->json(['error' => $ex->getMessage()]);
        }

        return response()->json(['message' => 'success']);
    }

    public function addPeriod(Request $request)
    {
        $request->validate([
            'period_name' => 'required', 'period_start_date' => 'required', 'period_end_date' => 'required',
        ]);
        $period = new period();
        try {
            $period->period_name = $request->input('period_name');
            $period->period_start_date = $request->input('period_start_date');
            $period->period_end_date = $request->input('period_end_date');

            $period->save();
        } catch (Exception $ex) {
            return response()->json(['error' => $ex->getMessage()]);
        }

        return response()->json(['message' => 'success']);
    }
}
