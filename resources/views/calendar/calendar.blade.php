@vite(['resources/js/pages/calendar.js'])
<x-app-layout>
    <div class="calendar-main">
        <div class="calendar-changeView calendar-container">
            <table>
                <tbody>
                    <tr>
                        <td>
                            <div class="calendar-changeView-title">スケジュール表示</div>
                        </td>
                        <td>
                            <div class="calendar-changeView-items">
                                @if($type === 'month')
                                <div class="calendar-changeView-item"><span class="calendar-changeView-active">月</span></div>
                                <div class="calendar-changeView-item"><a href="{{ route('Calendar.view', ['type' => 'week', 'currentDate' => $currentDate] ) }}">週</a></div>
                                <div class="calendar-changeView-item"><a href="{{ route('Calendar.view', ['type' => 'day', 'currentDate' => $currentDate] ) }}">日</a></div>
                                @elseif ($type === 'week')
                                <div class="calendar-changeView-item"><a href="{{ route('Calendar.view', ['type' => 'month'] ) }}">月</a></div>
                                <div class="calendar-changeView-item"><span class="calendar-changeView-active">週</span></div>
                                <div class="calendar-changeView-item"><a href="{{ route('Calendar.view', ['type' => 'day', 'currentDate' => $currentDate] ) }}">日</a></div>
                                @elseif ($type === 'day')
                                <div class="calendar-changeView-item"><a href="{{ route('Calendar.view', ['type' => 'month'] ) }}">月</a></div>
                                <div class="calendar-changeView-item"><a href="{{ route('Calendar.view', ['type' => 'week', 'currentDate' => $currentDate] ) }}">週</a></div>
                                <div class="calendar-changeView-item"><span class="calendar-changeView-active">日</span></div>
                                @endif
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <hr>

        <div class="calendar-mainarea calendar-container">
            @if ($type === 'month')
                @include('calendar.month', ['calendar' => $Calendar])
            @elseif ($type === 'week')
                @include('calendar.week', ['calendar' => $Calendar])
            @elseif ($type === 'day')
                @include('calendar.day', ['calendar' => $Calendar])
            @endif
        </div>
    </div>
</x-app-layout>