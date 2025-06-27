<?php

namespace App\Http\Controllers;

use App\Models\schedule;
use App\Models\schedule_category;
use App\Models\User;
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
        $categories = schedule_category::all();
        $users = User::all();

        $contents = compact('categories', 'users');

        if ($id) {
            $schedules = schedule::find($id);
            if(!$schedules) {
                abort(404, 'スケジュールが見つかりません');
            }
            $contents['schedules'] = $schedules;
            $contents['mode'] = 'edit'; 
            return view('schedule.scheduleEdit', $contents);
        } else {
            $contents['mode'] = 'create';
            return view('schedule.scheduleEdit', $contents);
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
