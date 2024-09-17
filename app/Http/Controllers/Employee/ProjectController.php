<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Services\ProjectService;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
    protected $projectService;
    public function __construct(ProjectService $projectService)
    {
        $this->projectService = $projectService;
    }
    /**
     * Display a listing of the resource.
     */
    /**
     * get all projects
     *
     * @return response  of the status of operation : projects
     */
    public function index()
    {
        $projects = $this->projectService->allProjects();

        return response()->json([
            'status' => 'success',
            'data' => [
                'projects' =>  $projects
            ],
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    /**
     * get one project 
     *
     * @param Project $project
     *
     * @return response  of the status of operation :  user_role_in_project , project and tasks
     */
    public function show(Project $project)
    {
        $data = $this->projectService->oneProject($project);
        return response()->json([
            'status' => 'success',
            'data' => [
                'user_role_in_project' => $data['user_role_in_project'],
                'project' =>  $data['project'],
                'tasks' =>   $data['tasks']
            ],
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Project $project)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy()
    {
        //
    }
    /**
     * end a project 
     *
     * @param Project $project
     *
     * @return response  of the status of operation 
     */
    public function endProject(Project $project)
    {
        $this->projectService->endProject($project);
        return response()->json([
            'status' => 'success',
        ], 200);
    }
}