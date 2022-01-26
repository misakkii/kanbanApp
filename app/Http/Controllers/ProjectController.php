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
        return Inertia::render('Project/Index', [
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
        return Inertia::render('Project/Completion', [
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
        return Inertia::render('Project/Delete', [
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
        ])->validatewithBag('saveProject');

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
        return Inertia::share('project',
            fn(Request $request) => Inertia::lazy(fn() => Project::find($request->id))
        );
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
        Validator::make($input, [
            'title' => 'required | string',
            'due_date' => 'nullable | date'
        ])->validateWithBag('projectUpdate');

        $project = project::where('id', $request->id)->update([
            'title' => $request->title,
            'due_date' => $request->due_date,
        ]);

        return redirect()->route('project.index', $parameters = [], $status = 303, $headers = []);


        // $project = Project::find($input->id);
        // $project->title = $input->title;
        // $project->title = $input->due_date;
        // $project->save();

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Project  $project
     * @return \Illuminate\Http\Response
     */
    public function destroy(Project $project)
    {
        //
    }
}
