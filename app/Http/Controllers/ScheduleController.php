<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ScheduleController extends Controller
{
    function show($id):View
    {
        
        return view('schedule.scheduleShow', ['id' => $id]);
    }

    function store():RedirectResponse
    {
        return redirect('');
    }
}
