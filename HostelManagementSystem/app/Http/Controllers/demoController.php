<?php

namespace App\Http\Controllers;


use App\Models\projector;

class demoController extends Controller
{
    //
    function index(){
return projector::all();
    }
}
