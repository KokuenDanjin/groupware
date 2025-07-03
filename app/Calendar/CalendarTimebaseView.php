<?php 
/**
* CalendarTimebaseViewクラス
* weekView、dayView用
*
* @author Carlos
* @since 1.0.0
*/

namespace App\Calendar;

use Carbon\Carbon;
use Illuminate\Support\Collection;

abstract class CalendarTimebaseView extends CalendarView {

    function __construct($date)
    {
        parent::__construct($date);
    }

    /**
    * カレンダーの時間軸の表示範囲を設定を取得するメソッド
    * 
    *
    * @param int $startTime 開始時間
    * @param int $endTime 終了時間
    *
    * @return array ['開始時間', '終了時間'] 
    */
    function getAvailabilityTime(int $startTime = 8, int $endTime = 20): array
    {
        return ['startTime' => $startTime, 'endTime' => $endTime];
    }

    /**
    * スケジュールパネルのポジションを計算するメソッド
    *
    * @param int $scheduleStartTime タイムライン表示の開始時間 h
    * @param int $scheduleEndTime  タイムライン表示の終了時間 h
    * @param int $timeType time_typeカラムの値　normal or all_day or undecided
    * @param string $startTimestamp スケジュールの開始時間 UNIXタイムスタンプ
    * @param string $endTimestamp スケジュールの終了時間 UNIXタイムスタンプ
    *
    * @return array パネルの位置、大きさ
    */
    function getSchedulePanelPosition(
        $scheduleStartTime,
        $scheduleEndTime,
        $timeType,
        $startTimestamp = null,
        $endTimestamp = null
    ):array
    {
        $timeHeight = 60;

        // スケジュールが表示時間外の場合の処理

        if ($timeType === 'normal') {
            // ベース時間（表示スケジュールの開始時間）
            $timelineTop = strtotime(date('Y-m-d', $startTimestamp) . sprintf('%02d00', $scheduleStartTime));
            $baseTime = $timelineTop - 3600; // 1行目は終日予定用なのでずらす

            $topPx = ($startTimestamp - $baseTime) / 60 * ($timeHeight / 60); // ベース時間からの分差
            $heightPx = ($endTimestamp - $startTimestamp) / 60 * ($timeHeight / 60); // スケジュールの時間（分）
        } elseif ($timeType === 'all_day') {
            $topPx = $timeHeight;
            $heightPx = ($scheduleEndTime - $scheduleStartTime + 1) * $timeHeight; // スケジュールの時間（分）
        } else {
            $topPx = 0; // タイムライン上部
            $heightPx = 16;
        };

        return [
            'top' => $topPx . 'px',
            'height' => $heightPx .'px',
            'width' => '100%'
        ];
    }

    /**
    * style属性に載せる形を生成するメソッド
    *
    * @return array 連想配列 [プロパティ => 値]
    */
    static function getStyleAttributeString($styles): string
    {
        return implode(' ', array_map(fn($prop, $val) => "{$prop}: {$val};", array_keys($styles), $styles));
    }

    /**
    * scheduleRenderを使用してスケジュールをレンダリングするメソッド
    *
    * @param string $date Y-m-d
    * @param int $days 何日間分のスケジュールかを指定
    *
    * @return Collection 日ごとにグループ化したスケジュール
    */
    function callScheduleRender($date, $days = 1):Collection
    {
        $startDate = Carbon::parse($date);
        $endDate = $startDate->copy()->addDay($days - 1);

        $groupedSchedules = $this->getGroupedSchedulesByDateRange(
            $startDate->toDateString(),
            $endDate->toDateString()
        );

        $rendered = [];

        foreach ($groupedSchedules as $dateKey => $schedules) {
            foreach ($schedules as $schedule) {
                $rendered[$dateKey][] = $this->scheduleRender($schedule);
            }
        }
        
        return collect($rendered);
    }

    /**
    * スケジュールをレンダリングするメソッド
    *
    * @param Collection scheduleテーブルのレコード1行
    *
    * @return string スケジュールのhtml
    */
    function scheduleRender($schedule): string
    {
        $timeType = $schedule->time_type;

        // パネル表示データ
        $scheduleContents = [];
        
        // カレンダーの時間の表示範囲
        $scheduleAvailabilityTime = $this->getAvailabilityTime();

        $paramForGetSchedulePanelPosition = [
            $scheduleAvailabilityTime['startTime'],
            $scheduleAvailabilityTime['endTime'],
            $timeType,
        ];
        if ($timeType === 'normal') {
            $scheduleStartTime = strtotime($schedule->start_date . ' ' . $schedule->start_time);
            $scheduleEndTime = strtotime($schedule->end_date . ' ' . $schedule->end_time);

            $scheduleContents[] = date('H:i', $scheduleStartTime) . '～' . date('H:i', $scheduleEndTime); // MM:DD～MM:DD

            $paramForGetSchedulePanelPosition[] = $scheduleStartTime;
            $paramForGetSchedulePanelPosition[] = $scheduleEndTime;
        } elseif ($timeType === 'all_day') {
            $scheduleContents[] = '終日';
        } else {
            $scheduleContents[] = '時間未定';
        };

        if ($schedule->schedule_category) $scheduleContents[] = $schedule->schedule_category->name . '：'; // カテゴリ
        $scheduleContents[] = $schedule->title; // タイトル

        $scheduleText = implode(' ', $scheduleContents);
        
        // Style
        $styles = $this->getSchedulePanelPosition(...$paramForGetSchedulePanelPosition);
        $styleAttribute = $this->getStyleAttributeString($styles);

        // 最終HTML
        $html = [];
        $html[] = trim('
            <div class="schedule-panel weekday-schedule-panel" style="' . $styleAttribute .'" data-schedule-id=' . e($schedule['id']) . '>
                <p class="schedule-text">' . $scheduleText . '</p>
            </div>
        ');

        return implode('', $html);
    }
}