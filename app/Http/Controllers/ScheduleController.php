<?php

namespace App\Http\Controllers;

use App\Http\Requests\ScheduleRequest;
use App\Models\schedule;
use App\Models\schedule_category;
use App\Models\schedule_user;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Request as FacadesRequest;
use Illuminate\View\View;

class ScheduleController extends Controller
{
    /**
    * スケジュールカテゴリとユーザーの全件を配列で取得するメソッド
    *
    * @return array 二次元配列
    */
    protected function getCommonContents(): array
    {
        $categories = schedule_category::all();
        $users = User::all();
        return compact('categories', 'users');
    }

    function show($id):View
    {
        $schedule = schedule::findOrFail($id);

        return view('schedule.scheduleShow', compact('id', 'schedule'));
    }

    function create():view
    {
        $contents = $this->getCommonContents();

        $contents['userId'] = FacadesRequest::query('userId', null);
        $contents['mode'] = 'create';
        $contents['currentDate'] = FacadesRequest::query('currentDate', null);

        return view('schedule.scheduleEdit', $contents);
    }

    function edit($id = null):view
    {
        $contents = $this->getCommonContents();
        $contents['mode'] = 'edit'; 
        
        $schedule = schedule::findOrFail($id);
        $contents['id'] = $id;
        $contents['schedule'] = $schedule;
        $contents['participants'] = $schedule->users()->pluck('id')->toArray();
        
        return view('schedule.scheduleEdit', $contents);
    }

    function store(ScheduleRequest $request):RedirectResponse
    {
        $validated = $request->validated();      

        $schedule = schedule::create([
            'title' => $validated['title'],
            'category_id' => $validated['category_id'] ?? null,
            'time_type' => $validated['time_type'],
            'start_date' => $validated['start_date'],
            'end_date' => $validated['end_date'],
            'start_time' => $validated['time_type'] === 'normal' ? $validated['start_time'] : null,
            'end_time' => $validated['time_type'] === 'normal' ? $validated['end_time'] : null,
            'private_flg' => $validated['private_flg'],
            'memo' => $validated['memo'] ?? null
        ]);

        $schedule->users()->sync($validated['participants']);

        // routeに渡す値をセッションから取得
        $back = session('calendar.back', [
            'userId' => null,
            'type' => null,
            'currentDate' => null
        ]);

        return redirect()->route('calendar.view', $back)->with('success', 'スケジュールを登録しました。');
    }

    function update(ScheduleRequest $request, $id):RedirectResponse
    {
        $validated = $request->validated();
        $schedule = schedule::findOrFail($id);
        $schedule->update([
            'title' => $validated['title'],
            'category_id' => $validated['category_id'] ?? null,
            'time_type' => $validated['time_type'],
            'start_date' => $validated['start_date'],
            'end_date' => $validated['end_date'],
            'start_time' => $validated['time_type'] === 'normal' ? $validated['start_time'] : null,
            'end_time' => $validated['time_type'] === 'normal' ? $validated['end_time'] : null,
            'private_flg' => $validated['private_flg'],
            'memo' => $validated['memo'] ?? null
        ]);

        $schedule->users()->sync($validated['participants']);

        // routeに渡す値をセッションから取得
        $back = session('calendar.back', [
            'userId' => null,
            'type' => null,
            'currentDate' => null
        ]);

        return redirect()->route('calendar.view', $back)->with('success', 'スケジュールを登録しました。');
    }

    function delete($id):RedirectResponse
    {
        $schedule = schedule::findOrFail($id);
        $schedule->delete();

        // routeに渡す値をセッションから取得
        $back = session('calendar.back', [
            'userId' => null,
            'type' => null,
            'currentDate' => null
        ]);

        return redirect()->route('calendar.view', $back)->with('success', 'スケジュールを削除しました。');
    }
}
