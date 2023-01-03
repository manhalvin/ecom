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

    public function roleList(Request $request)
    {
        // Xử lý phân trang
        $allRoleNum = $this->role->all()->count();
        $search = "";
        $filters = [];

        // 1. Xác định được số lượng bản ghi trên 1 trang
        $perPage = 3;
        // 2. Tính số trang
        $maxPage = ceil($allRoleNum / $perPage);

        // 3. Xử lý sô trang dựa vào phương thức GET
        if (!empty($request->input('page'))) {
            $page = $request->input('page');
            if ($page < 1 || $page > $maxPage) {
                $page = 1;
            }
        } else {
            $page = 1;
        }

        // 4. Tính toán offset trong limit dựa vào biến $page
        $offset = ($page - 1) * $perPage;

        if (!empty($request->status)) {
            $status = $request->status;
            if ($status == 'active') {
                $status = 1;
            } else {
                $status = 0;
            }

            $filters[] = ['status', '=', $status];
        }

        $roles = $this->role->orderBy('created_at', 'asc');

        if (!empty($filters)) {
            $roles = $roles->where($filters);
        }

        if ($request->search) {
            $search = $request->search;
        }

        $roles = $roles->where(function ($query) use ($search) {
            $query->orWhere('name', 'like', '%' . $search . '%');
        })
            ->offset($offset)
            ->limit($perPage)
            ->get();

        $roleCount = $roles->count();

        return view('backend.role.list', compact('roles', 'maxPage', 'page', 'roleCount', 'allRoleNum'));
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
