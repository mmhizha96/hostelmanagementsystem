<?php

namespace App\Http\Controllers;

use App\Models\active_period_hostel_online_application;
use App\Models\minimumTreshold;
use App\Models\period;
use App\Models\room;
use App\Models\roomAllocation;
use App\Models\roomapplication;
use Carbon\Carbon;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class roomApplicationController extends Controller
{
    //

    public function getMyrooms(Request $request)
    {

        $request->validate(['level' => 'required', 'active_period_id' => 'required', 'semester' => 'required', 'residence_session_id' => 'required', 'period_id' => 'required', 'gender' => 'required']);
        $residence_session_id = $request['residence_session_id'];
        $active_period_id = $request['active_period_id'];
        $gender = $request['gender'];
        $level=$request['level'];
        $semester=$request['semester'];
$period_id=$request['period_id'];
        $datenow = $datenow = Carbon::now();
        $newDate = Carbon::createFromFormat('Y-m-d H:i:s', $datenow)
            ->format('Y/m/d');

        $result = DB::select("SELECT
        tbl_rooms.room_id,
        tbl_rooms.room_gender,
        tbl_rooms.room_number,
        tbl_rooms.room_capacity,
        tbl_room_types.room_type,
        tbl_room_type_costs.room_price,
        tbl_floors.floor_name,
        tbl_hostels.hostel_name,
        tbl_locations.location_name
    FROM
        tbl_rooms
        INNER JOIN
        tbl_room_types
        ON
            tbl_rooms.room_type_id = tbl_room_types.room_type_id
        INNER JOIN
        tbl_room_type_costs
        ON
            tbl_room_types.room_type_id = tbl_room_type_costs.room_type_id
        INNER JOIN
        tbl_floors
        ON
            tbl_rooms.floor_id = tbl_floors.floor_id
        INNER JOIN
        tbl_hostels
        ON
            tbl_floors.hostel_id = tbl_hostels.hostel_id
        INNER JOIN
        tbl_locations
        ON
            tbl_hostels.location_id = tbl_locations.location_id
    WHERE
        tbl_rooms.hostel_id IN ((
            SELECT
                tbl_hostel_preference.hostel_id
            FROM
                tbl_hostel_preference
                INNER JOIN tbl_residence_sessions ON tbl_hostel_preference.residence_session_id = tbl_residence_sessions.residence_session_id
                INNER JOIN tbl_active_period_hostel_online_application ON tbl_residence_sessions.active_period_id = tbl_active_period_hostel_online_application.active_period_id
            WHERE
                tbl_residence_sessions.end_date > '$newDate' and
                tbl_residence_sessions.residence_session_id = $residence_session_id
                AND tbl_active_period_hostel_online_application.is_active = 1
                AND tbl_residence_sessions.available_status = 1
            )) AND
        tbl_rooms.floor_id IN ((
            SELECT
                tbl_hostel_preference.floor_id
            FROM
                tbl_hostel_preference
                INNER JOIN tbl_residence_sessions ON tbl_hostel_preference.residence_session_id = tbl_residence_sessions.residence_session_id
                INNER JOIN tbl_active_period_hostel_online_application ON tbl_residence_sessions.active_period_id = tbl_active_period_hostel_online_application.active_period_id
            WHERE
                tbl_residence_sessions.end_date > '$newDate'
                                and
                                tbl_residence_sessions. residence_session_id = $residence_session_id
                AND tbl_active_period_hostel_online_application.is_active = 1
                AND tbl_residence_sessions.available_status = 1
            )) AND
        tbl_room_type_costs.active_period_id = $active_period_id AND
        tbl_rooms.room_id NOT IN ((
            SELECT
                tbl_room_status.room_id
            FROM
                tbl_room_status
                INNER JOIN tbl_residence_sessions ON tbl_room_status.residence_session_id = tbl_residence_sessions.residence_session_id
            WHERE
                tbl_room_status.room_status <> 1
                AND tbl_room_status.residence_session_id = $residence_session_id
            )) AND
        tbl_rooms.room_id NOT IN ((
            SELECT
                tbl_resevations.room_id
            FROM
                tbl_resevations
            WHERE
            tbl_resevations.residence_session_id = $residence_session_id
        )) AND
        tbl_rooms.room_gender = '$gender'");

        return response()->json($result);
    }

    public function createRoomApplication(Request $request)
    {
        $request->validate([
            'room_id' => 'required', 'student_id' => 'required',
            'applied_by' => 'required',  'residence_session_id' => 'required', 'active_period_id' => 'required',
        ]);

        try {
            $residenceSessionId = $request['residence_session_id'];
            $room_id = $request['room_id'];
            $active_period_id = $request['active_period_id'];

            $matchconditions = ['residence_session_id' => $residenceSessionId, 'room_id' => $room_id];
            $roomoccupants = roomAllocation::where($matchconditions)->where('approved_status', '!=', '2')->count();

            $applicants = roomapplication::where($matchconditions)->where('application_status', '=', '0')->count();

            $room = room::where('room_id', $room_id)->first();
            $room_capacity = $room->room_capacity;

            if (($roomoccupants + $applicants) >= $room_capacity) {
                return response()->json(['message' => 'room is fully ocupied', 'success' => false], 500);
            }

            $room_application = new roomapplication();
            $room_application->room_id = $request['room_id'];
            $room_application->student_id = $request['student_id'];
            $room_application->applied_by = $request['applied_by'];
            $room_application->payment_status = $request['payment_status'];
            // 0 for pending 1 for approved, 2 for forfeited
            $room_application->application_status = '0';
            $date = Carbon::now();
            $date->addDays(5);
            $room_application->expiration_date = $date;
            $room_application->residence_session_id = $request['residence_session_id'];
            $room_application->reg_number = $request['reg_number'];
            $room_application->active_period_id = $request['active_period_id'];
            $room_application->save();

            $roomoccupants = roomAllocation::where($matchconditions)->count();

            $applicants = roomapplication::where($matchconditions)->where('application_status', '=', '0')->count();

            $room_capacity = room::where('room_id', $room_id)->first();
            $room_capacity = $room_capacity->room_capacity;

            $room_status['residence_session_id'] = $residenceSessionId;

            $room_status['room_id'] = $room_id;
            $room_status['active_period_id'] = $active_period_id;

            if (($roomoccupants + $applicants) >= $room_capacity) {
                $room_status['room_status'] = '0';
            } else {
                $room_status['room_status'] = '1';
            }



            $result = app('App\Http\Controllers\roomstatusController')->updateRoomStatus($room_status);

            if ($result == 1) {
                $activeperiod=active_period_hostel_online_application::find($active_period_id);

                $period_id=$activeperiod->period_id;

                $minimum_treshold_value = app('App\Http\Controllers\studentDetailsController')->getminimum_treshold($period_id);
                $room_cost = $request['room_cost'];

                $feesBalance = app('App\Http\Controllers\studentDetailsController')->getFeesBalance($room_application['reg_number']);
                $total_cost = (float) $minimum_treshold_value + $room_cost;

                if ($feesBalance >= $total_cost) {
                    return app('App\Http\Controllers\roomApplicationController')->proccessApplications($room_application);
                } else {
                    return response()->json(['message' => 'application saved successfully ', 'success' => true], 201);
                }
            } else {
                return $result;
            }
        } catch (QueryException $ex) {
            if ($ex->errorInfo[1] == 1062) {
                return response()->json(['message' => 'you have already applied for this session', 'success' => false], 403);
            } else {
                return response()->json(['message' => $ex->getMessage(), 'success' => false], 501);
            }
        }
    }

    public function proccessApplications($room_application)
    {

        $result = null;
        if (! is_null($room_application)) {
            $processresult = 'pending';

            $datenow = Carbon::now();
            $active_period_id = $room_application->active_period_id;
            $room_id = $room_application->room_id;

            $active_period=active_period_hostel_online_application::find($active_period_id);

            $period_id=$active_period->period_id;

            $minimum_treshold_value = app('App\Http\Controllers\studentDetailsController')->getminimum_treshold($period_id);

            try {


                // $minimum_treshold = minimumTreshold::where('active_period_id', $active_period_id)->get();
                // return response()->json(['message' => "pano tasvika", $active_period_id, 'success' => false], 201);
                // $minimum_treshold_value = $minimum_treshold[0]->minimum_threshhold;

                $room_cost = room::join('tbl_room_type_costs', 'tbl_room_type_costs.room_type_id', '=', 'tbl_rooms.room_type_id')
                    ->where('tbl_room_type_costs.active_period_id', $active_period_id)
                    ->where('tbl_rooms.room_id', $room_id)
                    ->first();

                $room_cost_value = $room_cost->room_price;

                $feesBalance = app('App\Http\Controllers\studentDetailsController')->getFeesBalance($room_application['reg_number']);
                $total_cost = (float) $minimum_treshold_value + $room_cost_value;
                $feesBalance = $feesBalance;

                // return response()->json(['message' => $feesBalance, 'success' => true], 201);
                if ($total_cost <= $feesBalance) {

                    // allocate room
                    $processresult = 'allocated application ';

                    app('App\Http\Controllers\roomAllocationController')->Autoalocation($room_application);
                    // updating room application
                    log::info([
                        'message' => 'approved application',
                        'application_id' => $room_application->room_allocation_application_id,
                        'regnumber' => $room_application->reg_number,

                    ]);
                    $result = [
                        'message' => 'approved application',
                        'application_id' => $room_application->room_allocation_application_id,
                        'regnumber' => $room_application->reg_number,

                    ];
                    $room_number = $room_application->room_number;

                    return response()->json([
                        'message' => 'congats you have been allocated room'.$room_number, 'success' => true,
                    ], 200);
                } else {

                    log::info([
                        'message' => 'stalled application',
                        'application_id' => $room_application->room_allocation_application_id,
                        'regnumber' => $room_application->reg_number,

                    ]);
                    $result = [
                        'message' => 'stalled application',
                        'application_id' => $room_application->room_allocation_application_id,
                        'regnumber' => $room_application->reg_number,

                    ];
                    $expiration_date = $room_application->expiration_date;

                    return response()->json([
                        'message' => 'Your application is pending until'.$expiration_date.' please make sure you make the payments ', 'success' => false,
                    ], 200);
                }

                log::info('process sucessfully done');
            } catch (QueryException $ex) {
                if ($ex->errorInfo[1] == 1062) {
                    $room_application->application_status = 1;
                    $room_application->update();
                    log::info([
                        'message' => 'student already alocated',
                        'application_id' => $room_application->room_allocation_application_id,
                        'regnumber' => $room_application->reg_number,

                    ]);
                    $result = [
                        'message' => 'student already alocated',
                        'application_id' => $room_application->room_allocation_application_id,
                        'regnumber' => $room_application->reg_number,

                    ];

                    return response()->json(['message' => 'you are already allocated a room for this period', 'success' => false], 403);
                } else {

                    log::info([
                        'message' => $ex->getMessage(),
                        'application_id' => $room_application->room_allocation_application_id,
                        'regnumber' => $room_application->reg_number,

                    ]);
                    $result = [
                        'message' => $ex->getMessage(),
                        'application_id' => $room_application->room_allocation_application_id,
                        'regnumber' => $room_application->reg_number,

                    ];

                    return response()->json(['message' => $ex->getMessage(), 'success' => false], 500);
                }
            }

            log::channel('custom')->info('nothing done');

            if (is_null($result)) {
                return $result;
            } else {
                return implode(' ', $result);
            }
        } else {
            $processresult = 'pending';

            $datenow = Carbon::now();
            $room_applications = roomapplication::where('application_status', 0)->get();
            foreach ($room_applications as $room_application) {
                try {
                    $active_period_id = $room_application->active_period_id;
                    $room_id = $room_application->room_id;
                    $residenceSessionId = $room_application->residence_session_id;

                    $room_cost = room::join('tbl_room_type_costs', 'tbl_room_type_costs.room_type_id', '=', 'tbl_rooms.room_type_id')
                        ->where('tbl_room_type_costs.active_period_id', $active_period_id)
                        ->where('tbl_rooms.room_id', $room_id)
                        ->first();

                    $room_cost_value = $room_cost->room_price;

                    $active_period=active_period_hostel_online_application::find($active_period_id);
                    $period_id=$active_period->period_id;
                    $minimum_treshold_value = app('App\Http\Controllers\studentDetailsController')->getminimum_treshold($period_id);

                    $feesBalance = app('App\Http\Controllers\studentDetailsController')->getFeesBalance($room_application['reg_number']);
                    $total_cost = (float) $minimum_treshold_value + $room_cost_value;
                    $feesBalance = $feesBalance + 900;
                    if ($room_application->expiration_date >= $datenow) {

                        if ($total_cost <= $feesBalance) {

                            // allocate room
                            $processresult = 'allocated application ';

                            app('App\Http\Controllers\roomAllocationController')->Autoalocation($room_application);
                            // updating room application
                            log::info([
                                'message' => 'approved application',
                                'application_id' => $room_application->room_allocation_application_id,
                                'regnumber' => $room_application->reg_number,

                            ]);
                            $result = [
                                'message' => 'approved application',
                                'application_id' => $room_application->room_allocation_application_id,
                                'regnumber' => $room_application->reg_number,

                            ];
                        } else {

                            log::info([
                                'message' => 'stalled application',
                                'application_id' => $room_application->room_allocation_application_id,
                                'regnumber' => $room_application->reg_number,

                            ]);
                            $result = [
                                'message' => 'stalled application',
                                'application_id' => $room_application->room_allocation_application_id,
                                'regnumber' => $room_application->reg_number,

                            ];
                        }
                    } elseif ($room_application->expiration_date <= $datenow) {
                        if ($total_cost <= $feesBalance) {
                            // allocate room
                            $processresult = 'allocated application ';

                            app('App\Http\Controllers\roomAllocationController')->Autoalocation($room_application);
                            // updating room application

                            log::info([
                                'message' => 'approved application',
                                'application_id' => $room_application->room_allocation_application_id,
                                'regnumber' => $room_application->reg_number,
                                'room_cost' => $room_cost_value,
                            ]);

                            $result = [
                                'message' => 'approved application',
                                'application_id' => $room_application->room_allocation_application_id,
                                'regnumber' => $room_application->reg_number,

                            ];
                        } else {

                            // foreit application
                            $room_application->application_status = 2;
                            $room_application->update();

                            $room_status['residence_session_id'] = $residenceSessionId;

                            $room_status['room_id'] = $room_id;
                            $room_status['active_period_id'] = $active_period_id;

                            $room_status['room_status'] = '1';

                            $result = app('App\Http\Controllers\roomstatusController')->updateRoomStatus($room_status);

                            log::info([
                                'message' => 'fofeited  application',
                                'application_id' => $room_application->room_allocation_application_id,
                                'regnumber' => $room_application->reg_number,

                            ]);
                            $result = [
                                'message' => 'fofeited  application',
                                'application_id' => $room_application->room_allocation_application_id,
                                'regnumber' => $room_application->reg_number,

                            ];
                        }
                    }

                    log::info('process sucessfully done');
                } catch (QueryException $ex) {
                    if ($ex->errorInfo[1] == 1062) {
                        $room_application->application_status = 1;
                        $room_application->update();
                        log::info([
                            'message' => 'student already alocated',
                            'application_id' => $room_application->room_allocation_application_id,
                            'regnumber' => $room_application->reg_number,

                        ]);
                        $result = [
                            'message' => 'student already alocated',
                            'application_id' => $room_application->room_allocation_application_id,
                            'regnumber' => $room_application->reg_number,

                        ];
                    } else {

                        log::info([
                            'message' => $ex->getMessage(),
                            'application_id' => $room_application->room_allocation_application_id,
                            'regnumber' => $room_application->reg_number,

                        ]);
                        $result = [
                            'message' => $ex->getMessage(),
                            'application_id' => $room_application->room_allocation_application_id,
                            'regnumber' => $room_application->reg_number,

                        ];
                    }
                }
            }
            log::channel('custom')->info('nothing done');

            if (is_null($result)) {
                return $result;
            } else {
                return implode(' ', $result);
            }
        }
    }

    public function getMyApplication(Request $request)
    {

        $regnumber = $request['regnumber'];
        $room_application = roomapplication::where('reg_number', '=', $regnumber)->orderby('residence_session_id', 'DESC')->get();

        return response()->json($room_application);
    }

    public function getStudentPendingApplications($regnumber)
    {
        $datenow = $datenow = Carbon::now();

        $application_pending = DB::select("SELECT
        COUNT( tbl_room_allocation_applications.room_allocation_application_id ) as count
    FROM
        tbl_room_allocation_applications
    WHERE
        tbl_room_allocation_applications.reg_number = '$regnumber'
        AND tbl_room_allocation_applications.expiration_date > '$datenow'
        AND tbl_room_allocation_applications.application_status <> 2");

        return $application_pending;
    }
}
