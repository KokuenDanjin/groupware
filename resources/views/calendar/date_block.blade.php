<div class="calendar-date-block">
    @php
        $baseQueryParams = ['userId' => $userId];
        $getQueryParam = fn($addingParam = []) => '?' . http_build_query([...$baseQueryParams, ...$addingParam]);
    @endphp
    <span>
        <a
            class="calendar-move-button"
            href="{{ route('calendar.view', ['type' => $type]) . $getQueryParam(['currentDate' => $type ==='month' ? \App\Calendar\CalendarView::getBeforeMonth($currentDate) : \App\Calendar\CalendarView::getBeforeWeek($currentDate)]) }}"
        >
            <span class="material-symbols-outlined">keyboard_double_arrow_left</span>
        </a>
    </span>
    @if ($type !== 'month')
    <span>
        <a
            class="calendar-move-button"
            href="{{ route('calendar.view', ['type' => $type]) . $getQueryParam(['currentDate' => \App\Calendar\CalendarView::getBeforeDay($currentDate)]) }}"
        >
            <span class="material-symbols-outlined">keyboard_arrow_left</span>
        </a>
    </span>
    @endif
    <span class="calendar-date">
        <a href="{{ route('calendar.view', ['type' => $type]) . $getQueryParam(['currentDate' => now()->toDateString('Y-m-d')]) }}">
            {{ $type === 'month' ? $calendar->getTitle() : $calendar->getCurrentDayString() }}
        </a>
    </span>
    @if ($type !== 'month')
    <span>
        <a
            class="calendar-move-button"
            href="{{ route('calendar.view', ['type' => $type]) . $getQueryParam(['currentDate' => \App\Calendar\CalendarView::getNextDay($currentDate)]) }}"
        >
            <span class="material-symbols-outlined">keyboard_arrow_right</span>
        </a>
    </span>
    @endif
    <span>
        <a
            class="calendar-move-button"
            href="{{ route('calendar.view', ['type' => $type]) . $getQueryParam(['currentDate' => $type ==='month' ? \App\Calendar\CalendarView::getNextMonth($currentDate) : \App\Calendar\CalendarView::getNextWeek($currentDate)]) }}"
        >
            <span class="material-symbols-outlined">keyboard_double_arrow_right</span>
        </a>
    </span>
</div>