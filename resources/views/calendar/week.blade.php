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
                            <a class="calendar-move-button" href="{{ route('calendar.view', ['type' => 'week', 'currentDate' => $calendar->getBeforeWeek()]) }}">
                                <span class="material-symbols-outlined">keyboard_double_arrow_left</span>
                            </a>
                        </span>
                        <span>
                            <a class="calendar-move-button" href="{{ route('calendar.view', ['type' => 'week', 'currentDate' => $calendar->getBeforeDay()]) }}">
                                <span class="material-symbols-outlined">keyboard_arrow_left</span>
                            </a>
                        </span>
                        <span class="calendar-date">
                            <a href="{{ route('calendar.view', ['type' => 'week', 'currentDate' => carbon\carbon::now()->format('Ymd') ]) }}">{{ $calendar->getCurrentDayString() }}</a>
                        </span>
                        <span>
                            <a class="calendar-move-button" href="{{ route('calendar.view', ['type' => 'week', 'currentDate' => $calendar->getNextDay()]) }}">
                                <span class="material-symbols-outlined">keyboard_arrow_right</span>
                            </a>
                        </span>
                        <span>
                            <a class="calendar-move-button" href="{{ route('calendar.view', ['type' => 'week', 'currentDate' => $calendar->getNextWeek()]) }}">
                                <span class="material-symbols-outlined">keyboard_double_arrow_right</span>
                            </a>
                        </span>
                    </div>
                </td>
                <td></td>
            </tr>
        </tbody>
    </table>
    
    <div class="calendar-main-calendar">
        {!! $calendar->render() !!}
        <table class="calendar-main-table calendar-main-table-weekday calendar-main-table-weekday-data">
            <tbody>
                <tr>
                    @for ($row = 0; $row < 8; $row++)
                        <td>
                            <div class="calendar-daily calendar-cell {{ ($row === 0 ? 'time-cell' : 'schedule-cell') }}">
                                @if ($row !== 0)
                                    {!! $calendar->scheduleRender() !!}
                                @endif
                            </div>
                            @for ($col = 8; $col < 21; $col++)
                                @php
                                    $clasess = ['calendar-hour', 'calendar-cell'];
                                    $clasess[] = $row === 0 ? 'time-cell' : 'schedule-cell';
                                @endphp
                                <div class="{{ implode(' ', $clasess) }}" {{ $row != 0 ? "week-row-num=$row" : "" }} data-hour="{{ $col }}">
                                    @if ($row === 0)
                                        {{ $col }}
                                    @endif
                                </div>
                            @endfor
                        </td>
                    @endfor
                </tr>
            </tbody>
        </table>
    </div>
</div>