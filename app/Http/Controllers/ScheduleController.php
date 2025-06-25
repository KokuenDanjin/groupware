<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use Illuminate\View\View;

class ScheduleController extends Controller
{
    function show():View
    {
        $id = 1;
        return view('schedule.scheduleShow', ['id' => $id]);
    }
}
