<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\AdminUserRequest;
use App\Http\Requests\Admin\AdminUserUpdateRequest;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AdminUserController extends Controller
{
    private $user;
    private $role;

    public function __construct()
    {
        $this->user = new User;
        $this->role = new Role;
        $this->middleware(function ($request, $next) {
            session(['module_active' => 'users']);
            return $next($request);
        });
    }

    public function index(Request $request)
    {
        $users = $this->user->paginate(10);
        $roles = $this->role->all();
        return view('backend.user.index', compact('users', 'roles'));
    }

    public function store(AdminUserRequest $request)
    {
        $user = $this->user->create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        $roleId = $request->input('role_id');
        $user->roles()->attach($roleId);
        return response()->json(['status' => 'success']);
    }

    public function edit($id)
    {
        $user = $this->user->find($id);
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
            ]);
        } else {
            $this->user->find($id)->update([
                'name' => $request->name,
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

    public function userList()
    {
        $users = $this->user->paginate(10);
        $roles = $this->role->all();
        return view('backend.user.list', compact('users', 'roles'));
    }

    public function userEditAjax(User $user)
    {
        $user = $this->user->find($user)->first();
        $rolesOfUser = $user->roles;
        $roles = $this->role->all();
        return view('backend.user.edit',compact('user','roles','rolesOfUser'));
    }
}
