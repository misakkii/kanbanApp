<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Project;
use Inertia\Inertia;
use Validator;

class ProjectController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Inertia::render('Project/List/Index', [
            'items' => fn() => Project::whereNull(['deleted_at', 'completed_at'])->get()
        ]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function createCompletion()
    {
        return Inertia::render('Project/List/Completion', [
            'items' => fn() => Project::whereNotNull('completed_at')->get()
        ]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function createDelete()
    {
        return Inertia::render('Project/List/Delete', [
            'items' => fn() => Project::whereNotNull('deleted_at')->get()
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function createList()
    {
        return Inertia::render('Project/List', ['items' => Project::all()]);
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
            'title' => 'required | string',
            'due_date' => 'nullable | date'
        ])->validateWithBag('saveProject');

        $project = Project::create([
            'title' => $request->title,
            'due_date' => $request->due_date
        ]);

        return redirect()->route('project.index', $parameters = [], $status = 303, $headers = []);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Project  $project
     * @return \Illuminate\Http\Response
     */
    public function show(Project $project)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request)
    {
        //
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
        // $task = Task::find($input->id());

        // dd($input);
        Validator::make($input, [
            'project_name' => 'required | string',
            'due_date' => 'nullable | date',
        ])->validateWithBag('projectUpdate');

        $project = Project::where('id', $request->id)->update([
            'project_name' => $request->project_name,
            'due_date' => $request->due_date,
        ]);

        // $project = Project::find($input->id);
        // $project->tasks()->save(['project_name' => $input->project_name]);
        // $project->title = $input->project_name;
        // $project->title = $input->due_date;
        // $project->save();

        return redirect()->route('project.index', $parameters = [], $status = 303, $headers = []);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $project = Project::destroy($request->id);
        // $project = Project::where('id', $request->id)->delete();

        return redirect()->route('project.index', $parameters = [], $status = 303, $headers = []);
    }
}
