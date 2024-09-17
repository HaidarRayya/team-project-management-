<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use App\Http\Requests\Task\FillterTaskRequest;
use App\Http\Requests\Task\StoreTaskRequest;
use App\Http\Requests\Task\UpdateTaskRequest;
use App\Models\Project;
use App\Models\Task;
use App\Services\TaskService;

class TaskController extends Controller
{
    protected $taskService;
    public function __construct(TaskService $taskService)
    {
        $this->taskService = $taskService;
        // $this->authorizeResource(Task::class, 'task');
    }
    /**
     * Display a listing of the resource.
     */

    /**
     * get all tasks
     *
     * @param Project $project 
     *
     * @return response  of the status of operation : tasks  
     */
    public function index(Project $project)
    {

        $tasks = $this->taskService->allTasks($project);

        return response()->json([
            'status' => 'success',
            'data' => [
                'tasks' =>  $tasks
            ],
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    /**
     * create a new task
     * @param  Project $project
     * @param StoreTaskRequest $request 
     *
     * @return response  of the status of operation : task and message
     */
    public function store(StoreTaskRequest $request, Project $project)
    {
        $taskData = $request->validatedWithCasts()->toArray();

        $task = $this->taskService->createTask($taskData, $project);
        return response()->json([
            'status' => 'success',
            'message' => 'تم انشاء المهمة بنجاح',
            'data' => [
                'task' =>  $task
            ],
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    /**
     * show a specified task
     * @param Project $project
     * @param Task $task 
     *
     * @return response  of the status of operation : task 
     */
    public function show(Task $task)
    {
        $task = $this->taskService->oneTask($task);

        return response()->json([
            'status' => 'success',
            'data' => [
                'task' =>  $task
            ],
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    /**
     * update a specified task
     * @param  Project $project
     * @param UpdateTaskRequest $request
     * @param Task $task 
     *
     * @return response  of the status of operation : task and message 
     */
    public function update(UpdateTaskRequest $request, Project $project, Task $task)
    {
        $taskData = $request->validatedWithCasts()->toArray();
        $task = $this->taskService->updateTask($taskData, $project, $task);

        return response()->json([
            'status' => 'success',
            'message' => 'تم تحديث المهمة بنجاح',
            'data' => [
                'task' =>  $task
            ],
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */

    /**
     * delete a specified task
     * @param  Project $project
     * @param Task $task 
     *
     * @return response  of the status of operation 
     */
    public function destroy(Project $project, Task $task)
    {
        $this->taskService->deleteTask($project, $task);
        return response()->json(status: 204);
    }

    /**
     * start work  a task
     * @param  Project $project
     * @param Task $task 
     *
     * @return response  of the status of operation 
     */
    public function startWorkTask(Project $project, Task $task)
    {
        $this->taskService->startWorkTask($project, $task);

        return response()->json([
            'status' => 'success',
        ], 200);
    }
    /**
     * end work  a task
     * @param  Project $project
     * @param Task $task 
     *
     * @return response  of the status of operation 
     */
    public function endWorkTask(Project $project, Task $task)
    {
        $this->taskService->endWorkTask($project, $task);
        return response()->json([
            'status' => 'success',
        ], 200);
    }
    /**
     * start test  a task
     * @param  Project $project
     * @param Task $task 
     *
     * @return response  of the status of operation 
     */
    public function startTestTask(Project $project, Task $task)
    {
        $this->taskService->startTestTask($project, $task);
        return response()->json([
            'status' => 'success',
        ], 200);
    }
    /**
     * end test  a task
     * @param  Project $project
     * @param Task $task 
     *
     * @return response  of the status of operation 
     */
    public function endTestTask(Project $project, Task $task)
    {
        $this->taskService->endTestTask($project, $task);
        return response()->json([
            'status' => 'success',
        ], 200);
    }
    /**
     * end a task
     * @param  Project $project
     * @param Task $task 
     *
     * @return response  of the status of operation 
     */
    public function endTask(Project $project, Task $task)
    {
        $this->taskService->endTask($project, $task);
        return response()->json([
            'status' => 'success',
        ], 200);
    }
}