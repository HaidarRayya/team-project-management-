<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use App\Http\Requests\Note\StoreNoteRequest;
use App\Http\Requests\Note\UpdateNoteRequest;
use App\Models\Note;
use App\Models\Project;
use App\Models\Task;
use App\Services\NoteService;
use Illuminate\Http\Request;

class NoteController extends Controller
{
    protected $noteService;
    public function __construct(NoteService $noteService)
    {
        $this->noteService = $noteService;
    }
    /**
     * Display a listing of the resource.
     */
    /**
     * get all notes 
     * @param Project $project
     * @param Task $task
     *
     * @return response  of the status of operation :notes
     */
    public function index(Project $project, Task $task)
    {
        $notes = $this->noteService->allNotes($task);

        return response()->json([
            'status' => 'success',
            'data' => [
                'notes' =>  $notes
            ],
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    /**
     * create a note 
     * @param StoreNoteRequest $request
     * @param Project $project
     * @param Task $task
     *
     * @return response  of the status of operation :note
     */
    public function store(StoreNoteRequest $request, Project $project, Task $task)
    {
        $noteData = $request->validated();
        $note = $this->noteService->createNote($noteData, $project, $task);

        return response()->json([
            'status' => 'success',
            'data' => [
                'note' =>  $note
            ],
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    /**
     * get a note 
     * @param Project $project
     * @param Task $task
     * @param  Note $note
     * @return response  of the status of operation :note
     */
    public function show(Project $project, Task $task, Note $note)
    {
        $note = $this->noteService->oneNote($note);
        return response()->json([
            'status' => 'success',
            'data' => [
                'note' =>    $note
            ],
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    /**
     * update a note 
     * @param UpdateNoteRequest $request
     * @param Project $project
     * @param Task $task
     * @param  Note $note
     * @return response  of the status of operation :note
     */
    public function update(UpdateNoteRequest $request, Project $project, Task $task, Note $note)
    {
        $noteData = $request->validated();
        $note = $this->noteService->updateNote($noteData, $project, $note);

        return response()->json([
            'status' => 'success',
            'data' => [
                'note' =>   $note
            ],
        ],  200);
    }

    /**
     * Remove the specified resource from storage.
     */
    /**
     * delete a note 
     * @param Project $project
     * @param Task $task
     * @param  Note $note
     * @return response  of the status of operation :note
     */
    public function destroy(Project $project, Task $task, Note $note)
    {
        $this->noteService->deleteNote($project, $note);

        return response()->json(status: 204);
    }
}