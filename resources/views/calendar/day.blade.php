<div class="calendar-contents">
    <table class="calendar-navline">
        <tbody>
            <tr>
                <td>
                    <form class="calendar-userselect-form" action="">
                        <select name="" id="">
                            <option value="">ログインユーザー</option>
                        </select>
                    </form>
                </td>
                <td>
                    <div class="calendar-date-block">
                        <span>
                            <a class="calendar-move-button" href="{{ route('Calendar.view', ['type' => 'week', 'currentDate' => $Calendar->getBeforeWeek()]) }}">
                                <span class="material-symbols-outlined">keyboard_double_arrow_left</span>
                            </a>
                        </span>
                        <span>
                            <a class="calendar-move-button" href="{{ route('Calendar.view', ['type' => 'week', 'currentDate' => $Calendar->getBeforeDay()]) }}">
                                <span class="material-symbols-outlined">keyboard_arrow_left</span>
                            </a>
                        </span>
                        <span class="calendar-date">
                            <a href="{{ route('Calendar.view', ['type' => 'Week']) }}">{{ $Calendar->getCurrentDayString() }}</a>
                        </span>
                        <span>
                            <a class="calendar-move-button" href="{{ route('Calendar.view', ['type' => 'week', 'currentDate' => $Calendar->getNextDay()]) }}">
                                <span class="material-symbols-outlined">keyboard_arrow_right</span>
                            </a>
                        </span>
                        <span>
                            <a class="calendar-move-button" href="{{ route('Calendar.view', ['type' => 'week', 'currentDate' => $Calendar->getNextWeek()]) }}">
                                <span class="material-symbols-outlined">keyboard_double_arrow_right</span>
                            </a>
                        </span>
                    </div>
                </td>
                <td></td>
            </tr>
        </tbody>
    </table>
    
    <div class="calendar-main-calendar">{!! $Calendar->render() !!}</div>
</div>