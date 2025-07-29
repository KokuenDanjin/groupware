@vite(['resources/js/pages/calendar.js', 'resources/js/components/calendar/index.js'])
<x-app-layout>
    <div class="calendar-main">
        <div class="calendar__change-view calendar-container">
            <table>
                <tbody>
                    <tr>
                        <td>
                            <div class="calendar__change-view-title">スケジュール表示</div>
                        </td>
                        <td>
                            <div class="calendar__change-view-items">
                                @php
                                    $views = ['month' => '月', 'week' => '週', 'day' => '日'];
                                @endphp
                                @foreach($views as $viewType => $label)
                                    <div class="calendar__change-view-item {{ $type === $viewType ? 'calendar__change-view-item--active' : '' }}">
                                        @if($type === $viewType)
                                            <span>{{ $label }}</span>
                                        @else
                                            <a href="{{ route('calendar.view', ['type' => $viewType] ) . '?' . http_build_query(['userId' => $userId, 'currentDate' => $currentDate]) }}">{{ $label }}</a>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <hr>

        <div class="calendar__main-area calendar-container">
            <div class="calendar__contents">
                <table class="calendar__nav-area">
                    <tbody>
                        <tr>
                            <td>
                                <select name="user" id="participantUserSelect">
                                    @foreach ($allUsers as $user)
                                    <option value="{{ $user->id }}" {{ $user->id == $userId ? 'selected' : '' }}>{{ $user->name }}</option>
                                    @endforeach
                                </select>
                            </td>
                            <td>
                                @include('calendar.date_block', ['userId' => $userId, 'type' => $type, 'currentDate' => $currentDate])
                            </td>
                            <td></td>
                        </tr>
                    </tbody>
                </table>

                @if ($type === 'month')
                    @include('calendar.month', ['userId' => $userId , 'calendar' => $calendar])
                @else
                    @include('calendar.timebase', ['userId' => $userId , 'calendar' => $calendar])
                @endif

            </div>
        </div>
    </div>
</x-app-layout>