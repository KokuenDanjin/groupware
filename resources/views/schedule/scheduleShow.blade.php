@vite(['resources/js/pages/schedule.js'])
<x-app-layout>
    <div class="schedule-main">
        <div class="schedule-container">
            @include('schedule.components.back-to-calendar-button')
            <div class="schedule__title">{{ $schedule->category_id ? $schedule->category->name . '：' : '' }}{{ $schedule->title }}</div>
            <div class="schedule__detail">
                <table class="schedule__detail__table">
                    <tbody>
                        <tr>
                            <th>日時</th>
                            <td>{{ \App\Schedule\ScheduleView::dateRender($schedule) }}</td>
                        </tr>
                        <tr>
                            <th>参加者</th>
                            <td>
                                <div>{{ $schedule->users->pluck('name')->join(', ') ?: 'NoData' }}</div>
                            </td>
                        </tr>
                        <tr>
                            <th>メモ</th>
                            <td @class([
                                    'schedule__detail',
                                    'schedule__detail__no-data' => empty($schedule->memo)
                                ])>
                                <div>{!! nl2br(e($schedule->memo) ?: 'なし') !!}</div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <ul class="schedule__nav">
                <li>
                    <a class="schedule__nav__button schedule__nav__button-edit" href="{{ route('schedule.edit', ['id' => $schedule->id]) }}">
                        <span class="material-symbols-outlined schedule__nav__icon">edit_document</span>
                        <span>変更</span>
                    </a>
                </li>
                <li>
                    <a class="schedule__nav__button schedule__nav__button-delete">
                        <span class="material-symbols-outlined schedule__nav__icon">delete</span> 
                        <span>削除</span>
                    </a>
                </li>
            </ul>
        </div>
    </div>
</x-app-layout>