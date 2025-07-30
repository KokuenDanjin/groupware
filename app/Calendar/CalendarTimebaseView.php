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
            $timelineBottom = strtotime(date('Y-m-d', $startTimestamp) . sprintf('%02d00', $scheduleEndTime + 1));

            // 完全に表示時間外の場合
            if ($endTimestamp < $timelineTop || $startTimestamp > $timelineBottom) {
                return [
                    'top' => '0px',
                    'height' => '16px',
                    'width' => '100%'
                ];
            }

            // はみ出す部分を切り詰める
            $startTimestamp = max($startTimestamp, $timelineTop);
            $endTimestamp = min($endTimestamp, $timelineBottom);

            // 1行目は時間未定予定用なのでずらす
            $baseTime = $timelineTop - 3600;
            
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
     * 日をまたぐスケジュールを分割するメソッド
     *
     * @param Schedule $schedule インスタンス
     * @return array 分割されたスケジュールの配列
     */
    function splitMultiDaySchedule($schedule): array
    {
        $start = Carbon::parse($schedule->start_date . ' ' . $schedule->start_time ?? '00:00:00');
        $end = Carbon::parse($schedule->end_date . ' ' . $schedule->end_time ?? '00:00:00');

        // 同日ならそのまま返す
        if ($start->isSameDay($end)) {
            // 元スケジュールの日時情報を保持
            $schedule->original_start = $start;
            $schedule->original_end = $end;
            return [$schedule];
        }

        $splits = [];
        $current = $start->copy()->startOfDay();

        while ($current->lte($end)) {
            $dailyDate = $current->toDateString();

            if ($schedule->time_type === 'all_day') {
                $dailyStart = '00:00';
                $dailyEnd = '24:00';
            } else {
                if ($current->isSameDay($start)) {
                    $dailyStart = $start->format('H:i');
                    $dailyEnd = '24:00';
                } elseif ($current->isSameDay($end)) {
                    $dailyStart = '00:00';
                    $dailyEnd = $end->format('H:i');
                } else {
                    $dailyStart = '00:00';
                    $dailyEnd = '24:00';
                }
            }

            $splits[] = (object)[
                'id' => $schedule->id,
                'title' => $schedule->title,
                'time_type' => $schedule->time_type,
                'schedule_category' => $schedule->schedule_category,
                'start_date' => $dailyDate,
                'end_date'   => $dailyDate,
                'start_time' => $dailyStart,
                'end_time'   => $dailyEnd,
                'original_start' => $start,
                'original_end'   => $end,
            ];

            $current->addDay();
        }

        return $splits;
    }

    /**
    * scheduleRenderを使用してスケジュールをレンダリングするメソッド
    *
    * @param int $userId 参加者として登録されているユーザー
    * @param string $date Y-m-d
    * @param int $days 何日間分のスケジュールかを指定
    *
    * @return Collection 日ごとにグループ化したスケジュール
    */
    function callScheduleRender($userId, $date, $days = 1):Collection
    {
        $startDate = Carbon::parse($date);
        $endDate = $startDate->copy()->addDay($days - 1);

        $groupedSchedules = $this->getGroupedSchedulesByDateRange([
            'start' => $startDate->toDateString(),
            'end' => $endDate->toDateString(),
            'userId' => $userId
        ]);

        $rendered = [];

        foreach ($groupedSchedules as $dateKey => $schedules) {
            foreach ($schedules as $schedule) {
                $splitSchedules = $this->splitMultiDaySchedule($schedule);
                foreach ($splitSchedules as $splitSchedule) {
                    $splitDate = $splitSchedule->start_date;
                    $rendered[$splitDate][] = $this->scheduleRender($splitSchedule);
                }
            }
        }
        return collect($rendered);
    }

    /**
    * スケジュールをレンダリングするメソッド
    *
    * @param Schedule $schedule インスタンス
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

        $startTime = $schedule->original_start ?? Carbon::parse($schedule->start_date . ' ' . $schedule->start_time);
        $endTime = $schedule->original_end ?? Carbon::parse($schedule->end_date . ' ' . $schedule->end_time);

        // 日付情報のテキスト用（時間指定or終日）
        $formatTime = function($startTime, $endTime, $timeType): string
        {
            if($timeType ==='all_day') {
                return $startTime->isSameDay($endTime)
                    ? '終日'
                    : $startTime->format('m/d') . ' ～ ' . $endTime->format('m/d');
            }
            if ($timeType === 'undecided') {
                return $startTime->isSameDay($endTime)
                    ? '時間未定'
                    : $startTime->format('m/d') . ' ～ ' . $endTime->format('m/d') . ' 時間未定';
            }

            return $startTime->isSameDay($endTime)
                ? $startTime->format('H:i') . ' ～ ' . $endTime->format('H:i')
                : $startTime->format('m/d H:i') . ' ～ ' . $endTime->format('m/d H:i');
        };

        $scheduleContents[] = $formatTime($startTime, $endTime, $timeType);

        if ($timeType === 'normal') {
            $paramForGetSchedulePanelPosition[] = strtotime($schedule->start_date . ' ' . $schedule->start_time);
            $paramForGetSchedulePanelPosition[] = strtotime($schedule->end_date . ' ' . $schedule->end_time);
        }

        if ($schedule->schedule_category) $scheduleContents[] = $schedule->schedule_category->name . '：'; // カテゴリ
        $scheduleContents[] = $schedule->title; // タイトル

        $scheduleText = implode(' ', $scheduleContents);
        
        // Style
        $styles = $this->getSchedulePanelPosition(...$paramForGetSchedulePanelPosition);
        $styleAttribute = $this->getStyleAttributeString($styles);

        // 最終HTML
        $html = [];
        $html[] = trim('
            <div class="schedule-panel weekday-schedule-panel" style="' . $styleAttribute .'" data-schedule-id=' . e($schedule->id) . '>
                <p class="schedule-text">' . $scheduleText . '</p>
            </div>
        ');
        
        return implode('', $html);
    }
}