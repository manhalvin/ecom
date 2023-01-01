<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\API\AdminUserResource;
use App\Http\Resources\API\UserCollection;
use App\Models\User;
use Illuminate\Http\Request;

class AdminUserController extends Controller
{
    private $user;

    public function __construct()
    {
        $this->user = new User;
    }
    public function index(Request $request)
    {
        // return $method = $request->method();

        $where = [];
        if($request->name){
            $where[] = ['name', 'like', '%' . $request->name . '%'];
        }

        if($request->email){
            $where[] = ['email', 'like', '%' . $request->email . '%'];
        }

        $users = $this->user->orderBy('name', 'desc');
        if(!empty($where)){
            $users = $users->where($where);
        }
        // $users = $users->get();
        $users = $users->with('posts')->paginate(5);

        if($users){
            $statusCode = 200;
            $statusText = 'success';
        }else{
            $statusCode = 204;
            $statusText = 'No data';
        }

        // $users = AdminUserResource::collection($users);
        // $users = new AdminUserResource($user) -> show
        $users = new UserCollection($users, $statusCode, $statusText);

        $response = [
            'status_code' => $statusCode,
            'status' => $statusText,
            'data' => $users
        ];

        return $response;
    }

    public function show(User $user)
    {
        $user = User::with('posts')->find($user->id);
        if(!$user){
            $status = 'no data';
        }else{
            $status = 'success';
        }

        $user = new AdminUserResource($user);
        $response = [
            'status' => $status,
            'data' => $user
        ];

        return $response;

    }
}
