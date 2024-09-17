<?php

namespace App\Services;

use App\Enums\ProjectStatus;
use App\Enums\TaskStatus;
use App\Http\Resources\TaskResource;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Models\Task;
use App\Models\User;
use Illuminate\Http\Exceptions\HttpResponseException;

class TaskService
{

    public function allTasks($project)
    {
        /**
         * get all  tasks
         * @param Project $project 
         * @return   TaskResource $tasks
         */

        try {
            $user = User::find(id: Auth::user()->id);
            $authService = new AuthService();
            $active_user_role = $authService->getRoleUserInProject($project);

            if ($active_user_role == "manager") {
                $tasks =  $user->load(['tasks' => function ($q) use ($user) {
                    $q->where('manager_id', $user->id);
                }]);
            } else if ($active_user_role == "developer") {


                $tasks = $user->load(['tasks' => function ($q) use ($user) {
                    $q->where('employee_id', '=', $user->id);
                }]);
            } else if ($active_user_role == "tester") {
                $tasks = $user->load('tasks');
            };
            $tasks = TaskResource::collection($tasks->tasks);
            return $tasks;
        } catch (Exception $e) {
            Log::error("error in get all tasks" . $e->getMessage());
            throw new Exception("there is something wrong in server");
        }
    }
    /**
     * create  a  new task
     * @param array $taskData 
     * @param Project $project 
     * @return   TaskResource $task
     */
    public function createTask($taskData, $project)
    {

        try {
            $authService = new AuthService();
            $active_user_role = $authService->getRoleUserInProject($project);
        } catch (Exception $e) {
            Log::error("error in create task" . $e->getMessage());
            throw new Exception("there is something wrong in server");
        }
        if ($active_user_role == "manager") {
            try {
                $taskData['project_id'] = $project->id;
                $task = Task::create($taskData);
                $task = TaskResource::make($task);
                return $task;
            } catch (Exception $e) {
                Log::error("error in create task" . $e->getMessage());
                throw new Exception("there is something wrong in server");
            }
        } else {
            throw new HttpResponseException(response()->json(
                [
                    'status' => 'error',
                    'message' => "لا يمكنك اضافة مهمات على هذا المشروع انت لست مدير",
                ],
                403
            ));
        }
    }

    /**
     * show  a  task
     * @param Task $task 
     * @return   $task
     */
    public function oneTask($task)
    {
        try {
            $notes = $task->load('notes');
            $task = TaskResource::make($task);
            return [
                'task' => $task,
                'notes' => $notes
            ];
        } catch (Exception $e) {
            Log::error("error in get a task" . $e->getMessage());
            throw new Exception("there is something wrong in server");
        }
    }
    /**
     * update  a  task
     * @param array $data
     * @param Project $project  
     * @param Task $task 
     * @return  TaskResource $task
     */
    public function updateTask($taskData, $project, $task)
    {
        try {
            $authService = new AuthService();
            $active_user_role = $authService->getRoleUserInProject($project);
        } catch (Exception $e) {
            Log::error("error in create task" . $e->getMessage());
            throw new Exception("there is something wrong in server");
        }
        if ($active_user_role == "manager") {
            try {
                if ($task->status == TaskStatus::FALIED->value) {
                    if (array_key_exists('employee_id', $taskData)) {
                        $task->update($taskData);
                        $task->status = TaskStatus::APPOINTED->value;
                    } else {
                        $task->update($taskData);
                    }
                } else {
                    $task->update($taskData);
                }
                $task = TaskResource::make(Task::find($task->id));
                return $task;
            } catch (Exception $e) {
                Log::error("error in create task" . $e->getMessage());
                throw new Exception("there is something wrong in server");
            }
        } else {
            throw new HttpResponseException(response()->json(
                [
                    'status' => 'error',
                    'message' => 'لا يمكنك تعديل التاسك هذه العملية للمدير',
                ],
                403
            ));
        }
    }
    /**
     * delete  a task
     * @param Project $project 
     * @param Task $task 
     */
    public function deleteTask($project, $task)
    {
        try {
            $authService = new AuthService();
            $active_user_role = $authService->getRoleUserInProject($project);
        } catch (Exception $e) {
            Log::error("error in create task" . $e->getMessage());
            throw new Exception("there is something wrong in server");
        }
        if ($active_user_role == "manager") {
            if (
                $task->status == TaskStatus::APPOINTED->value ||
                $task->status == TaskStatus::STARTWORK->value ||
                $task->status == TaskStatus::ENDWORK->value ||
                $task->status == TaskStatus::STARTTEST->value
            ) {
                throw new HttpResponseException(response()->json(
                    [
                        'status' => 'error',
                        'message' => "لا يمكنك حذف هذه المهمة قد م بدء العمل بها",
                    ],
                    422
                ));
            } else {
                try {
                    $task->delete();
                } catch (Exception $e) {
                    Log::error("error in  delete a task"  . $e->getMessage());
                    throw new Exception("there is something wrong in server");
                }
            }
        } else {
            throw new HttpResponseException(response()->json(
                [
                    'status' => 'error',
                    'message' => 'لا يمكنك حذف المهمة هذه العملية للمدير',
                ],
                403
            ));
        }
    }
    /**
     * start  work a task
     * @param Project $project 
     * @param Task $task 
     */
    public function startWorkTask($project, $task)
    {
        try {
            $user = User::find(Auth::user()->id);
            $authService = new AuthService();
            $active_user_role = $authService->getRoleUserInProject($project);
        } catch (Exception $e) {
            Log::error("error in start work task" . $e->getMessage());
            throw new Exception("there is something wrong in server");
        }
        if ($active_user_role == "developer") {
            try {
                $oldTask = $project->load('oldestTask');
                if ($oldTask->id == $task->id) {
                    $project->status = ProjectStatus::STARTWOEK->value;
                    $project->save();
                }
                $task->status = TaskStatus::STARTWORK->value;
                $task->save();
                $user->projects()->updateExistingPivot($project, ['last_activity' => now()]);
            } catch (Exception $e) {
                Log::error("error in start work task" . $e->getMessage());
                throw new Exception("there is something wrong in server");
            }
        } else {
            throw new HttpResponseException(response()->json(
                [
                    'status' => 'error',
                    'message' => 'لا يمكنك يدء المهمة هذه العملية خاصة بالمطور',
                ],
                403
            ));
        }
    }
    /**
     * end work a task
     * @param Project $project 
     * @param Task $task 
     */

    public function endWorkTask($project, $task)
    {
        try {
            $user = User::find(Auth::user()->id);
            $authService = new AuthService();
            $active_user_role = $authService->getRoleUserInProject($project);
        } catch (Exception $e) {
            Log::error("error in end work task" . $e->getMessage());
            throw new Exception("there is something wrong in server");
        }
        if ($active_user_role == "developer") {
            try {
                $startTaskDate = $task->updated_at;
                $task->status = TaskStatus::ENDWORK->value;
                $task->save();
                $endTaskDate = now();

                $hoursWork = $endTaskDate->diffInHours($startTaskDate);
                $user->projects()->updateExistingPivot($project, ['contribution_hours' => $hoursWork, 'last_activity' => now()]);
            } catch (Exception $e) {
                Log::error("error in end work  task" . $e->getMessage());
                throw new Exception("there is something wrong in server");
            }
        } else {
            throw new HttpResponseException(response()->json(
                [
                    'status' => 'error',
                    'message' => 'لا يمكنك انهاء المهمة هذه العملية خاصة بالمطور',
                ],
                403
            ));
        }
    }
    /**
     * start test a task
     * @param Project $project 
     * @param Task $task 
     */
    public function startTestTask($project, $task)
    {
        try {
            $user = User::find(Auth::user()->id);

            $authService = new AuthService();
            $active_user_role = $authService->getRoleUserInProject($project);
        } catch (Exception $e) {
            Log::error("error in start test task" . $e->getMessage());
            throw new Exception("there is something wrong in server");
        }
        if ($active_user_role == "tester") {
            try {
                $startTaskDate = $task->updated_at;

                $task->status = TaskStatus::STARTTEST->value;
                $task->tester_id = Auth::user()->id;
                $task->save();
                $endTaskDate = now();
                $hoursWork = $endTaskDate->diffInHours($startTaskDate);

                $user->projects()->updateExistingPivot($project, ['contribution_hours' => $hoursWork, 'last_activity' => now()]);
            } catch (Exception $e) {
                Log::error("error in start test task" . $e->getMessage());
                throw new Exception("there is something wrong in server");
            }
        } else {
            throw new HttpResponseException(response()->json(
                [
                    'status' => 'error',
                    'message' => 'لا يمكنك يدء اختبار هذه المهمة العملية خاصة بالمطور',
                ],
                403
            ));
        }
    }
    /**
     * end test a task
     * @param Project $project 
     * @param Task $task 
     */
    public function endTestTask($project, $task)
    {
        try {
            $user = User::find(Auth::user()->id);
            $authService = new AuthService();
            $active_user_role = $authService->getRoleUserInProject($project);
        } catch (Exception $e) {
            Log::error("error in end test task" . $e->getMessage());
            throw new Exception("there is something wrong in server");
        }
        if ($active_user_role == "tester") {
            try {
                $latestTask = $project->load('latestTask');
                if ($latestTask->id == $task->id) {
                    $project->status = ProjectStatus::ENDWORK->value;
                    $project->save();
                }
                $startTaskDate = $task->updated_at;
                $task->status = TaskStatus::ENDTEST->value;
                $task->save();
                $endTaskDate = now();
                $hoursWork = $endTaskDate->diffInHours($startTaskDate);
                $user->projects()->updateExistingPivot($project, ['contribution_hours' => $hoursWork, 'last_activity' => now()]);
            } catch (Exception $e) {
                Log::error("error in end test task" . $e->getMessage());
                throw new Exception("there is something wrong in server");
            }
        } else {
            throw new HttpResponseException(response()->json(
                [
                    'status' => 'error',
                    'message' => 'لا يمكنك انهاء اختبار هذه المهمة العملية خاصة بالمطور',
                ],
                403
            ));
        }
    }
    /**
     * end a task
     * @param Project $project 
     * @param Task $task 
     */
    public function endTask($project, $task)
    {
        try {
            $user = User::find(Auth::user()->id);
            $authService = new AuthService();
            $active_user_role = $authService->getRoleUserInProject($project);
        } catch (Exception $e) {
            Log::error("error in end task" . $e->getMessage());
            throw new Exception("there is something wrong in server");
        }
        if ($active_user_role == "manger") {
            try {
                $task->status = TaskStatus::ENDED->value;
                $task->save();
                $user->projects()->updateExistingPivot($project, ['last_activity' => now()]);
            } catch (Exception $e) {
                Log::error("error in create task" . $e->getMessage());
                throw new Exception("there is something wrong in server");
            }
        } else {
            throw new HttpResponseException(response()->json(
                [
                    'status' => 'error',
                    'message' => 'لا يمكنك انهاء  هذه المهمة العملية خاصة بالمدير',
                ],
                403
            ));
        }
    }
}