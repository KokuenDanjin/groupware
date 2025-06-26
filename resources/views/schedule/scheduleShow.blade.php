@vite(['resources/js/pages/schedule.js'])
<x-app-layout>
    <div class="schedule-main">
        <div class="schedule-container">
            @include('schedule.components.back-to-calendar-button')
            <div class="schedule__title">買い物：カネスエ</div>
            <div class="schedule__detail">
                <table class="schedule__detail__table">
                    <tbody>
                        <tr>
                            <th>日時</th>
                            <td>2025/06/25（水）　12:30 ～ 14:20</td>
                        </tr>
                        <tr>
                            <th>参加者</th>
                            <td>
                                <div>レオン・S・ケネディ</div>
                            </td>
                        </tr>
                        <tr>
                            <th>メモ</th>
                            <td class="schedule__detail__no-data">なし</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <ul class="schedule__nav">
                <li>
                    <a class="schedule__nav__button schedule__nav__button-edit" href="{{ route('schedule.edit', ['id' => $id]) }}">
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