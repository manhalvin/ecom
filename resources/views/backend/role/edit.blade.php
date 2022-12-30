@extends('admin.index')
@section('admin')
    <div class="content-wrapper ">
        <div class="container-full">
            <!-- Main content -->
            <div id="content" class="container-fluid">
                <div class="card">
                    <div class="card-header font-weight-bold">
                        Sửa vai trò
                    </div>
                    <div class="card-body ">
                        @if (session('status'))
                            <div class="alert alert-success" role="alert">
                                {{ session('status') }}
                            </div>
                        @endif
                        <form action="{{ route('admin.roles.update',$role->id) }}"  method="post">
                            @csrf
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="name">Tên vai trò</label>
                                    <input class="form-control" type="text" name="name" id="name"
                                        value="{{ $role->name }}" placeholder="Vui lòng nhập tên vai trò">
                                    @error('name')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label for="description">Mô tả vai trò</label>
                                    <textarea name="description" placeholder="Vui lòng nhập mô tả vai trò" class="form-control" id="description"
                                        cols="30" rows="2">{{ $role->display_name }}</textarea>
                                    @error('description')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-12 ">
                                <div class="row">
                                    <div class="col-md-12">
                                        <h3>Chọn các quyền</h3>
                                        @error('permission_id')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input checkall mb-3" type="checkbox" value=""
                                        id="flexCheckDefault">
                                    <label class="form-check-label" for="flexCheckDefault">
                                        Check All
                                    </label>
                                </div>
                                @foreach ($permissionsParent as $item)
                                    <div class="card mb-3">
                                        <div class="card-header">
                                            <div class="form-check">
                                                <input class="form-check-input checkbox_wrapper" type="checkbox"
                                                    value="{{ $item->id }}" id="flexCheckDefault_{{ $item->id }}">
                                                <label class="form-check-label" for="flexCheckDefault_{{ $item->id }}">
                                                    {{ $item->name }}
                                                </label>
                                            </div>
                                        </div>
                                        <div class="row">
                                            @foreach ($item->permissionsChild as $permission)
                                                <div class="card-body text-dark col-md-3 ">
                                                    <h6 class="card-title">
                                                        <input
                                                            class="form-check-input_{{ $permission->id }} checkbox-child"
                                                            type="checkbox" data-id="{{ $item->id }}"
                                                            name="permission_id[]" value="{{ $permission->id }}"
                                                            id="flexCheck_{{ $permission->id }}"
                                                            {{ $permissionsChecked->contains('id', $permission->id) ? 'checked' : '' }}>
                                                        <label class="form-check-label"
                                                            for="flexCheck_{{ $permission->id }}">
                                                            {{ $permission->display_name }}
                                                        </label>

                                                    </h6>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            <input type="submit" class="btn btn-primary" value="Submit">
                        </form>
                    </div>
                </div>
            </div>
            <!-- /.content -->
        </div>
    </div>
@endsection
@section('js')
    <script>
        $(document).ready(function() {
            $('.checkall').click(function(event) {
                if (this.checked) {
                    // Iterate each checkbox
                    $(':checkbox').each(function() {
                        this.checked = true;
                    });
                } else {
                    $(':checkbox').each(function() {
                        this.checked = false;
                    });
                }
            });

            $('.checkbox_wrapper').on('click', function() {
                $(this).parents('.card').find('.checkbox-child').prop('checked', $(this).prop('checked'))
            });
        });
    </script>
@endsection
