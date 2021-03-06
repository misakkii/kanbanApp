<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\{
    User,
    Task,
    Project,
    Work_time
};
use App\Events\TaskAdded;
use Inertia\Inertia;
use Carbon\Carbon;
use Validator;


class DashboardController extends Controller
{
    /**
     * Display a listing of the resource.
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $users_tasks = User::with(
            'taskInNow',
            'taskInToday',
            'taskInStandby',
            'taskInDone',
        )
            ->select('id', 'last_name', 'first_name')
            ->get();
        // dd($users_tasks->toArray());

        $projects = Project::select('id', 'project_name')->get();

        // event(new TaskAdded($users_all));
        return Inertia::render('Dashboard/Index', [
            'auth' => User::where('id', Auth::id())
                ->select('id', 'last_name', 'first_name')
                ->get(),
            'users_tasks' => fn() => $users_tasks,
            'projects' => fn() => $projects,
            // 'test' => Inertia::lazy(fn ()=> '非同期通信のテスト')
        ]);

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required',
            'project_id' => 'required',
            'title' => 'required',
            'due_date' => 'date | nullable',
            'created_by' => 'required',
        ])->validateWithBag('storeTask');


        $new_today_tasks = User::all()
            ->find(Auth::id())//requestを送ったユーザーのID
            ->tasks()->joinProject()
            ->whereIn('status', ['today'])
            ->get();
        dd($new_today_tasks->ToArray());
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Dashboard  $dashboard
     * @return \Illuminate\Http\Response
     */
    public function show(Dashboard $dashboard)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Dashboard  $dashboard
     * @return \Illuminate\Http\Response
     */
    public function edit(Dashboard $dashboard)
    {
        //
    }

    public function assignOff(Request $request)
    {
        // dd($request);
        $task = Task::find($request->task_id);

        //削除したいuser_idの設定（複数）
        $task->users()->updateExistingPivot($request->user_id, ['deleted_at' => Carbon::now()]);

        return redirect()->route('dashboard', $parameters = [], $status = 303, $headers = []);
    }

    public function executeTask(Request $request)
    {
        // dd($request);
        if(empty($request->user_id)) {
            return redirect()->route('dashboard', $parameters = [], $status = 303, $headers = []);
        }

        // dd($request);
        $user = User::find($request->user_id);

        $status = 'now';
        $user->tasks()->updateExistingPivot($request->task_id, ['status' => $status]);

        // dd($find_null->suspended_time);
        $new_execute = Work_time::create([
            'user_id' => $request->user_id,
            'task_id' => $request->task_id,
            'executed_time' => Carbon::now(),
            'use_date' => Carbon::today(),
        ]);

        return redirect()->route('dashboard', $parameters = [], $status = 303, $headers = []);
    }

    public function suspendTask(Request $request)
    {
        // dd($request->toArray());
        $work_time = Work_time::where('user_id', $request->user_id)
            ->latest('created_at')
            ->first();
        // dd($task_time->toArray());
        $now = Carbon::now();

        //タスク待機と同時に秒の差分のデータも算出
        function timeDiff($exe_time, $sus_time)
        {
            $diff_time = array();

            //日付データを秒に変換
            $exeTime = strtotime($exe_time);
            $susTime = strtotime($sus_time);

            $dif_seconds =  $susTime - $exeTime;

            $dif_minutes = ($dif_seconds - ($dif_seconds % 60)) / 60;
            $dif_hours = ($dif_minutes - ($dif_minutes % 60)) / 60;

            // $diffTime['seconds'] = $dif_seconds % 60;
            $diff_time['minutes'] = $dif_minutes % 60;
            $diff_time['hours'] = $dif_hours;

            return $diff_time;
        }

        $diff = timeDiff($work_time->executed_time, $now);
        $work_time->suspended_time = $now;
        $work_time->minute = $diff['minutes'] ;
        $work_time->hour = $diff['hours'];
        $work_time->save();

        //task_userのtotal_work_minutに合計時間の更新
        $time_data = Work_time::where('user_id', $request->user_id)
            ->where('task_id', $work_time->task_id)
            ->select('minute', 'hour')
            ->get();
        $sum_hour_to_minute = 0;
        $sum_minute = 0;
        foreach($time_data as $arr) {
            $sum_hour_to_minute += ($arr->hour * 60);
        }
        foreach ($time_data as $arr) {
            $sum_minute += $arr->minute;
        }
        $total_minute = ($sum_hour_to_minute + $sum_minute);
        function totalDiff($total_minute)
        {
            $diff_time = array();

            $total_hours = ($total_minute - ($total_minute % 60)) / 60;

            $diff_time['minutes'] = $total_minute % 60;
            $diff_time['hours'] = $total_hours;

            return $diff_time;
        }
        // dd($total_minute);
        // $total_minute = ($total_second - ($total_second % 60)) / 60;

        $task = Task::find($work_time->task_id);
        $status = 'today';
        $total = totalDiff($total_minute);
        $task->users()->updateExistingPivot($request->user_id, [
            'status' => $status,
            'total_work_minute' => $total_minute,
            'total_minute' => $total['minutes'],
            'total_hour' => $total['hours'],
        ]);

        return redirect()->route('dashboard', $parameters = [], $status = 303, $headers = []);

        //task_user.total_work_timeにwork_timesの集計時間を60進数で保存
        $time_data = Work_time::where('user_id', $request->user_id)
            ->where('task_id', $task_user->task_id)
            ->select('total_second')
            ->get();
        // dd($time_data->toArray());
        $total_minute = 0;
        foreach ($time_data as $arr) {
            $total_minute += $arr->total_second;
        }
        dd($total);

        //一度分に直す
        foreach($time_data as $arr) {
            $hour_chenge_minute += ($arr->hour * 60);
        }
        foreach ($time_data as $arr) {
            $total_minute += $arr->minute;
        }
        //このデータを再計算してtask_userに保存
        $munute = $hour_chenge_minute + $total_minute;

        function time_diff($exe_time, $sus_time)
        {
            $deffTime = array();

            //日付データを秒に変換
            $exeTime = strtotime($exe_time);
            $susTime = strtotime($sus_time);

            $difSeconds =  $susTime - $exeTime;

            return $difSeconds;
        }

        $difSeconds =  $susTime - $exeTime;
        $difMinutes = ($difSeconds - ($difSeconds % 60)) / 60;
        //該当の全データの取得
        //繰返し構文で①分データに変換
        //①を全て足す

        // $difHours = ($difMinutes - ($difMinutes % 60)) / 60;

        // $diffTime['seconds'] = $difSeconds % 60;
        // $diffTime['minutes'] = $difMinutes % 60;
        // $diffTime['hours'] = $difHours;
        // dd($difMinutes);

        //分データの保存
        $task_total_time = Task::find($task_user->task_id);
        $task_total_time->users()->updateExistingPivot($request->user_id, ['total_work_time' => $difMinutes]);


        return redirect()->route('dashboard', $parameters = [], $status = 303, $headers = []);
    }

    public function completeTheTask(Request $request)
    {
        // dd($request);
        $work_time = Work_time::where('user_id', $request->user_id)
            ->latest('created_at')
            ->first();
        // dd($task_time->toArray());

        $work_time->suspended_time = Carbon::now();
        $work_time->save();

        $task_status = Task::find($work_time->task_id);

        $status = 'done';
        $task_status->users()->updateExistingPivot($request->user_id, [
            'status' => $status,
            'completed_at' => Carbon::now(),
        ]);

        return redirect()->route('dashboard', $parameters = [], $status = 303, $headers = []);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $input = $request->all();
        // dd($input);

        $validator = Validator::make($input, [
            'project_id' => 'required',
            'title' => 'required | string',
            'due_date' => 'nullable | date',
        ])->validateWithBag('taskUpdate');

        $task = Task::where('id', $request->id)->update([
            'project_id' => $validator['project_id'],
            'title' => $validator['title'],
            'due_date' => $validator['due_date'],
        ]);

        return redirect()->route('dashboard', $parameters = [], $status = 303, $headers = []);
    }

    public function chengeToToday(Request $request)
    {

        $user = User::find($request->user_id);
        // dd($user);

        $status = 'today';
        $user->tasks()->updateExistingPivot($request->task_id, ['status' => $status]);

        return redirect()->route('dashboard', $parameters = [], $status = 303, $headers = []);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Dashboard  $dashboard
     * @return \Illuminate\Http\Response
     */
    public function destroy(Dashboard $dashboard)
    {
        //
    }
}
