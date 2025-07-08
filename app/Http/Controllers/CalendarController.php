<?php

namespace App\Http\Controllers;

use App\Calendar\CalendarDayView;
use App\Calendar\CalendarWeekView;
use App\Calendar\CalendarMonthView;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class CalendarController extends Controller
{
    function show(Request $request, $type = 'month'):View
    {
        $userId = $request->query('userId', Auth::id());
        $currentDate = $request->query('currentDate', Carbon::now()->format('Y-m-d'));

        $allUsers = User::all();

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
            'calendar.back' => compact('userId', 'type', 'currentDate')
        ]);

        return view('calendar.calendar', compact(
            'userId',
            'allUsers',
            'calendar',
            'type',
            'currentDate'
        ));
    }

}
