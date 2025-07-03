@vite(['resources/js/pages/calendar.js', 'resources/js/components/calendar/index.js'])
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
                                <div class="calendar-changeView-item"><a href="{{ route('calendar.view', ['type' => 'week', 'currentDate' => $currentDate] ) }}">週</a></div>
                                <div class="calendar-changeView-item"><a href="{{ route('calendar.view', ['type' => 'day', 'currentDate' => $currentDate] ) }}">日</a></div>
                                @elseif ($type === 'week')
                                <div class="calendar-changeView-item"><a href="{{ route('calendar.view', ['type' => 'month'] ) }}">月</a></div>
                                <div class="calendar-changeView-item"><span class="calendar-changeView-active">週</span></div>
                                <div class="calendar-changeView-item"><a href="{{ route('calendar.view', ['type' => 'day', 'currentDate' => $currentDate] ) }}">日</a></div>
                                @elseif ($type === 'day')
                                <div class="calendar-changeView-item"><a href="{{ route('calendar.view', ['type' => 'month'] ) }}">月</a></div>
                                <div class="calendar-changeView-item"><a href="{{ route('calendar.view', ['type' => 'week', 'currentDate' => $currentDate] ) }}">週</a></div>
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
            <div class="calendar-contents">
                <table class="calendar-navarea">
                    <tbody>
                        <tr>
                            <td>
                                <form class="calendar-userselect-form" action="">
                                    <select name="user" id="user">
                                        @foreach ($users as $user)
                                        <option value="{{ $user->id }}" {{ $user->is(Auth::user()) ? 'selected' : '' }}>{{ $user->name }}</option>
                                        @endforeach
                                    </select>
                                </form>
                            </td>
                            <td>
                                @include('calendar.date_block', ['type' => $type, 'currentDate' => $currentDate])
                            </td>
                            <td></td>
                        </tr>
                    </tbody>
                </table>

                @if ($type === 'month')
                    @include('calendar.month', ['calendar' => $calendar])
                @else
                    @include('calendar.timebase', ['calendar' => $calendar])
                @endif

            </div>
        </div>
    </div>
</x-app-layout>