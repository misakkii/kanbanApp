<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\{User, Task, Project};
use App\Events\TaskAdded;
use Inertia\Inertia;
use Carbon\Carbon;


class DashboardController extends Controller
{
    /**
     * Display a listing of the resource.
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $users_tasks = User::with('taskInToday','taskInNow', 'taskInStandby')
            ->select('id', 'last_name', 'first_name')
            ->get();
        dd($users_tasks->toArray());

        $projects = Project::select('id', 'project_name')->get();

        // event(new TaskAdded($users_all));
        return Inertia::render('Dashboard/Index', [
            'auth' => User::where('id', Auth::id())
                ->select('id', 'last_name', 'first_name')
                ->get(),
            'users_tasks' => $users_tasks,
            'projects' => fn() => $projects,
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
        $task->users()->detach($request->user_id);

        return redirect()->route('dashboard', $parameters = [], $status = 303, $headers = []);
    }

    public function start(Request $request)
    {

        $task = Task::find($request->task_id);

        $status = 'now';
        $task->users()->updateExistingPivot($request->user_id, ['status' => $status]);
        // dd($task->toArray());

        $task->workTimes()->attach($request->user_id, [
            'start_date_time' => Carbon::now(),
            'use_date' => Carbon::today(),
        ]);

        return redirect()->route('dashboard', $parameters = [], $status = 303, $headers = []);


        // task_userのstatusをnowに変更
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Dashboard  $dashboard
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Dashboard $dashboard)
    {
        //
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
