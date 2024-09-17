<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Project\StoreProjectRequest;
use App\Http\Requests\Project\UpdateProjectRequest;
use App\Models\Project;
use App\Services\ProjectService;

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
    /**
     * create a project
     *@param StoreProjectRequest request
     * @return response  of the status of operation : project
     */
    public function store(StoreProjectRequest $request)
    {
        $projectData = $request->validated();
        $project = $this->projectService->createProject($projectData);

        return response()->json([
            'status' => 'success',
            'data' => [
                'project' =>  $project
            ],
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    /**
     * get a one project 
     *
     * @param Project $project
     *
     * @return response  of the status of operation : project and tasks
     */
    public function show(Project $project)
    {
        $data = $this->projectService->oneProject($project);
        return response()->json([
            'status' => 'success',
            'data' => [
                'project' =>  $data['project'],
                'users' =>   $data['users']
            ],
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    /**
     * update a project
     *@param UpdateProjectRequest request
     * @param Project $project
     * @return response  of the status of operation : projects
     */
    public function update(UpdateProjectRequest $request, Project $project)
    {
        $projectData = $request->validated();
        $project = $this->projectService->updateProject($projectData, $project);

        return response()->json([
            'status' => 'success',
            'data' => [
                'project' =>  $project
            ],
        ],  200);
    }

    /**
     * Remove the specified resource from storage.
     */
    /**
     * delate a project
     * @param Project $project
     * @return response  of the status of operation 
     */
    public function destroy(Project $project)
    {
        $this->projectService->deleteProject($project);

        return response()->json(status: 204);
    }
}