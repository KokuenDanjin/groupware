<?php 
/**
* CalendarViewクラス ※スーパークラス
*
* @author Carlos
* @since 1.0.0
*/

namespace App\Calendar;

use App\Models\schedule;
use Carbon\Carbon;
use \Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;

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
    * 前日の日付をY-m-d形式で取得するメソッド
    *
    * @param string $date 日付 Y-m-d ※省略で現在
    *
    * @return string 前の日（例：2025-06-15）
    */
    static function getBeforeDay($date = null): string
    {
        return Carbon::parse($date ?? now())->subDay()->toDateString();
    }

    /**
    * 翌日の日付をY-m-d形式で取得するメソッド
    *
    * @param string $date 日付 Y-m-d ※省略で現在
    *
    * @return string 次の日（例：2025-06-17）
    */
    static function getNextDay($date = null): string
    {
        return Carbon::parse($date ?? now())->addDay()->toDateString();
    }

    /**
    * 1週間前の日付をY-m-d形式で取得するメソッド
    *
    * @param string $date 日付 Y-m-d ※省略で現在
    *
    * @return string 前の週（例：2025-06-09）
    */
    static function getBeforeWeek($date = null): string
    {
        return Carbon::parse($date ?? now())->subWeek()->toDateString();
    }
    
    /**
    * 1週間後の日付をY-m-d形式で取得するメソッド
    *
    * @param string $date 日付 Y-m-d ※省略で現在
    *
    * @return string 次の週（例：2025-06-23）
    */
    static function getNextWeek($date = null): string
    {
        return Carbon::parse($date ?? now())->addWeek()->toDateString();
    }

    /**
    * 前の月の1日をY-m-d形式で取得するメソッド
    *
    * @param string $date 日付 Y-m-d ※省略で現在
    *
    * @return string 前の月（例：2025-05-01）
    */
    static function getBeforeMonth($date = null): string
    {
        return Carbon::parse($date ?? now())->startOfMonth()->subMonth()->toDateString();
    }
    
    /**
    * 次の月の1日Y-m-d形式で取得するメソッド
    *
    * @param string $date 日付 Y-m-d ※省略で現在
    *
    * @return string 次の月（例：2025-07-01）
    */
    static function getNextMonth($date = null): string
    {
        return Carbon::parse($date ?? now())->startOfMonth()->addMonth()->toDateString();
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
    * スケジュール新規作成ボタンのdivタグを取得するメソッド
    *
    * @param CalendarWeekDay CalendarWeekDayインスタンス
    *
    * @return string HTML schedule-add-button-blockクラス
    */
    function getScheduleAddButton($day):string
    {
        $userId = Request::query('userId', Auth::id());

        $scheduleAddBtnRoute = route('schedule.create', ['date' => $day->getString('Y-m-d')]) . '?' . http_build_query(['userId' => $userId]);
        $html[] = trim('
            <div class="schedule-add-button-block">
                <a class="schedule-add-button" href="' . $scheduleAddBtnRoute .'">
                    <span class="schedule-add-button-icon material-symbols-outlined">edit_square</span>
                </a>
            </div>
        ');

        return implode('', $html);
    }

    /**
    * 日付指定範囲分のスケジュールを取得するメソッド
    *
    * @param array $options [
    *                   'start' => string 'Y-m-d',
    *                   'end' => string 'Y-m-d', // 省略可(単日指定)
    *                   'userId' => int  // 省略可(全ユーザー)
    *               ]
    *
    * @return Collection
    */
    function getGroupedSchedulesByDateRange($options): Collection
    {
        $start = $options['start'];
        $end = $options['end'] ?? null;
        $userId = $options['userId'] ?? null;

        $query = schedule::query();

        if ($start && $end) {
            $query->whereBetween('start_date', [$start, $end]);
        } else {
            $query->where('start_date', $start);
        }

        if ($userId) {
            $query->whereHas('users', function($q) use ($userId) {
                $q->where('users.id', $userId);
            });
        }

        return $query->orderBy('start_time')->get()->groupBy('start_date');
    }

    /**
    * スケジュールをレンダリングするメソッド
    *
    * @return string スケジュールのhtml
    */
    abstract function scheduleRender($schedule): string;

}