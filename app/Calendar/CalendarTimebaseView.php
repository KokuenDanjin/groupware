<?php 
/**
* CalendarTimebaseViewクラス
* weekView、dayView用
*
* @author Carlos
* @since 1.0.0
*/

namespace App\Calendar;

use App\Models\schedule;
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
        $startTime = $schedule->start_time ?? '00:00:00';
        $endTime = $schedule->end_time ?? '00:00:00';
        $start = Carbon::parse($schedule->start_date . ' ' . $startTime);
        $end = Carbon::parse($schedule->end_date . ' ' . $endTime);

        // 同日ならそのまま返す
        if ($start->isSameDay($end)) {
            // 元スケジュールの日時情報を保持
            $schedule->original_start = $start;
            $schedule->original_end = $end;
            $schedule->split_priority = ($schedule->time_type === 'all_day') ? 1 : 2;
            return [$schedule];
        }

        $splits = [];
        $current = $start->copy();

        while ($current->lte($end)) {
            $dailyDate = $current->toDateString();
            $isFirstDay = $current->isSameDay($start);
            $isLastDay = $current->isSameDay($end);

            if ($schedule->time_type === 'all_day') {
                $dailyStart = '00:00:00';
                $dailyEnd = '23:59:59';
            } else {
                if ($isFirstDay) {
                    $dailyStart = $start->format('H:i:s');
                    $dailyEnd = '23:59:59';
                } elseif ($isLastDay) {
                    $dailyStart = '00:00:00';
                    $dailyEnd = $end->format('H:i:s');
                } else {
                    $dailyStart = '00:00:00';
                    $dailyEnd = '23:59:59';
                }
            }

            $type = (!$current->isSameDay($start) && !$current->isSameDay($end))
                ? 'multi_day_normal'
                : $schedule->time_type;
            $splits[] = (object)[
                'id' => $schedule->id . '-' . $dailyDate,
                'parent_id' => $schedule->id,
                'title' => $schedule->title,
                'time_type' => $type,
                'schedule_category' => $schedule->schedule_category,
                'start_date' => $dailyDate,
                'end_date' => $dailyDate,
                'start_time' => $dailyStart,
                'end_time' => $dailyEnd,
                'original_start' => $start,
                'original_end' => $end,
                'created_at' => $schedule->created_at,
                'split_priority' => ($type === 'all_day') ? 1 : 2
            ];

            $current->addDay();
        }

        return $splits;
    }


    /**
     * 時間の重複でスケジュールをグループ化する（終日も含む）メソッド
     *
     * @param array $schedules スケジュールの配列
     * 
     * @return array グループ配列（それぞれが重複するスケジュール群）
     */
    function groupOverlappingSchedulesWithAllDay($schedules): array
    {
        // 時間情報を付与
        $normalized = array_map(function($s) {
            $start = ($s->time_type === 'all_day' || $s->time_type === 'multi_day_normal') 
                ? strtotime($s->start_date . ' 00:00:00') 
                : strtotime($s->start_date . ' ' . $s->start_time);
            $end = ($s->time_type === 'all_day' || $s->time_type === 'multi_day_normal') 
                ? strtotime($s->start_date . ' 23:59:59') 
                : strtotime($s->end_date   . ' ' . $s->end_time);
            return [
                'schedule' => $s,
                'start' => $start,
                'end' => $end
            ];
        }, $schedules);
        usort($normalized, fn($a, $b) => $a['start'] <=> $b['start']);

        // グラフ探索で連結成分（重複グループ）を作る
        $visited = [];
        $groups = [];

        $overlap = function($a, $b) {
            $result = $a['start'] < $b['end'] && $b['start'] < $a['end'];
            return $result;
        };

        foreach ($normalized as $i => $node) {
            if (isset($visited[$i])) continue;

            // BFS / DFS
            $stack = [$i];
            $component = [];

            while ($stack) {
                $idx = array_pop($stack);
                if (isset($visited[$idx])) continue;
                $visited[$idx] = true;
                $component[] = $normalized[$idx];

                foreach ($normalized as $j => $other) {
                    if (!isset($visited[$j]) && $overlap($normalized[$idx], $other)) {
                        $stack[] = $j;
                    }
                }
            }

            $groups[] = $component;
        }

        // 各グループ内で並び順を決定
        foreach ($groups as &$group) {
            usort($group, function($a, $b) {
                $pa = $a['schedule']->split_priority ??
                ($a['schedule']->time_type === 'all_day' || $a['schedule']->time_type === 'multi_day_normal' ? 1 : 2);
                $pb = $b['schedule']->split_priority ??
                ($b['schedule']->time_type === 'all_day' || $b['schedule']->time_type === 'multi_day_normal' ? 1 : 2);

                if ($pa !==$pb) return $pa <=> $pb;

                if ($a['start'] === $b['start']) {
                    return strtotime($a['schedule']->created_at) <=> strtotime($b['schedule']->created_at);
                }
                return $a['start'] <=> $b['start'];
            });

            // グループ分け結果
            foreach ($groups as $idx => $group) {
                $ids = array_map(fn($n) => $n['schedule']->id, $group);
            }
        }

        return array_map(fn($g) => array_column($g, 'schedule'), $groups);
    }

    
    /**
     * 1グループ内での列割り当てをするメソッド
     *
     * @param  array $group 同じ連結成分のスケジュール
     * 
     * @return array [['schedule' => Schedule, 'col' => int], ...] colは0開始
     */
    function assignColumnsInGroup($group): array
    {
        usort($group, function($a, $b) {
            $pa = $a->split_priority ?? ($a->time_type === 'all_day' ? 1 : 2);
            $pb = $b->split_priority ?? ($b->time_type === 'all_day' ? 1 : 2);
            
            if ($pa !== $pb) return $pa <=> $pb;

            $sa = strtotime($a->start_date . ' ' . $a->start_time);
            $sb = strtotime($b->start_date . ' ' . $b->start_time);
            
            return $sa <=> $sb ?: strtotime($a->created_at) <=> strtotime($b->created_at);
        });

        $columns = [];
        $results = [];

        foreach ($group as $s) {
            $start = ($s->time_type === 'all_day') 
                ? strtotime($s->start_date . ' 00:00:00') 
                : strtotime($s->start_date . ' ' . $s->start_time);
            $end = ($s->time_type === 'all_day') 
                ? strtotime($s->start_date . ' 23:59:59') 
                : strtotime($s->end_date   . ' ' . $s->end_time);

            $colIndex = null;
            foreach ($columns as $idx => $lastEnd) {
                if ($start >= $lastEnd) {
                    $colIndex = $idx;
                    $columns[$idx] = $end;
                    break;
                }
            }
            if ($colIndex === null) {
                $colIndex = count($columns);
                $columns[] = $end;
            }
            $results[] = ['schedule' => $s, 'col' => $colIndex];
        }

        return ['items' => $results, 'total' => count($columns)];
    }

    /**
    * scheduleの開始時間、終了時間を固定するメソッド
    *
    * @param schedule $schedule スケジュール
    * @param string $startTime 開始時間（H:i:s）
    * @param string $endTime 終了時間（H:i:s）
    *
    */
    function scheduleTimeFixer($schedule, $startTime = '00:00:00', $endTime = '23:59:59'): void
    {
        $schedule->start_time = $startTime;
        $schedule->end_time = $endTime;
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
    function callScheduleRender($userId, $date, $days = 1): Collection
    {
        $startDate = Carbon::parse($date);
        $endDate = $startDate->copy()->addDay($days - 1);

        $groupedSchedules = $this->getGroupedSchedulesByDateRange([
            'start' => $startDate->toDateString(),
            'end'   => $endDate->toDateString(),
            'userId'=> $userId
        ]);

        // 終日予定の時間を固定
        $groupedSchedules->each(function($schedules) {
            foreach ($schedules as $s) {
                if ($s->time_type === 'all_day') {
                    $this->scheduleTimeFixer($s);
                }
            }
        });

        $groupedByDate = [];

        // 日跨ぎを分割して日ごとにまとめる
        foreach ($groupedSchedules as $dateKey => $schedules) {
            foreach ($schedules as $schedule) {
                foreach ($this->splitMultiDaySchedule($schedule) as $split) {
                    if (!isset($split->split_priority)) {
                        $split->split_priority = ($split->time_type === 'all_day') ? 1 : 2;
                    }
                    $groupedByDate[$split->start_date][] = $split;
                }
            }
        }

        $rendered = [];
        $availability = $this->getAvailabilityTime();

        foreach ($groupedByDate as $dateKey => $schedules) {
            $daily = collect($schedules);

            // 表示範囲内
            $inTime = $daily->filter(function($s) use ($availability) {
                if ($s->time_type === 'undecided') return false;
                if ($s->time_type === 'all_day') return true;

                $start = strtotime($s->start_date . ' ' . $s->start_time);
                $end   = strtotime($s->end_date   . ' ' . $s->end_time);

                $timelineTop    = strtotime($s->start_date . sprintf(' %02d:00:00', $availability['startTime']));
                $timelineBottom = strtotime($s->start_date . sprintf(' %02d:00:00', $availability['endTime']));

                return !($end < $timelineTop || $start > $timelineBottom);
            })->values();

            // 表示時間外
            $outTime = $daily
                ->filter(fn($s) => $s->time_type === 'normal')
                ->reject(fn($s) => $inTime->contains(fn($in) => $in->id === $s->id && $in->start_date === $s->start_date))
                ->values();

            // 未定
            $undecided = $daily->where('time_type', 'undecided')->values();

            // 重複グループ化
            $groups = $this->groupOverlappingSchedulesWithAllDay($inTime->all());

            // 列割り当て & HTML化
            foreach ($groups as $group) {
                $assigned = $this->assignColumnsInGroup($group);
                $totalCols = $assigned['total'];

                foreach ($assigned['items'] as $item) {
                    $rendered[$dateKey][] = $this->scheduleRender($item['schedule'], $item['col'], $totalCols);
                }
            }

            // 時間外予定
            foreach ($outTime as $schedule) {
                $rendered[$dateKey][] = $this->scheduleRender($schedule);
            }

            // 未定予定
            foreach ($undecided as $schedule) {
                $rendered[$dateKey][] = $this->scheduleRender($schedule);
            }
        }

        return collect($rendered);
    }

    /**
    * スケジュールをレンダリングするメソッド
    *
    * @param Schedule $schedule インスタンス
    * @param integer $index 横並びにした場合の左からの順番（0始まり）
    * @param integer $count 同時に重なっているスケジュールの総数
    *
    * @return string スケジュールのhtml
    */
    function scheduleRender($schedule, $index = 0, $count = 1): string
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

        if ($timeType === 'normal' || $timeType === 'multi_day_normal') {
            $paramForGetSchedulePanelPosition[] = strtotime($schedule->start_date . ' ' . $schedule->start_time);
            $paramForGetSchedulePanelPosition[] = strtotime($schedule->end_date . ' ' . $schedule->end_time);
        }

        if ($schedule->schedule_category) $scheduleContents[] = $schedule->schedule_category->name . '：'; // カテゴリ
        $scheduleContents[] = $schedule->title; // タイトル

        $scheduleText = implode(' ', $scheduleContents);

        // 最終HTML
        $html = [];
        $html[] = trim('
            <div
                class="schedule-panel weekday-schedule-panel"
                
                data-schedule-id="' . e($schedule->id) .'"
                data-start="' . e($schedule->start_date . 'T' . $schedule->start_time) . '"
                data-end="' . e($schedule->end_date . 'T' . $schedule->end_time) . '"
                data-time-type="' . e($schedule->time_type) . '"
                data-title="' . e($schedule->title) . '"
            >
                <p class="schedule-text">' . $scheduleText . '</p>
            </div>
        ');
        
        return implode('', $html);
    }
}