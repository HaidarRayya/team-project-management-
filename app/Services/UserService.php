<?php

namespace App\Services;

use App\Http\Resources\UserResource;
use App\Models\User;
use Exception;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class UserService
{
    /**
     * get all  users
     * @return   UserResource $users
     */
    public function allUsers()
    {
        try {
            $users = User::notAdmin()
                ->get();
            $users = UserResource::collection($users);
            return $users;
        } catch (Exception $e) {
            Log::error("error in get all user" . $e->getMessage());
            throw new Exception("there is something wrong in server");
        }
    }
    /**
     * show  a  user
     * @param User $user 
     * @return  array of  TaskResource $tasks and  UserResource $user 
     */
    public function oneUser($user)
    {
        try {
            $user = UserResource::make($user);
            return [
                'user' => $user,
            ];
        } catch (Exception $e) {
            Log::error("error in get a user");
            throw new Exception("there is something wrong in server");
        }
    }
    /**
     * delete  a user
     * @param User $user 
     */
    public function deleteUser($user)
    {
        try {
            if ($user->role == 'employee')
                $tasks = $user->load('employee_tasks')->employee_tasks;
            else if ($user->role == 'manager')
                $tasks = $user->load('tasks')->tasks;
        } catch (Exception $e) {
            Log::error("error in delete user");
            throw new Exception("there is something wrong in server");
        }
        if ($tasks->isEmpty())
            try {
                $user->delete();
            } catch (Exception $e) {
                Log::error("error in delete user");
                throw new Exception("there is something wrong in server");
            }
        else {
            if ($user->role == 'manager') {
                try {
                    foreach ($tasks as $task)
                        $task->update(['manager_id' => Auth::user()->user_id]);
                    $user->delete();
                } catch (Exception $e) {
                    Log::error("error in delete user");
                    throw new Exception("there is something wrong in server");
                }
            } else {
                throw new HttpResponseException(response()->json(
                    [
                        'status' => 'error',
                        'message' => "لا يمكنك حذف هذا الموظف لديه مهمات موكله له",
                    ],
                    422
                ));
            }
        }
    }

    /**
     * get all deleted users
     * @return UserResource $users
     */

    public function allDeletedUser()
    {
        try {
            $users = User::onlyTrashed()->get();
            $users = UserResource::collection($users);
            return $users;
        } catch (Exception $e) {
            Log::error("error in get all deleted user");
            throw new Exception("there is something wrong in server");
        }
    }
    /**
     * restore a user
     * @param int $user_id      
     * @return UserResource $user
     */
    public function restoreUser($user_id)
    {
        try {
            $user = User::withTrashed()->find($user_id);
            $user->restore();
            return UserResource::make($user);
        } catch (Exception $e) {
            Log::error("error in get restore user");
            throw new Exception("there is something wrong in server");
        }
    }
}