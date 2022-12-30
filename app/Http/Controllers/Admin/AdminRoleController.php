<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Permission;
use App\Models\Role;
use Illuminate\Http\Request;

class AdminRoleController extends Controller
{
    private $role;
    private $permission;

    public function __construct()
    {
        $this->role = new Role;
        $this->permission = new Permission();
        $this->middleware(function ($request, $next) {
            session(['module_active' => 'roles']);
            return $next($request);
        });
    }

    public function index()
    {
        return view('backend.role.index');
    }

    public function roleList()
    {
        $roles = $this->role->paginate(2);
        return view('backend.role.list', compact('roles'));
    }

    public function create()
    {
        $permissions = $this->permission->where('parent_id', 0)->get();
        return view('backend.role.create', compact('permissions'));
    }

    public function store(Request $request)
    {
        $role = $this->role->create([
            'name' => $request->name,
            'display_name' => $request->description,
        ]);

        $role->permissions()->attach($request->permission_id);
        return redirect()->route('admin.roles.index')->with('status', 'Bạn đã thêm vai trò thành công');
    }

    public function edit(Request $request, $id)
    {
        $permissionsParent = $this->permission->where('parent_id', 0)->get();
        $role = $this->role->find($id);
        $permissionsChecked = $role->permissions;
        return view('backend.role.edit', compact('permissionsParent', 'role', 'permissionsChecked'));
    }

    public function update(Request $request, $id)
    {
        $role = $this->role->find($id);
        $role->update([
            'name' => $request->name,
            'display_name' => $request->description,
        ]);

        $role->permissions()->sync($request->permission_id);
        return redirect()->route('admin.roles.index')->with('status', 'Bạn đã sửa vai trò thành công');
    }
}
