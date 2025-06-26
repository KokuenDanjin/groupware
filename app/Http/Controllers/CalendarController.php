<?php

namespace App\Http\Controllers;

use App\Calendar\CalendarDayView;
use App\Calendar\CalendarWeekView;
use App\Calendar\CalendarMonthView;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CalendarController extends Controller
{
    function show(Request $request, $type = 'month', $currentDate = null):View
    {
        $carbonDate = $currentDate ? Carbon::createFromFormat('Ymd', $currentDate) : now();
                
        switch ($type) {
            case 'week':
                $Calendar = new CalendarWeekView($carbonDate);
                break;
            case 'day':
                $Calendar = new CalendarDayView($carbonDate);
                break;
            case 'month':
            default:
                $Calendar = new CalendarMonthView($carbonDate);
                break;
        }

        // セッションに記録
        session([
            'calendar.back' => [
                'type' => $type,
                'currentDate' => $currentDate
            ]
        ]);

        return view('Calendar.calendar', compact('Calendar', 'type', 'currentDate'));
    }

}
