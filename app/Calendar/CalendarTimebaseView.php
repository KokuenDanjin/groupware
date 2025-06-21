<?php 
/**
* CalendarTimebaseViewクラス
* weekView、dayView用
*
* @author Carlos
* @since 1.0.0
*/

namespace App\Calendar;

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
    * @param int $scheduleStartTime 表示スケジュールの開始時間 h
    * @param int $scheduleEndTime  表示スケジュールの終了時間 h
    * @param string $startTimestamp スケジュールの開始時間 UNIXタイムスタンプ
    * @param string $endTimestamp スケジュールの終了時間 UNIXタイムスタンプ
    *
    * @return array パネルの位置、大きさ
    */
    function getSchedulePanelPosition($scheduleStartTime, $scheduleEndTime, $startTimestamp, $endTimestamp):array
    {
        // ベース時間（表示スケジュールの開始時間）
        $baseTime = strtotime(date('Ymd', $startTimestamp) . sprintf('%02d00', $scheduleStartTime)) - 3600 ; // 1行目は終日予定用なのでずらす
        // 終日予定の場合の処理

        // スケジュールが表示時間外の場合の処理

        $minutesFromBase = ($startTimestamp - $baseTime) / 60; // ベース時間からの分差
        $durationMinutes = ($endTimestamp - $startTimestamp) / 60; // スケジュールの時間（分）

        return [
            'top' => $minutesFromBase . 'px',
            'height' => $durationMinutes . 'px',
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

        $scheduleAvailabilityTime = $this->getAvailabilityTime();
        
        $scheduleStartTime = strtotime($schedule['startTime']);
        $scheduleEndTime = strtotime($schedule['endTime']);

        $styles = $this->getSchedulePanelPosition(
            $scheduleAvailabilityTime['startTime'],
            $scheduleAvailabilityTime['endTime'],
            $scheduleStartTime,
            $scheduleEndTime
        );
        $styleAttribute = $this->getStyleAttributeString($styles);

        $scheduleContents = [];
        $scheduleContents[] = date('H:i', $scheduleStartTime) . '～' . date('H:i', $scheduleEndTime); // MM:DD～MM:DD
        if ($schedule['categoryId']) $scheduleContents[] = $schedule['categoryId'] . '：'; // カテゴリ
        $scheduleContents[] = $schedule['title']; // タイトル

        $scheduleText = implode(' ', $scheduleContents);

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