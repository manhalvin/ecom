<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\AdminUserRequest;
use App\Http\Requests\Admin\AdminUserUpdateRequest;
use App\Models\Group;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AdminUserController extends Controller
{
    private $user;
    private $role;
    private $group;

    public function __construct()
    {
        $this->user = new User;
        $this->role = new Role;
        $this->group = new Group;
        $this->middleware(function ($request, $next) {
            session(['module_active' => 'users']);
            return $next($request);
        });
    }

    public function index(Request $request)
    {
        $roles = $this->role->all();
        $groups = $this->group->all();
        return view('backend.user.index', compact('roles', 'groups'));
    }

    public function store(AdminUserRequest $request)
    {
        $user = $this->user->create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'group_id' => $request->group_id,
            'user_id' => Auth::user()->id,
        ]);

        $roleId = $request->input('role_id');
        $user->roles()->attach($roleId);
        return response()->json(['status' => 'success']);
    }

    public function edit(User $user)
    {
        $this->authorize('update', $user);
        $user = $this->user->where('id',$user->id)->first();
        if (!$user) {
            return response()->json(
                [
                    'data' => null,
                    'status_code' => 400,
                    'message' => 'Fetch data no success',
                ]
            );
        }
        return response()->json(
            [
                'data' => $user,
                'status_code' => 200,
                'message' => 'Fetch data success',
            ]
        );
    }

    public function update(AdminUserUpdateRequest $request, $id)
    {
        if (!empty($request->password)) {
            $this->user->find($id)->update([
                'name' => $request->name,
                'password' => Hash::make($request->password),
                'group_id' => $request->group_id,
                'user_id' => Auth::user()->id,
            ]);
        } else {
            $this->user->find($id)->update([
                'name' => $request->name,
                'group_id' => $request->group_id,
                'user_id' => Auth::user()->id,
            ]);
        }
        $user = $this->user->find($id);
        $roleId = $request->input('role');
        $user->roles()->sync($roleId);
        return response()->json(['status' => 'success']);

    }

    public function destroy($id)
    {
        if (Auth::id() == $id) {
            return response()->json(
                [
                    'data' => null,
                    'status_code' => 400,
                    'message' => 'Bạn không thể xóa chính bạn',
                ]
            );
        } else {
            $user = $this->user->find($id);
            if (!$user) {
                return response()->json(
                    [
                        'data' => null,
                        'status_code' => 400,
                        'message' => 'Xóa thành viên không thành công',
                    ]
                );
            }

            $user = $user->delete();
            return response()->json(
                [
                    'data' => $user,
                    'status_code' => 200,
                    'message' => 'Xóa thành viên thành công',
                ]
            );
        }

    }

    public function userList(Request $request)
    {
        $filters = [];
        $search = null;
        if(!empty($request->status)){
            $status = $request->status;
            if($status == 'active'){
                $status = 1;
            }else{
                $status = 0;
            }

            $filters[] = ['users.status', '=', $status];
        }

        if(!empty($request->group_id)){
            $groupId = $request->group_id;
            $filters[] = ['users.group_id', '=', $groupId];
        }

        if(!empty($request->search)){
            $search = $request->search;
        }

        if(!empty($request->paginate)){
            $paginate = $request->paginate;
        }else{
            $paginate = 2;
        }

        $sortType = $request->sortType;
        $sortBy = $request->sortBy;
        $allowSort = ['asc', 'desc'];

        if(!empty($sortType) && in_array($sortType, $allowSort)){
            if($sortType =='desc'){
                $sortType = 'asc';
            }else{
                $sortType = 'desc';
            }
        }else{
            $sortType = 'asc';
        }

        $sortArr = [
            'sortBy' => $sortBy,
            'sortType' => $sortType
        ];


        $users = $this->user->getAllUsers($filters, $search, $sortArr, $paginate);
        $roles = $this->role->all();
        return view('backend.user.list', compact('users', 'roles','sortType'));
    }

    public function userEditAjax($id)
    {
        $user = $this->user->find($id);
        $rolesOfUser = $user->roles;
        $roles = $this->role->all();
        $groups = $this->group->all();
        return view('backend.user.edit', compact('user', 'roles', 'rolesOfUser', 'groups'));
    }
}
