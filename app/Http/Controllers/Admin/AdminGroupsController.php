<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Group;
use App\Models\Module;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminGroupsController extends Controller
{
    private $group;
    private $module;

    public function __construct()
    {
        $this->group = new Group;
        $this->module = new Module;
        $this->middleware(function ($request, $next) {
            session(['module_active' => 'groups']);
            return $next($request);
        });
    }

    public function index()
    {
        $groups = $this->group->all();
        return view('backend.groups.index', compact('groups'));
    }

    public function create()
    {
        return view('backend.groups.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:groups,name',
        ], [
            'name.required' => "Tên không được để trống",
            'name.unique' => "Tên bị trùng, vui lòng chọn tên khác",
        ]);

        $this->group->create([
            'name' => $request->name,
            'user_id' => Auth::user()->id,
        ]);

        return redirect()->route('admin.groups.index')->with('status', 'Bạn đã thêm nhóm thành công');
    }

    public function show()
    {

    }

    public function edit(Group $group)
    {
        $this->authorize('update', $group);
        $group = $this->group->find($group->id);
        return view('backend.groups.edit', compact('group'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|unique:groups,name,' . $id,
        ], [
            'name.required' => "Tên không được để trống",
            'name.unique' => "Tên nhóm bị trùng, vui lòng chọn tên nhóm khác",
        ]);

        $this->group->find($id)->update([
            'name' => $request->name,
            'user_id' => Auth::user()->id,
        ]);

        return redirect()->route('admin.groups.index')->with('status', 'Bạn đã sửa nhóm thành công');
    }

    public function destroy(Group $group)
    {
        $userCount = $group->users->count();
        if ($userCount == 0) {
            $group = $this->group->destroy($group->id);
            return redirect()->route('admin.groups.index')->with('status', 'Bạn đã xóa nhóm thành công');
        }

        return redirect()->route('admin.groups.index')->with('status', 'Trong nhóm vẫn còn '.$userCount.' người dùng');
    }

    public function permission(Group $group)
    {
        $modules = $this->module->all();
        $roleListArr = [
            'view' => 'Xem',
            'add' => 'Thêm',
            'edit' => 'Sửa',
            'delete' => 'Xóa'
        ];

        $roleJson = $group->permissions;
        if(!empty($roleJson)){
            $roleArr = json_decode($roleJson, true);
        }else{
            $roleArr = [];
        }
        return view('backend.groups.permission', compact('group','modules','roleListArr','roleArr'));
    }

    public function postPermission(Group $group, Request $request)
    {
        if(!empty($request->role)){
            $roleArr = $request->role;
        }else{
            $roleArr = [];
        }
        $roleJson = json_encode($roleArr);
        $group->permissions = $roleJson;
        $group->save();
        return redirect()->route('admin.groups.index')->with('status', 'Bạn đã phân quyền thành công');
    }

}
