<?php

namespace App\Http\Controllers;

use App\Calendar\CalendarDayView;
use App\Calendar\CalendarWeekView;
use App\Calendar\CalendarMonthView;

use Carbon\Carbon;
use Illuminate\Http\Request;

class CalendarController extends Controller
{
    function show(Request $request, $type = 'month', $currentDate = null)
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

        return view('Calendar.calendar', [
            'Calendar' => $Calendar,
            'type' => $type,
            'currentDate' => $currentDate
        ]);
    }

}
