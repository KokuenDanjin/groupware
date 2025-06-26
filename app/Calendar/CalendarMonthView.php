<?php 
/**
* 月表示カレンダーをViewに渡すクラス
*
* @author Carlos
* @since 1.0.0
*/

namespace App\Calendar;

class CalendarMonthView extends CalendarView {

    function __construct($date) 
    {
        parent::__construct($date);
    }

    /**
    * 月をY年n月形式で取得するメソッド
    *
    * @return string 月（例：2025年6月）
    */
    function getTitle(): string {
        return $this->carbon->format('Y年n月');
    }

    /**
    * 1ヶ月分の週を配列で取得するメソッド
    *
    * @return array CalendarWeekクラスのインスタンスを要素に持つ配列（1ヶ月分）
    */
    protected function getWeeks():array
    {
        $weeks = [];

        // 初日
        $firstDay = $this->carbon->copy()->firstOfMonth();

        // 月末
        $lastDay = $this->carbon->copy()->lastOfMonth();

        // 1週目
        $week = new CalendarWeek($firstDay->copy());
        $weeks[] = $week;

        $tmpDay = $firstDay->copy()->addDay(7)->startOfWeek();

        while ($tmpDay->lte($lastDay)) {
            $week = new CalendarWeek($tmpDay, count($weeks));
            $weeks[] = $week;

            // 翌週へ（インクリメント）
            $tmpDay->addDay(7);
        }

        return $weeks;
    }

    /**
    * スケジュールをレンダリングするメソッド
    *
    * @return string スケジュールのhtml
    */
    function scheduleRender(): string
    {
        // 仮データ
        $schedule = [
            'id' => 1,
            'title' => 'カネスエ',
            'categoryId' => null,
            'startTime' => '202506201230',
            'endTime' => '202506201410',
        ];

        $scheduleContents = [];
        if ($schedule['categoryId']) $scheduleContents[] = $schedule['categoryId'] . '：'; // カテゴリ
        $scheduleContents[] = $schedule['title']; // タイトル

        $scheduleText = implode(' ', $scheduleContents);

        $html = [];

        $html[] = trim('
            <div class="schedule-panel" data-schedule-id=' . e($schedule['id']) . '>
                <p class="schedule-text">' . $scheduleText . '</p>
            </div>
        ');

        return implode('', $html);
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
            <div class="calendar-main-month-calendar">
                <table class="calendar-main-table calendar-main-table-month">
                    <thead>
                        <tr>
                            <th class="header-sun">日</th>
                            <th class="header-mon">月</th>
                            <th class="header-tue">火</th>
                            <th class="header-wed">水</th>
                            <th class="header-thu">木</th>
                            <th class="header-fri">金</th>
                            <th class="header-sat">土</th>
                        </tr>
                    </thead>
                    <tbody>
        ');

        // データ
        $weeks = $this->getWeeks();
        foreach ($weeks as $week) {
            $html[] = '<tr class="' . $week->getClassName() . '">';
            $days = $week->getDays();
            foreach ($days as $day) {
                
                $td_classes = [$day->getClassName()];
                if ($day->isToday()) $td_classes[] = "day-today"; // 「今日」のみクラスを追加
                $html[] = '<td class="' . implode(" ", $td_classes) . '">';
                $html[] = trim('
                    <div class="day-cell">
                        <div class="calendar-dateblock">
                ');
                $html[] = $day->render();

                $html[] = $this->getScheduleAddButton($day);
                $html[] = trim('            
                    </div>
                    <div class="calendar-schedule-area">
                ');
                
                // ----スケジュールのレンダリングエリア----
                $html[] = $this->scheduleRender();
                

                $html[] = trim('
                            </div>
                        </div>
                    </td>
                ');
            }
            $html[] = trim('</tr>');
        }
        $html[] = trim('
                    </tbody>
                </table>
            </div>
        ');

        return implode('', $html);
    }
}