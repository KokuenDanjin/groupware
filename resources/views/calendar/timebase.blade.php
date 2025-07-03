<div class="calendar-main-calendar">
    {!! $calendar->render() !!}
    <table class="calendar-main-table calendar-main-table-weekday calendar-main-table-weekday-data">
        <tbody>
            <tr>
                @php
                    $showDays = $type === 'week' ? 7 : 1;
                    $daysSchedules = $calendar->callScheduleRender($currentDate, $showDays);
                @endphp
                @for ($col = 0; $col <= $showDays; $col++)
                    <td>
                        <div class="calendar-daily calendar-cell {{ ($col === 0 ? 'time-cell' : 'schedule-cell') }}">
                            @if ($col !== 0)
                                @php
                                    $colDate = Carbon\Carbon::createFromFormat('Y-m-d', $currentDate)->addDay($col - 1);
                                    $schedules = $daysSchedules->get($colDate->toDateString(), []);
                                @endphp
                                @foreach( $schedules as $schedule)
                                    {!! $schedule !!}
                                @endforeach
                            @endif
                        </div>
                        @php
                            $availabilityTime = $calendar->getAvailabilityTime();
                            $startTime = $availabilityTime['startTime'];
                            $endTime = $availabilityTime['endTime'] + 1;
                        @endphp
                        @for ($row = $startTime; $row < $endTime; $row++)
                            @php
                                $clasess = ['calendar-hour', 'calendar-cell'];
                                $clasess[] = $col === 0 ? 'time-cell' : 'schedule-cell';
                            @endphp
                            <div class="{{ implode(' ', $clasess) }}" {{ $col != 0 ? "week-col-num=$col" : "" }} data-hour="{{ $row }}">
                                @if ($col === 0)
                                    {{ $row }}
                                @endif
                            </div>
                        @endfor
                    </td>
                @endfor
            </tr>
        </tbody>
    </table>
</div>
