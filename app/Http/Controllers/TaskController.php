<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\{
    Task,
    Project,
    User,
};
use Inertia\Inertia;
use Carbon\Carbon;
use Validator;

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        // $tasks = Task::with('projects')->get();
        $projects = Project::select('id', 'project_name')->get();
        $user = User::where('id', Auth::id())->select('id', 'last_name', 'first_name')->get();
        $users = User::select('id', 'last_name', 'first_name')->get();
        $tasks = Task::joinProject()
            ->whereNull(['tasks.deleted_at'])
            ->get();

        // dd($tasks->toArray());

        return Inertia::render('Task/List/Index', [
            'tasks' => fn() => $tasks,
            'projects' => fn() => $projects,
            'auth' => $user,
            'users' => $users,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function createCompletedList()
    {
        $projects = Project::select('id', 'project_name')->get();
        $user = User::find(Auth::id())->select('id')->get();
        $tasks = Task::joinProject()
            ->whereNotNull(['tasks.completed_at'])
            ->get();

        return Inertia::render('Task/List/Completed', [
            'tasks' => fn() => $tasks,
            'projects' => fn() => $projects,
            'auth' => $user
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function createDeletedList()
    {
        $projects = Project::select('id', 'project_name')->get();
        $user = User::find(Auth::id())->select('id')->get();
        $tasks = Task::joinProject()
            ->whereNotNull(['tasks.deleted_at'])
            ->get();

        return Inertia::render('Task/List/Deleted', [
            'tasks' => fn() => $tasks,
            'projects' => fn() => $projects,
            'auth' => $user
        ]);
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
            'user_id' => 'nullable',
            'project_id' => 'required',
            'title' => 'required',
            'due_date' => 'date | nullable',
            'created_by' => 'required',
        ])->validateWithBag('storeTask');
        // dd($validator);

        $task = Task::create([
            'project_id' => $validator['project_id'],
            'title' => $validator['title'],
            'due_date' => $validator['due_date'],
            'created_by' => $validator['created_by'],
        ]);

        //ここで複数のuser_idが入ればそのレコード分保存がかかる[user_id : {1, 2, 3}]
        $userId = $validator['user_id'];
        $task->users()->sync($userId, false);

        return redirect()->route('task.index', $parameters = [], $status = 303, $headers = []);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Task  $task
     * @return \Illuminate\Http\Response
     */
    public function show(Task $task)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Task  $task
     * @return \Illuminate\Http\Response
     */
    public function edit(Task $task)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Task  $task
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Task $task)
    {
        $input = $request->all();
        // dd($input);

        Validator::make($input, [
            'project_id' => 'required',
            'title' => 'required | string',
            'due_date' => 'nullable | date',
        ])->validateWithBag('taskUpdate');

        $task = Task::where('id', $request->id)->update([
            'project_id' => $request->project_id,
            'title' => $request->title,
            'due_date' => $request->due_date,
        ]);

        // DB::beginTransaction();
        // try {
        // } catch(Exception $e) {
        //     DB::rollback();
        // }
        //     DB::commit();

        return redirect()->route('task.index', $parameters = [], $status = 303, $headers = []);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $task = Task::find($request->id);
        $task->deleted_at = Carbon::now();
        $task->save();

        return redirect()->route('task.index', $parameters = [], $status = 303, $headers = []);
    }
}
