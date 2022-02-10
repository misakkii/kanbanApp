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
            'taskInToday',
            'taskInNow',
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

    public function executeTask(Request $request)
    {

        $task = Task::find($request->task_id);

        $status = 'now';
        $task->users()->updateExistingPivot($request->user_id, ['status' => $status]);

        $find_null = Work_time::where('user_id', $request->user_id)
            ->where('task_id', $request->task_id)
            ->latest('created_at')
            ->first();
        // dd($find_null->suspended_time);
        if(isset($find_null->suspended_time)) {
            $new_execute = Work_time::create([
                'user_id' => $request->user_id,
                'task_id' => $request->task_id,
                'executed_time' => Carbon::now(),
                'use_date' => Carbon::today(),
            ]);
            // dd('作成されます');
        }else{
            dd('実行が重複しています');
        }

        return redirect()->route('dashboard', $parameters = [], $status = 303, $headers = []);
    }

    public function suspendTask(Request $request)
    {
        // dd($request);
        $task_time = Work_time::where('user_id', $request->user_id)
            ->where('task_id', $request->task_id)
            ->latest('created_at')
            ->first();
        // dd($task_time->toArray());
        $task_time->suspended_time = Carbon::now();
        $task_time->save();

        $task_status = Task::find($request->task_id);

        $status = 'today';
        $task_status->users()->updateExistingPivot($request->user_id, ['status' => $status]);

        return redirect()->route('dashboard', $parameters = [], $status = 303, $headers = []);
    }

    public function completeTheTask(Request $request)
    {
        $task_time = Work_time::where('user_id', $request->user_id)
            ->where('task_id', $request->task_id)
            ->latest('created_at')
            ->first();
        // dd($task_time->toArray());
        $task_time->completed_time = Carbon::now();
        $task_time->save();

        $task_status = Task::find($request->task_id);

        $status = 'done';
        $task_status->users()->updateExistingPivot($request->user_id, ['status' => $status]);
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
