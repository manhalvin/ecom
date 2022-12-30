@extends('admin.index')
@section('admin')
    <div class="content-wrapper ">
        <div class="container-full">
            <!-- Main content -->
            <div id="content" class="container-fluid">
                <div class="card">
                    <div class="card-header font-weight-bold">
                        Phân quyền nhóm: {{ $group->name }}
                    </div>
                    <div class="card-body ">
                        @if (session('status'))
                            <div class="alert alert-success" role="alert">
                                {{ session('status') }}
                            </div>
                        @endif
                        <form action="" method='POST'>
                            @csrf
                            <table class='table table-bordered'>
                                <thead>
                                    <tr>
                                        <th>Module</th>
                                        <th>Quyền</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if ($modules->count() > 0)
                                        @foreach ($modules as $module)
                                            <tr>
                                                <td>{{ $module->content }}</td>
                                                <td>
                                                    <div class="row">
                                                        @if (!empty($roleListArr))
                                                            @foreach ($roleListArr as $roleName => $roleLabel)
                                                                <div class="col-md-2">
                                                                    <div class="form-check">
                                                                        <input class="form-check-input" type="checkbox"
                                                                            value='{{ $roleName }}'
                                                                            id="role_{{ $module->title }}_{{ $roleName }}"
                                                                            name="role[{{ $module->title }}][]" {{ isRole($roleArr,$module->title,$roleName) ? 'checked' : false }} >
                                                                        <label class="form-check-label"
                                                                            for="role_{{ $module->title }}_{{ $roleName }}">
                                                                            {{ $roleLabel }}
                                                                        </label>
                                                                    </div>
                                                                </div>
                                                            @endforeach
                                                        @endif
                                                        @if ($module->title == 'groups')
                                                            <div class="col-md-2">
                                                                <div class="form-check">
                                                                    <input class="form-check-input" type="checkbox"
                                                                        value='permission'
                                                                        id="role_{{ $module->title }}_permission"
                                                                        name="role[{{ $module->title }}][]" {{ isRole($roleArr,$module->title,'permission') ? 'checked' : false }}>
                                                                    <label class="form-check-label"
                                                                        for="role_{{ $module->title }}_permission">
                                                                        Phân quyền
                                                                    </label>
                                                                </div>
                                                            </div>
                                                        @endif
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    @endif
                                </tbody>
                            </table>
                            <input type="submit" value='Phân quyền'>
                        </form>
                    </div>
                </div>
            </div>
            <!-- /.content -->
        </div>
    </div>
@endsection
