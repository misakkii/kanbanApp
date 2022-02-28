<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\{
    Work_time,
    User,
    Task,
};
use Validator;


class DetailController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // $work_times = Work_time::where('user_id', Auth::id())->get();
        // dd($work_times->toArray());

        $task_in_user = User::with(
            'taskInToday',
            'taskInNow',
            'taskInStandby',
            'taskInDone',
        )
            ->select('id', 'last_name', 'first_name')
            ->find(Auth::id());
        // dd($task_in_user->toArray());

        return Inertia::render('Detail/Index', [
            // 'work_times' => fn() => $work_times,
            'task_in_user' => fn() => $task_in_user,
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
    public function show($id)
    {
        //
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
    public function update(Time $time)
    {
        $time->update();




        // dd($time);

        // $vd_executed_time = Validator::make($input, [
        //     'executed_time' => 'require | date'
        // ]);
        // $vd_suspended_time = Validator::make($input, [
        //     'suspended_time' => 'require | date'
        // ]);

        // $work_times = Work_time::find($id);

        // $work_times->update(['executed_time' => $vd_executed_time['executed_time']]);
        // $work_times->update(['suspended_time' => $vd_suspended_time['suspended_time']]);

        // return redirect()->route('detail', $parameters = [], $status = 303, $headers = []);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
