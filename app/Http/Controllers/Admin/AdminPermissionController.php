<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Permission;
use App\Models\Role;
use Illuminate\Http\Request;

class AdminPermissionController extends Controller
{
    private $role;
    private $permission;

    public function __construct()
    {
        $this->role = new Role;
        $this->permission = new Permission();
        $this->middleware(function ($request, $next) {
            session(['module_active' => 'permissions']);
            return $next($request);
        });
    }

    public function create()
    {
        return view('backend.permission.create');
    }

    public function store(Request $request)
    {
        $permission = $this->permission->create([
            'name' => $request->module_parent,
            'display_name' => $request->module_parent,
            'parent_id' => 0,
        ]);

        foreach ($request->module_child as $value) {
            $this->permission->create([
                'name' => $value,
                'display_name' => $value,
                'parent_id' => $permission->id,
                'key_code' => $value . '_' . $request->module_parent,
            ]);
        }

        return redirect()->route('admin.permissions.create')->with('status', 'Bạn đã thêm quyền thành công');
    }
}
