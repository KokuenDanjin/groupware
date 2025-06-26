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

    function edit($id = null):view
    {
        if ($id) {
            return view('schedule.scheduleEdit', ['id' => $id, 'mode' => 'edit']);
        } else {
            return view('schedule.scheduleEdit', ['id' => $id, 'mode' => 'create']);
        };
    }

    function store():RedirectResponse
    {

        // routeに渡す値をセッションから取得
        $back = session('calendar.back', [
            'type' => null,
            'currentDate' => null
        ]);

        return redirect(route('calendar.view', $back));
    }

    function update():RedirectResponse
    {

        // routeに渡す値をセッションから取得
        $back = session('calendar.back', [
            'type' => null,
            'currentDate' => null
        ]);

        return redirect(route('calendar.view', $back));
    }
}
