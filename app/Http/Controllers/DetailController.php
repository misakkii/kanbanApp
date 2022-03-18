<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

use Illuminate\Http\Request;
use App\Http\Traits\TimeDiffTrait;
use Inertia\Inertia;
use App\Models\{
    Work_time,
    User,
    Task,
};
use Carbon\Carbon;



class DetailController extends Controller
{
    use TimeDiffTrait;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //2回目以降のページ読み込み時＄$requestで欲しい値を取得する
        $work_times = Work_time::where('user_id', Auth::id())
        // ->where('task_id',)
        ->get();
        // dd($work_times->toArray());

        $task_in_user = User::with(
            'taskInToday',
            'taskInNow',
            'taskInStandby',
            'taskInDone',
        )->select('id', 'last_name', 'first_name')
            ->find(Auth::id());
        // dd($task_in_user->toArray());

        $today = array($task_in_user->taskInToday->toArray());
        $now[0]['show'] = true;
        // $flag = $now + array('show' => true);
        // $now_sum_flag = array_merge($now, $flag);
        // dd($now);

        //フラグの追加
        $today_in_flag = array();
        foreach ($today[0] as $arr) {
            $data = array($arr);
            foreach ($data as $arr) {
                $arr['show'] = false;
                array_push($today_in_flag, $arr);
            }
        }

        // dd($today_in_flag);
        // dd($task_in_user->taskInToday->toArray());

        return Inertia::render('Detail/Index', [
            // 'work_times' => Inertia::lazy(fn () => $work_times),
            'task_in_user' => fn() => $task_in_user,
            'today' => fn() => $today_in_flag,
        ]);
    }

    public function workTimesIndex(Request $request)
    {
        $work_times = Work_time::where('user_id', $request->user_id)
            ->where('task_id', $request->task_id)
            ->whereNull('deleted_at')
            ->get();

        return response()->json([
            'work_times' => $work_times,
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
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request)
    {
        $work_times = Work_time::where('user_id', Auth::id())
        // ->where('task_id',)
        ->get();

        return redirect()->back()->with('work_times', $work_times);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Work_time $work_time)
    {
        return Inertia::render('Detail/Components/EditDataTable', [
            'work_time' => [
                'id' => $work_time->id,
                'user_id' => $work_time->user_id,
                'task_id' => $work_time->task_id,
                'executed_time' => $work_time->executed_time,
                'suspended_time' => $work_time->suspended_time,
            ]
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $work_time = Work_time::find($request->work_time_id);

        //差分が8時間以内になるようにバリデーション
        // $exe_time = strtotime($request->executed_time);
        // $sus_time = strtotime($request->suspended_time);
        // $diff = $sus_time - $exe_time;

        $validator = Validator::make($request->all(), [
            'executed_time' => 'required | date | before:suspended_time',
            'suspended_time' => 'required | date | after:executed_time',
        ]);

        if($validator->fails()) {
            return response()->json($validator->errors());
        }
        // if($diff > 28800) {
        //     return response()->json([
        //         'err_msg' => "差分が8時間以内になるように入力してください"
        //     ]);
        // }

        $diff = $this->timeDiff($request->executed_time, $request->suspended_time);

        $work_time->hour = $diff['hours'];
        $work_time->minute = $diff['minutes'];
        $work_time->executed_time = $request->executed_time;
        $work_time->suspended_time = $request->suspended_time;
        $work_time->save();

        $total_minute = $this->sumAllTimeDiff($request->task_id);
        $total = $this->totalDiff($total_minute);

        $task = Task::find($request->task_id)->users()->updateExistingPivot($request->user_id, [
            'total_work_minute' => $total_minute,
            'status' => 'today',
            'total_hour' => $total['hours'],
            'total_minute' => $total['minutes'],
        ]);

        return response()->json([
            'success' => true,
            'request' => $request->all(),
            'work_time' => $work_time,
            'val' => $diff,
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $work_time = Work_time::find($request->work_time_id);

        $work_time->deleted_at = Carbon::now();
        $work_time->save();

        $total_minute = $this->sumAllTimeDiff($request->task_id);
        $total = $this->totalDiff($total_minute);

        $task = Task::find($request->task_id)->users()->updateExistingPivot($request->user_id, [
            'total_work_minute' => $total_minute,
            'total_hour' => $total['hours'],
            'total_minute' => $total['minutes'],
        ]);

        $message = $request->user_id.'を削除しました';
        return response()->json(['message' => $message]);
    }
}
