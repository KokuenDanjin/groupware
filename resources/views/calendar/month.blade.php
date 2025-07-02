<div class="calendar-contents">
    <table class="calendar-navarea">
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
                            <a class="calendar-move-button" href="{{ route('calendar.view', ['type' => 'month', 'currentDate' => $calendar->getBeforeMonth()]) }}">
                                <span class="material-symbols-outlined">keyboard_arrow_left</span>
                            </a>
                        </span>
                        <span class="calendar-date">
                            <a href="{{ route('calendar.view', ['type' => 'month']) }}">{{ $calendar->getTitle() }}</a>
                        </span>
                        <span>
                            <a class="calendar-move-button" href="{{ route('calendar.view', ['type' => 'month', 'currentDate' => $calendar->getNextMonth()]) }}">
                                <span class="material-symbols-outlined">keyboard_arrow_right</span>
                            </a>
                        </span>
                    </div>
                </td>
                <td></td>
            </tr>
        </tbody>
    </table>
    
    <div class="calendar-main-calendar">{!! $calendar->render() !!}</div>
</div>