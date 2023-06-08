<?php

use App\Http\Controllers\academicLevelControler;
use App\Http\Controllers\activePeriodsController;
use App\Http\Controllers\auths\authController;
use App\Http\Controllers\auths\studentAuthcontroller;
use App\Http\Controllers\blacklist;
use App\Http\Controllers\floorsController;
use App\Http\Controllers\hostelController;
use App\Http\Controllers\hostelPreferenceController;
use App\Http\Controllers\locationController;
use App\Http\Controllers\minimumTresholdController;
use App\Http\Controllers\periodController;
use App\Http\Controllers\resavationsController;
use App\Http\Controllers\residenceSessionsController;
use App\Http\Controllers\roomAllocationController;
use App\Http\Controllers\roomApplicationController;
use App\Http\Controllers\roomPreferenceController;
use App\Http\Controllers\roomsController;
use App\Http\Controllers\roomstatusController;
use App\Http\Controllers\roomTypeController;
use App\Http\Controllers\roomTypeCostController;
use App\Http\Controllers\studentDetailsController;
use App\Http\Controllers\userController;
use App\Http\Controllers\yearController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

//passport protected routes
Route::middleware('auth:api')->group(function () {
    // hostel routes
    Route::get('hostellist', [hostelController::class, 'getHostels']);
    Route::post('addhostel', [hostelController::class, 'createHostel']);
    Route::post('updatehostel', [hostelController::class, 'updateHostel']);
    Route::post('findhostels', [hostelController::class, 'findhostels']);
    Route::post('deleteHostel', [hostelController::class, 'deleteHostel']);

    // floors routes
    Route::post('addfloor', [floorsController::class, 'createFloor']);
    Route::post('getfloors', [floorsController::class, 'getFloors']);
    Route::post('getbatchfloors', [floorsController::class, 'getBatchFloors']);
    Route::post('deletefloor', [floorsController::class, 'deleteFloor']);
    //rooms routes
    Route::post('addroom', [roomsController::class, 'createRoom']);
    Route::post('getrooms', [roomsController::class, 'getRooms']);
    Route::post('searchRoom', [roomsController::class, 'searchRoom']);
    Route::post('updateRoom', [roomsController::class, 'updateRoom']);
    Route::post('deleteRoom', [roomsController::class, 'deleteRoom']);
    // location routes
    Route::get('getlocations', [locationController::class, 'getLocation']);
    Route::post('addlocations', [locationController::class, 'addlocation']);

    // active periods routes
    Route::post('createactiveperiod', [activePeriodsController::class, 'createActivePeriod']);
    Route::get('getactiveperiod', [activePeriodsController::class, 'getactiveperiod']);
    Route::post('updateactiveperiod', [activePeriodsController::class, 'updatePeriod']);
    Route::post('deactivatePeriod', [activePeriodsController::class, 'deactivatePeriod']);
    Route::post('activatePeriod', [activePeriodsController::class, 'activatePeriod']);
    Route::post('deletePeriod', [activePeriodsController::class, 'deletePeriod']);
    Route::get('getcurrentactiveperiod', [activePeriodsController::class, 'getcurrentactiveperiod']);
    //  periods routes from ext db
    Route::get('getperiods', [periodController::class, 'getPeriods']);
    // academic level routes
    Route::get('getacademiclevel', [academicLevelControler::class, 'getAcademicLevel']);
    Route::get('getyear', [yearController::class, 'getAcademicyear']);
    // batches routes
    Route::post('createBatch', [residenceSessionsController::class, 'createBatch']);
    Route::post('Batches', [residenceSessionsController::class, 'getBatches']);
    Route::get('Batchesall', [residenceSessionsController::class, 'getAllBatches']);
    Route::post('updateBatch', [residenceSessionsController::class, 'updateBatch']);
    Route::post('deactivateBatch', [residenceSessionsController::class, 'deactivateBatch']);
    Route::post('activateBatch', [residenceSessionsController::class, 'activateBatch']);
    Route::post('deleteBatch', [residenceSessionsController::class, 'deleteBatch']);
    // hostel preferencne routes
    Route::post('sethostelpreference', [hostelPreferenceController::class, 'setHostelPreference']);
    Route::post('deletehostelpreference', [hostelPreferenceController::class, 'deleteHostelPreference']);
    Route::post('gethostelpreference', [hostelPreferenceController::class, 'getHostelPreference']);
    Route::post('gethosteltoadd', [hostelPreferenceController::class, 'getHostelsToadd']);
    //room preferencen routes
    Route::post('getroompreference', [roomPreferenceController::class, 'getRoomPreference']);
    Route::post('setroompreference', [roomPreferenceController::class, 'createRoomPreference']);
    Route::post('deleteroompreference', [roomPreferenceController::class, 'deleteRoomPreference']);
    Route::post('getroomstoadd', [roomPreferenceController::class, 'getRoomsToAdd']);
    // room status routes
    Route::post('setroomstatus', [roomstatusController::class, 'updateRoomStatus']);

    //room costs routes

    Route::post('setroomtypecost', [roomTypeCostController::class, 'setRoomTypeCost']);
    Route::post('getRoomTypeCost', [roomTypeCostController::class, 'getRoomTypeCost']);

    //room type routes
    Route::post('createroomtype', [roomTypeController::class, 'createRoomType']);
    Route::post('updateroomtype', [roomTypeController::class, 'updateRoomType']);
    Route::post('deleteroomtype', [roomTypeController::class, 'deleteRoomType']);
    Route::get('getroomtypes', [roomTypeController::class, 'show']);

    // resavations Routes
    Route::apiResource('resavations', resavationsController::class)->only(['store', 'index']);
    Route::post('unreserve', [resavationsController::class, 'unReserve']);
    Route::post('findresevedrooms', [resavationsController::class, 'findResevation']);
    Route::post('getroomstoreserve', [resavationsController::class, 'getToomsToreserve']);

    // room allocation
    Route::post('roomallocation', [roomAllocationController::class, 'store']);
    Route::post('getroomallocation', [roomAllocationController::class, 'show']);
    Route::post('getroomstoallocate', [roomAllocationController::class, 'getroomstoallocate']);
    Route::post('approve_reject', [roomAllocationController::class, 'approve_reject']);

    //minimum treshold

    Route::post('gettreshold', [minimumTresholdController::class, 'show']);
    // user Route
    Route::post('activateUser', [userController::class, 'activateUser']);
    Route::post('deactivateUser', [userController::class, 'DeactivateUser']);
// blacklist routes

Route::apiResource('blaclist', blacklist::class)->only(['store', 'index']);
Route::post('removeblacklist', [blacklist::class, 'destroy']);
// auth routes
    Route::post('register', [authController::class, 'register']);
Route::get('getusers', [authController::class, 'getUsers']);
Route::post('changePassword', [authController::class, 'changePassword']);

//rights routes

Route::post('getrights', [userController::class, 'getrights']);

Route::post('createrights', [userController::class, 'createrights']);

Route::post('removeright', [userController::class, 'removeRight']);

});

Route::get('minimumfees', [studentDetailsController::class, 'getminimum_treshold']);
Route::get('isregistered', [studentDetailsController::class, 'is_blacklisted']);

    Route::middleware('auth:api-student')->group(function () {

        Route::post('getstudentBatches', [residenceSessionsController::class, 'getStudentBatches']);
          //room application routes
    Route::post('getmyrooms', [roomApplicationController::class, 'getmyrooms']);

    Route::post('createroomapplication', [roomApplicationController::class, 'createRoomApplication']);
    Route::post('getstudentallocation', [roomAllocationController::class, 'getstudentallocation']);
    }
);

// admin auth
Route::post('login', [authController::class, 'login']);
// student auth
Route::post('student-login', [studentAuthcontroller::class, 'login']);

Route::post('getbal', [studentDetailsController::class, 'getFeesBalance']);
