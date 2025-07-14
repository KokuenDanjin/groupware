<?php 
/**
* スケジュールをViewに渡すクラス
*
* @author Carlos
* @since 1.0.0
*/
namespace App\Schedule;

use Carbon\Carbon;

class ScheduleView {
    protected $schedule;
    function __construct($schedule)
    {
        $this->schedule = $schedule;
    }

    /**
    * スケジュールの日時を文字列で返すメソッド
    *
    * @param schedule $schedule スケジュールモデルのインスタンス
    *
    * @return string 日時情報の文字列 
    */
    static function dateRender($schedule = null):string
    {
        if (!$schedule) {
            return '';
        } 

        $timeType = $schedule->time_type;
        $start = Carbon::parse($schedule->start_date . '' . $schedule->start_time ?? '00:00');
        $end = Carbon::parse($schedule->end_date . '' . $schedule->end_time ?? '00:00');

        // 日時情報の文字列
        $startDate = $start->format('Y/m/d');
        $endDate = $end->format('Y/m/d');
        $startTime = $start->format('H:i');
        $endTime = $end->format('H:i');

        $dateString = [];
        if ($start->toDateString() === $end->toDateString()) {
            $dateString[] = $startDate;
            if ($timeType === 'normal') {
                $dateString[] = ' ' . $startTime . ' ～ ' . $endTime;
            }
        } else {
            $dateString[] = $startDate;
            if ($timeType === 'normal') $dateString[] = ' ' . $startTime;
            $dateString[] = ' ～ ';
            $dateString[] = $endDate;
            if ($timeType === 'normal') $dateString[] = ' ' . $endTime;
        }
        if ($timeType === 'all_day') $dateString[] = ' （終日予定）';
        if ($timeType === 'undecided') $dateString[] = ' （時間未定）'; 

        return implode('', $dateString);
    }
}