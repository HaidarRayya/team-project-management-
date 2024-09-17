<?php

namespace App\Services;

use App\Http\Resources\NoteResourse;
use App\Http\Resources\ProjectResource;
use App\Models\Note;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Exceptions\HttpResponseException;

class NoteService
{
    /**
     * get all notes
     * @param  Task $task 
     * @return string  active_user_role
     * 
     */
    public function allNotes($task)
    {
        try {
            $notes = $task->load('notes');
            $notes = NoteResourse::collection($notes->notes);
            return $notes;
        } catch (Exception $e) {
            Log::error("error in get all notes" . $e->getMessage());
            throw new Exception("there is something wrong in server");
        }
    }
    /**
     * create a note
     * @param  array $noteData 
     * @param  Project $project 
     * @param  Task $task 
     * @return NoteResourse  note
     * 
     */
    public function createNote($noteData, $project, $task)
    {

        try {
            $authService = new AuthService();
            $active_user_role = $authService->getRoleUserInProject($project);
        } catch (Exception $e) {
            Log::error("error in create note" . $e->getMessage());
            throw new Exception("there is something wrong in server");
        }
        if ($active_user_role == "tester") {
            try {
                $note = Note::create([
                    'tester_id' => Auth::user()->id,
                    'task_id' => $task->id,
                    'descripation' => $noteData['descripation']
                ]);
                $note = NoteResourse::make($note);
                return $note;
            } catch (Exception $e) {
                Log::error("error in create note" . $e->getMessage());
                throw new Exception("there is something wrong in server");
            }
        } else {
            throw new HttpResponseException(response()->json(
                [
                    'status' => 'error',
                    'message' => 'لا يمكنك  اضافة ملاحظة على هذه  المهمة العملية خاصة بالمتخبر',
                ],
                422
            ));
        };
    }
    /**
     * get a note 
     * @param  Note $note 
     * @return NoteResourse  note
     * 
     */
    public function oneNote($note)
    {
        try {
            $note = NoteResourse::make($note);
            return $note;
        } catch (Exception $e) {
            Log::error("error in get one note" . $e->getMessage());
            throw new Exception("there is something wrong in server");
        }
    }
    /**
     * update a note 
     * @param  array $noteData 
     * @param  Project $project 
     * @param  Task $task 
     * @return NoteResourse  note
     */
    public function updateNote($noteData, $project, $note)
    {

        try {
            $authService = new AuthService();
            $active_user_role = $authService->getRoleUserInProject($project);
        } catch (Exception $e) {
            Log::error("error inupdate a note " . $e->getMessage());
            throw new Exception("there is something wrong in server");
        }
        if ($active_user_role == "tester") {
            try {
                $note->update([
                    'descripation' => $noteData['descripation']
                ]);
                $note = NoteResourse::make(Note::find($note->id));
                return $note;
            } catch (Exception $e) {
                Log::error("error in update a note " . $e->getMessage());
                throw new Exception("there is something wrong in server");
            }
        } else {
            throw new HttpResponseException(response()->json(
                [
                    'status' => 'error',
                    'message' => 'لا يمكنك  تعديل ملاحظة على هذه  المهمة العملية خاصة بالمتخبر',
                ],
                403
            ));
        };
    }
    /**
     *delete a note
     * @param  Project $project 
     * @return string  active_user_role
     * 
     */
    public function deleteNote($project, $note)
    {
        try {
            $authService = new AuthService();
            $active_user_role = $authService->getRoleUserInProject($project);
        } catch (Exception $e) {
            Log::error("error in delete a note" . $e->getMessage());
            throw new Exception("there is something wrong in server");
        }
        if ($active_user_role == "tester") {
            try {
                $note->delete();
            } catch (Exception $e) {
                Log::error("error in delete a note" . $e->getMessage());
                throw new Exception("there is something wrong in server");
            }
        } else {
            throw new HttpResponseException(response()->json(
                [
                    'status' => 'error',
                    'message' => 'لا يمكنك  حذف ملاحظة على هذه  المهمة العملية خاصة بالمتخبر',
                ],
                403
            ));
        };
    }
}