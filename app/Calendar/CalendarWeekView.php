<?php 
/**
* 週表示カレンダーをViewに渡すクラス
*
* @author Carlos
* @since 1.0.0
*/

namespace App\Calendar;

class CalendarWeekView extends CalendarView {

    function __construct($date) 
    {
        parent::__construct($date);
    }

    /**
    * カレンダーの時間軸の表示範囲を設定するメソッド
    * 
    *
    * @param int $startTime 開始時間
    * @param int $endTime 終了時間
    *
    * @return array ['開始時間', '終了時間'] 
    */
    // function setAvailabilityTime(int $startTime = 8, int $endTime = 20): array
    // {
    //     return ['startTime' => $startTime, 'endTime' => $endTime];
    // }

    /**
    * カレンダーをレンダリングするメソッド
    *
    * @return string カレンダーのhtml
    */
    function render(): string
    {
        $html = [];
        // ヘッダ
        $html[] = trim('
            <div class="calendar-main-weekday-calendar">
                <table class="calendar-main-table calendar-main-table-weekday">
                    <thead>
                        <tr>
                            <th></th>
            ');
        $week = new CalendarWeek($this->carbon);
        $days = $week->getDays($this->carbon);

        foreach ($days as $day) {
            $td_classes = [$day->getClassName()];
            if ($day->isToday()) $td_classes[] = "day-today"; // 「今日」のみクラスを追加
            if ($day->isSunday()) $td_classes[] = "header-sun";
            if ($day->isSaturday()) $td_classes[] = "header-sat";
            $html[] = '<th class="' . implode(" ", $td_classes) . '">';

            $html[] = trim('
                <div class="calendar-weekday-header-cell">
                <div class="spacer"></div>
                ');
            $html[] = $day->render("j({$day->getDayOfWeek()})"); // 'フォーマットを日付(曜日)に指定'

            $html[] = trim('
                                <a href="#">
                                    <span class="schedule-add-button material-symbols-outlined">edit_square</span>
                                </a>
                            </div>
                        </th>');
            }
        $html[] = trim('
                    </tr>
                </thead>
            </table>
            ');

        return implode('', $html);
    }
}