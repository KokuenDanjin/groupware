<?php 
/**
* 週表示カレンダーをViewに渡すクラス
*
* @author Carlos
* @since 1.0.0
*/

namespace App\Calendar;

class CalendarWeekView extends CalendarTimebaseView {

    function __construct($date) 
    {
        parent::__construct($date);
    }

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
            <div class="calendar__main-weekday-calendar">
                <table class="calendar__main-table calendar__main-table--weekday">
                    <thead>
                        <tr>
                            <th></th>
        ');
        $week = new CalendarWeek($this->carbon);
        $days = $week->getDays($this->carbon);

        foreach ($days as $day) {
            $td_classes = [$day->getClassName()];
            if ($day->isToday()) $td_classes[] = "day--today"; // 「今日」のみクラスを追加
            if ($day->isSunday()) $td_classes[] = "header--sun";
            if ($day->isSaturday()) $td_classes[] = "header--sat";
            $html[] = '<th class="' . implode(" ", $td_classes) . '">';

            $html[] = trim('
                <div class="calendar__weekday-header-cell calendar__week-header-cell">
                <div class="spacer"></div>
            ');
            $html[] = $day->render("j({$day->getDayOfWeek()})"); // 'フォーマットを日付(曜日)に指定'

            $html[] = $this->getScheduleAddButton($day);
            $html[] = trim('
                    </div>
                </th>
            ');
        }
        $html[] = trim('
                    </tr>
                </thead>
            </table>
        ');

        return implode('', $html);
    }
}