<?php

namespace App\Services;

use App\Enums\ProjectStatus;
use App\Http\Resources\ProjectResource;
use App\Http\Resources\TaskResource;
use Exception;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Models\Project;
use App\Models\User;

class ProjectService
{
    /**
     * get all pojects
     * @return ProjectResource $projects
     */
    public function allProjects()
    {
        try {
            if (Auth::user()->role == 'admin') {
                $projects = Project::all();
            } else {
                $user = User::find(Auth::user()->id);
                $projects = $user->load('projects')->projects;
            }
            $projects = ProjectResource::collection($projects);
            return $projects;
        } catch (Exception $e) {
            Log::error("error in get all projects" . $e->getMessage());
            throw new Exception("there is something wrong in server");
        }
    }
    /**
     * create a poject
     * @param array $projectData 
     * @return ProjectResource $project
     * 
     */
    public function createProject($projectData)
    {

        try {
            $project = Project::create([
                'name' => $projectData['name'],
                'descripation' => $projectData['descripation'],
            ]);
            $project = ProjectResource::make($project);
            return $project;
        } catch (Exception $e) {
            Log::error("error in create project" . $e->getMessage());
            throw new Exception("there is something wrong in server");
        }
    }
    /**
     * get a poject
     * @param Project $project 
     * @return array string user_role  ProjectResource $project TaskResource $tasks
     */
    public function oneProject($project)
    {
        $user = User::find(id: Auth::user()->id);

        if ($user->role == 'admin') {
            $project = $project->load('users');
        } else {
            $authService = new AuthService();
            $active_user_role = $authService->getRoleUserInProject($project);

            if ($active_user_role == "manager") {
                $tasks =  $user->load(['tasks' => function ($q) use ($user) {
                    $q->where('manager_id', '=', $user->id);
                }]);
            } else if ($active_user_role == "developer") {
                $tasks = $user->load(['tasks' => function ($q) use ($user) {
                    $q->where('employee_id', '=', $user->id);
                }]);
            } else if ($active_user_role == "tester") {
                $tasks = $user->load('tasks');
            };
            $tasks = TaskResource::collection($tasks->tasks);
            $project = ProjectResource::make($project);

            return [
                'user_role_in_project' => $active_user_role,
                'project' => $project,
                'tasks' => $tasks,
            ];
        }
        try {
        } catch (Exception $e) {
            Log::error("error in get one project" . $e->getMessage());
            throw new Exception("there is something wrong in server");
        }
    }
    /**
     * update a poject
     * @param Project $project 
     * @param array $projectData
     * @return  ProjectResource   $task
     */
    public function updateProject($projectData, $project)
    {

        try {
            $project->update($projectData);
            $project = ProjectResource::make($project);
            return $project;
        } catch (Exception $e) {
            Log::error("error in update a poject" . $e->getMessage());
            throw new Exception("there is something wrong in server");
        }
    }
    /**
     * delete a project
     * @param Project $project 
     */
    public function deleteProject($project)
    {
        try {
            $project->users()->detach();
            $project->tasks()->detach();
            $project->delete();
        } catch (Exception $e) {
            Log::error("error in delete a project" . $e->getMessage());
            throw new Exception("there is something wrong in server");
        }
    }
    /**
     * end a poject
     * @param Project $project 
     */
    public function endProject($project)
    {
        try {
            $user = User::find(Auth::user()->id);
            $authService = new AuthService();
            $active_user_role = $authService->getRoleUserInProject($project);
        } catch (Exception $e) {
            Log::error("error in end a poject" . $e->getMessage());
            throw new Exception("there is something wrong in server");
        }
        if ($active_user_role == "manager") {
            if ($project->status == ProjectStatus::ENDWORK->value) {
                try {
                    $project->status = ProjectStatus::ENDED->value;
                    $project->save();

                    $notManagerEmployee = $project->users()->wherePivot('role', '!=', 'manager')->get();
                    $totalHours = 0;
                    foreach ($notManagerEmployee  as $e) {
                        $totalHours += $e->pivot->contribution_hours;
                    }
                    $user->projects()->updateExistingPivot($project, ['contribution_hours' => $totalHours, 'last_activity' => now()]);
                } catch (Exception $e) {
                    Log::error("error in end a poject" . $e->getMessage());
                    throw new Exception("there is something wrong in server");
                }
            } else {
                throw new HttpResponseException(response()->json(
                    [
                        'status' => 'error',
                        'message' => 'لا يمكنك انهاء المشروع لم يم يتم انهاء كل المهمات',
                    ],
                    422
                ));
            }
        } else {
            throw new HttpResponseException(response()->json(
                [
                    'status' => 'error',
                    'message' => 'لا يمكنك انهاء المشروع هذه العملية خاصة بمدير المشروع',
                ],
                403
            ));
        }
    }
}