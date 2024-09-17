<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\UserService;
use Illuminate\Http\Request;

class UserController extends Controller
{
    protected $userService;
    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }
    /**
     * Display a listing of the resource.
     */
    /**
     * get all uasrs
     *
     *
     * @return response  of the status of operation : users  
     */
    public function index(Request $request)
    {
        $user_name = $request->input('user_name');
        $fillter = ['user_name' => $user_name];
        $users = $this->userService->allUsers($fillter);
        return response()->json([
            'status' => 'success',
            'data' => [
                'users' =>  $users
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
     * show a specified user
     *
     * @param User $user 
     *
     * @return response  of the status of operation : user 
     */
    public function show(User $user)
    {
        $data = $this->userService->oneUser($user);

        return response()->json([
            'status' => 'success',
            'data' => [
                ...$data
            ],
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     */

    public function update(Request $request, User $user)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */


    /**
     * delete a specified user
     * @param User $user 
     *
     * @return response  of the status of operation 
     */
    public function destroy(User $user)
    {
        $this->userService->deleteUser($user);
        return response()->json(status: 204);
    }
    /**
     * get all deleted users
     *
     * @return response  of the status of operation : users  
     */
    public function allDeletedUsers()
    {
        $users = $this->userService->allDeletedUser();
        return response()->json([
            'status' => 'success',
            'data' => [
                'users' =>  $users
            ],
        ], 200);
    }

    /**
     * restore a  user
     *
     * @param int $user_id 
     *
     * @return response  of the status of operation : user
     */
    public function restoreUser($user_id)
    {
        $user = $this->userService->restoreUser($user_id);
        return response()->json([
            'status' => 'success',
            'data' => [
                'user' =>  $user
            ],
        ], 200);
    }
}