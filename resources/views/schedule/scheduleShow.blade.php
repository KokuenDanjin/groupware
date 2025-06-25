@vite(['resources/js/pages/schedule.js'])
<x-app-layout>
    <div class="schedule-main">
        <div class="schedule-container">
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
                    <button class="schedule__nav__button schedule__nav__button-edit" type="button">
                        <span class="material-symbols-outlined schedule__nav__icon">edit_document</span>
                        <span>変更</span>
                    </button>
                </li>
                <li>
                    <button class="schedule__nav__button schedule__nav__button-delete" type="button">
                        <span class="material-symbols-outlined schedule__nav__icon">delete</span> 
                        <span>削除</span>
                    </button>
                </li>
            </ul>
        </div>
    </div>
</x-app-layout>