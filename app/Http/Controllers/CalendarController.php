<?php

namespace App\Http\Controllers;

use App\Calendar\CalendarDayView;
use App\Calendar\CalendarWeekView;
use App\Calendar\CalendarMonthView;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CalendarController extends Controller
{
    function show(Request $request, $type = 'month', $currentDate = null):View
    {
        $users = User::all();

        $carbonDate = $currentDate ? Carbon::createFromFormat('Y-m-d', $currentDate) : now();
                
        switch ($type) {
            case 'week':
                $calendar = new CalendarWeekView($carbonDate);
                $currentDate = $currentDate ?? $carbonDate->format('Y-m-d');
                break;
            case 'day':
                $calendar = new CalendarDayView($carbonDate);
                $currentDate = $currentDate ?? $carbonDate->format('Y-m-d');
                break;
            case 'month':
            default:
                $calendar = new CalendarMonthView($carbonDate);
                break;
        }

        // セッションに記録
        session([
            'calendar.back' => [
                'type' => $type,
                'currentDate' => $currentDate
            ]
        ]);

        return view('calendar.calendar', compact('users', 'calendar', 'type', 'currentDate'));
    }

}
