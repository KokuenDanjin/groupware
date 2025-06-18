<?php
/**
* 日を扱うクラス
*
* @author Carlos
* @since 1.0.0
*/

namespace App\Calendar;

use Carbon\Carbon;

class CalendarWeekDay {
    protected $carbon;

    function __construct($date)
    {
        $this->carbon = new Carbon($date);
    }

    /**
    * クラス名を生成するメソッド
    *
    * @return string クラス名（例：day-sun）
    */
    function getClassName():string
    {
        return 'day-' . strtolower($this->carbon->format('D'));
    }

    /**
    * carbonが今日かどうかを返すメソッド
    *
    * @return bool 今日ならtrue
    */
    function isToday():bool
    {
        return $this->carbon->isSameDay(carbon::today());
    }

    /**
    * carbonが日曜かどうかを返すメソッド
    *
    * @return bool 日曜ならtrue
    */
    function isSunday():bool
    {
        return $this->carbon->isSunday();
    }

    /**
    * carbonが土曜かどうかを返すメソッド
    *
    * @return bool 土曜ならtrue
    */
    function isSaturday():bool
    {
        return $this->carbon->isSaturday();
    }

    /**
    * 曜日をddd形式で返すメソッド
    *
    * @return string 曜日（例：日）
    */
    function getDayOfWeek(): string
    {
        $weekMaps = CalendarView::$weekMaps;
        return $weekMaps['ddd'][$this->carbon->dayOfWeek()];
    }

    /**
    * 1日をレンダリングするメソッド
    *
    * @param string $format 日付の表示フォーマット（Carbonのformarメソッドに準拠）
    *
    * @return string htmlのpタグ
    */
    function render(string $format = null): string
    {
        $data = $this->carbon->format($format ?? 'j');
        return '<p class="day">' . $data . '</p>';
    }
}