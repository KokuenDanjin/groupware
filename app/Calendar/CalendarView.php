<?php 
/**
* CalendarViewクラス ※スーパークラス
*
* @author Carlos
* @since 1.0.0
*/

namespace App\Calendar;

use Carbon\Carbon;


abstract class CalendarView {
    protected $carbon;
    public static $weekMaps = [
        'ddd' => ['日', '月', '火', '水', '木', '金', '土'],
        'dddd' => ['日曜日', '月曜日', '火曜日', '水曜日', '木曜日', '金曜日', '土曜日']
    ];

    function __construct($date)
    {
        $this->carbon = new Carbon($date);
    }

    /**
    * 日付をY年n月d日(ddd)形式で取得するメソッド
    *
    * @return string 月（例：2025年6月16日(月)）
    */
    function getCurrentDayString(): string
    {
        return $this->carbon->format("Y年n月d日({$this->getDayOfWeek()})");
    }

    /**
    * 前日の日付をYmd形式で取得するメソッド
    *
    * @return string 前の日（例：20250615）
    */
    function getBeforeDay(): string
    {
        return $this->carbon->copy()->subDay()->format('Ymd');
    }

    /**
    * 翌日の日付をYmd形式で取得するメソッド
    *
    * @return string 次の日（例：20250617）
    */
    function getNextDay(): string
    {
        return $this->carbon->copy()->addDay()->format('Ymd');
    }

    /**
    * 1週間前の日付をYmd形式で取得するメソッド
    *
    * @return string 前の週（例：20250609）
    */
    function getBeforeWeek(): string
    {
        return $this->carbon->copy()->subWeek()->format('Ymd');
    }
    
    /**
    * 1週間後の日付をYmd形式で取得するメソッド
    *
    * @return string 次の週（例：20250623）
    */
    function getNextWeek(): string
    {
        return $this->carbon->copy()->addWeek()->format('Ymd');
    }

    /**
    * 前の月の1日をYmd形式で取得するメソッド
    *
    * @return string 前の月（例：20250501）
    */
    function getBeforeMonth(): string
    {
        return $this->carbon->copy()->startOfMonth()->subMonth()->format('Ymd');
    }
    
    /**
    * 次の月の1日Ymd形式で取得するメソッド
    *
    * @return string 次の月（例：20250701）
    */
    function getNextMonth(): string
    {
        return $this->carbon->copy()->startOfMonth()->addMonth()->format('Ymd');
    }

    /**
    * 曜日を返すメソッド
    * formatLocalizedは環境によってうまく動かない場合があるらしい
    *
    * @param string $$weekMapPattern 'ddd'(デフォルト):日、'dddd':日曜日
    *
    * @return string 曜日
    */
    function getDayOfWeek(string $pattern = 'ddd'): string
    {
        $i = $this->carbon->dayOfWeek();

        // weekMapパターンが存在しなかったらdddにする
        $weekMap = self::$weekMaps[$pattern] ?? self::$weekMaps['ddd'];

        return $weekMap[$i];
    }

    /**
    * スケジュールをレンダリングするメソッド
    *
    * @return string スケジュールのhtml
    */
    abstract function scheduleRender(): string;

}