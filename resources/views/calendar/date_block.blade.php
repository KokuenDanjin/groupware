<div class="calendar-date-block">
    <span>
        <a class="calendar-move-button" href="{{ route('calendar.view', ['type' => $type, 'currentDate' => $calendar->getBeforeWeek()]) }}">
            <span class="material-symbols-outlined">keyboard_double_arrow_left</span>
        </a>
    </span>
    @if ($type !== 'month')
    <span>
        <a class="calendar-move-button" href="{{ route('calendar.view', ['type' => $type, 'currentDate' => $calendar->getBeforeDay()]) }}">
            <span class="material-symbols-outlined">keyboard_arrow_left</span>
        </a>
    </span>
    @endif
    <span class="calendar-date">
        <a href="{{ route('calendar.view', ['type' => $type, 'currentDate' => carbon\carbon::now()->format('Y-m-d') ]) }}">
            {{ $type === 'month' ? $calendar->getTitle() : $calendar->getCurrentDayString() }}
        </a>
    </span>
    @if ($type !== 'month')
    <span>
        <a class="calendar-move-button" href="{{ route('calendar.view', ['type' => $type, 'currentDate' => $calendar->getNextDay()]) }}">
            <span class="material-symbols-outlined">keyboard_arrow_right</span>
        </a>
    </span>
    @endif
    <span>
        <a class="calendar-move-button" href="{{ route('calendar.view', ['type' => $type, 'currentDate' => $calendar->getNextWeek()]) }}">
            <span class="material-symbols-outlined">keyboard_double_arrow_right</span>
        </a>
    </span>
</div>