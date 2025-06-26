<?php 
/**
* 日表示カレンダーをViewに渡すクラス
*
* @author Carlos
* @since 1.0.0
*/

namespace App\Calendar;

class CalendarDayView extends CalendarTimebaseView {

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
            <div class="calendar-main-weekday-calendar">
                <table class="calendar-main-table calendar-main-table-weekday">
                    <thead>
                        <tr>
                            <th></th>
        ');

        $day = new CalendarWeekDay($this->carbon);

        $td_classes = [$day->getClassName()];
        if ($day->isToday()) $td_classes[] = "day-today"; // 「今日」のみクラスを追加
        if ($day->isSunday()) $td_classes[] = "header-sun";
        if ($day->isSaturday()) $td_classes[] = "header-sat";
        $html[] = '<th class="' . implode(" ", $td_classes) . '">';

        $html[] = trim('<div class="calendar-weekday-header-cell calendar-day-header-cell">');

        $html[] = $this->getScheduleAddButton($day);
        $html[] = trim('
                            </div>
                        </th>
                    </tr>
                </thead>
            </table>
        ');

        return implode('', $html);
    }
}