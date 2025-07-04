<div class="calendar-date-block">
    @php
        $queryParam = '?' . http_build_query(['userId' => $userId]);
    @endphp
    <span>
        <a class="calendar-move-button" href="{{ route('calendar.view', ['type' => $type, 'currentDate' => $calendar->getBeforeWeek()]) . $queryParam }}">
            <span class="material-symbols-outlined">keyboard_double_arrow_left</span>
        </a>
    </span>
    @if ($type !== 'month')
    <span>
        <a class="calendar-move-button" href="{{ route('calendar.view', ['type' => $type, 'currentDate' => $calendar->getBeforeDay()]) . $queryParam }}">
            <span class="material-symbols-outlined">keyboard_arrow_left</span>
        </a>
    </span>
    @endif
    <span class="calendar-date">
        <a href="{{ route('calendar.view', ['type' => $type, 'currentDate' => \Carbon\Carbon::now()->format('Y-m-d') ]) . $queryParam }}">
            {{ $type === 'month' ? $calendar->getTitle() : $calendar->getCurrentDayString() }}
        </a>
    </span>
    @if ($type !== 'month')
    <span>
        <a class="calendar-move-button" href="{{ route('calendar.view', ['type' => $type, 'currentDate' => $calendar->getNextDay()]) . $queryParam }}">
            <span class="material-symbols-outlined">keyboard_arrow_right</span>
        </a>
    </span>
    @endif
    <span>
        <a class="calendar-move-button" href="{{ route('calendar.view', ['type' => $type, 'currentDate' => $calendar->getNextWeek()]) . $queryParam }}">
            <span class="material-symbols-outlined">keyboard_double_arrow_right</span>
        </a>
    </span>
</div>