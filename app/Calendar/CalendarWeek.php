<?php
/**
* 週を扱うクラス
*
* @author Carlos
* @since 1.0.0
*/

namespace App\Calendar;

use Carbon\Carbon;

class CalendarWeek {
    protected $carbon;
    protected $index = 0;

    function __construct($date, $index = 0)
    {
        $this->carbon = new Carbon($date);
        $this->index = $index;
    }

    /**
    * クラス名を生成するメソッド
    *
    * @return string クラス名（例：week-1）
    */
    function getClassName(): string
    {
        return 'week-' . $this->index;
    }

    /**
    * 1週間分の日を配列で取得するメソッド
    *
    * @param Carbon $startDay 週の最初の日とする日付のCarbonクラスインスタンス（省略で呼び出したインスタンスの週の日曜日）
    *
    * @return array CalendarWeekDayクラスのインスタンスを要素に持つ配列（1週間分）
    */
    function getDays(Carbon $startDay = null): array
    {
        $days = [];

        // 開始日
        $startDay = $startDay ?? $this->carbon->copy()->startOfWeek();

        // $startDayから1週間分
        $tmpDay = $startDay->copy();
        for ($i = 0; $i < 7; $i++) {
            $days[] = new CalendarWeekDay($tmpDay->copy());
            $tmpDay->addDay();
        }

        return $days;
    }

}