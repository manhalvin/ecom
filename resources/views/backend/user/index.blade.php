@extends('admin.index')
@section('admin')
    <div class="content-wrapper">
        <div class="container-full">
            <!-- Main content -->
            <section class="content">
                <div class="row">
                    <div class="col-12">
                        <div class="box">
                            <div class="box-header">
                                <h4 class="box-title align-items-start flex-column">
                                    Danh sách users
                                </h4>
                                <div class="row">
                                    <div class="col-md-12">
                                        @can('create', App\Models\User::class)
                                            <a href="" class="btn btn-sm btn-primary btn-add float-right">Thêm</a>
                                        @endcan
                                    </div>
                                    <form action="{{ route('admin.users.list') }}" id='user-list'>
                                        <div class='row'>
                                            <div class="col-3">
                                                <input type="text" name="search" id="" class='form-control'
                                                    placeholder="Từ khóa tìm kiếm">
                                            </div>
                                            <div class="col-3">
                                                <select name="group_id" class='form-control'>
                                                    <option value="">Tất cả nhóm </option>
                                                    @foreach ($groups as $group)
                                                        <option value="{{ $group->id }}"
                                                            {{ request()->group_id == $group->id ? 'selected' : false }}>
                                                            {{ $group->name }} </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-3">
                                                <select name="status" class='form-control'>
                                                    <option value="">Tất cả trạng thái </option>
                                                    <option value="active"
                                                        {{ request()->status == 'active' ? 'selected' : false }}>Kích hoạt
                                                    </option>
                                                    <option value="unactive"
                                                        {{ request()->status == 'unactive' ? 'selected' : false }}>Chưa kích
                                                        hoạt</option>
                                                </select>
                                            </div>
                                            <div class='col-2'>
                                                <input type="submit" name="btn_submit"
                                                    class="btn btn-sm btn-primary btn-search" value='Tìm kiếm'
                                                    id="btn_search">
                                            </div>
                                        </div> <br>
                                        <div class="row">
                                            <div class="col-12">
                                                <select name="paginate" class='form-control' id='paginate'>
                                                    <option value="">Chọn</option>
                                                    <option value="1"
                                                        {{ request()->paginate == '1' ? 'selected' : false }}>1 </option>
                                                    <option value="2"
                                                        {{ request()->paginate == '2' ? 'selected' : false }}>2</option>
                                                    <option value="5"
                                                        {{ request()->paginate == '5' ? 'selected' : false }}>5</option>
                                                </select>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                            <div class="box-body list-user">
                            </div>
                        </div>
                    </div>
                </div>
            </section>
            <!-- /.content -->
        </div>
    </div>

    <div class="modal fade" id="formModal" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="formModalLabel">Thêm người dùng</h4>
                </div>
                <div class="modal-body">
                    <form action="{{ route('admin.users.store') }}" method="post" enctype="multipart/form-data"
                        id='user-form'>
                        @csrf
                        <div class="form-group">
                            <label for="name">Họ và tên</label>
                            <input class="form-control" type="text" name="name" value="{{ old('name') }}">
                            <span style='color:red' class="error name_error"></span>
                        </div>

                        <div class="form-group">
                            <label for="email">Email</label>
                            <input class="form-control" type="text" name="email" value="{{ old('email') }}">
                            <span style='color:red' class="error email_error"></span>
                        </div>

                        <div class="form-group">
                            <label for="password">Mật khẩu</label>
                            <input class="form-control" type="password" name="password">
                            <span style='color:red' class="error password_error"></span>
                        </div>

                        <div class="form-group">
                            <label for="password-confirm">Xác nhận mật khẩu</label>
                            <input class="form-control" type="password" name="password_confirmation">
                            <span style='color:red' class="error password_confirmation_error"></span>
                        </div>

                        <div class="form-group ">
                            <label for="group_id">Nhóm</label> <br>
                            <select name="group_id" class="form-control">
                                <option value="0">Chọn nhóm</option>
                                @if ($groups->count() > 0)
                                    @foreach ($groups as $group)
                                        <option value="{{ $group->id }}">{{ $group->name }}</option>
                                    @endforeach
                                @endif
                            </select> <br>
                            <span style='color:red' class="error group_id_error"></span>
                        </div>

                        <div class="form-group ">
                            <label for="role_id">Vai trò</label> <br>
                            <select name="role_id[]" class="form-control select2_init" multiple>
                                <option value=""></option>
                                @foreach ($roles as $role)
                                    <option value="{{ $role->id }}">{{ $role->name }}</option>
                                @endforeach
                            </select> <br>
                            <span style='color:red' class="error role_id_error"></span>
                        </div> <br>

                        <input type="submit" name="btn_submit" class="btn btn-primary" value='Submit' id="btn-save">
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="ajaxModel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="userEditForm">Sửa thành viên</h4>
                </div>
                <div class="modal-body formUserEdit">

                </div>
                <div class="modal-footer">
                    <a href="" class="btn btn-primary" id="saveBtn">Submit</a>
                </div>
            </div>
        </div>
    @endsection
    @section('js')
        <script>
            loadUserData();

            function loadUserData() {
                $(document).ready(function() {
                    $.ajax({
                        type: 'GET',
                        url: '{{ route('admin.users.list') }}',
                        dataType: 'html',
                        success: function(data) {
                            $('.list-user').html(data);
                        }
                    });
                })
            }

            $('#paginate').change(function() {
                let paginate = $('select[name="paginate"]').val();
                $.ajax({
                    url: '{{ route('admin.users.list') }}',
                    type: 'GET',
                    data: {
                        paginate: paginate
                    },
                    success: function(data) {
                        $('.list-user').html(data);
                    }
                });
            });


            $(document).ready(function() {
                $('#user-list').on('submit', function(e) {
                    e.preventDefault();
                    let search = $('input[name="search"]').val().trim();
                    let groupId = $('select[name="group_id"]').val();
                    let status = $('select[name="status"]').val();
                    let paginate = $('select[name="paginate"]').val();
                    let actionUrl = $(this).attr('action');
                    let cscfToken = $(this).find('input[name="_token"]').val();

                    $.ajax({
                        url: actionUrl,
                        type: 'GET',
                        dataType: "html",
                        data: {
                            search: search,
                            group_id: groupId,
                            status: status,
                            paginate: paginate,
                            _token: cscfToken
                        },
                        success: function(response) {
                            $('.list-user').html(response);
                            // $('#user-list').trigger("reset");
                        }
                    })

                })
            })

            function loadUserEdit(id) {
                $(document).ready(function() {
                    $.ajax({
                        type: 'GET',
                        url: `https://quocmanh.com/Laravel/Auth/admin/users/ajax/${id}/edit`,
                        dataType: 'html',
                        success: function(data) {
                            $('.formUserEdit').html(data);
                        }
                    });
                })
            }

            $(document).ready(function() {
                $('.select2_init').select2({
                    'placeholder': 'Chọn vai trò'
                });
            });

            $(document).ready(function() {
                $('.btn-add').on('click', function(e) {
                    e.preventDefault();
                    $('.error').text('');
                    $('#btn-save').val("Submit");
                    $('#user-form').trigger("reset");
                    $('#formModal').modal('show');
                })
            })

            $(document).ready(function() {
                $('#user-form').on('submit', function(e) {

                    e.preventDefault();
                    let name = $('input[name="name"]').val().trim();
                    let email = $('input[name="email"]').val().trim();
                    let password = $('input[name="password"]').val();
                    let password_confirmation = $('input[name="password_confirmation"]').val();
                    let roleId = $('select[name="role_id[]"]').val();
                    let groupId = $('select[name="group_id"]').val();
                    let actionUrl = $(this).attr('action');
                    let cscfToken = $(this).find('input[name="_token"]').val();
                    $('.error').text('');

                    $.ajax({
                        url: actionUrl,
                        type: 'POST',
                        dataType: "json",
                        data: {
                            name: name,
                            email: email,
                            password: password,
                            password_confirmation: password_confirmation,
                            group_id: groupId,
                            role_id: roleId,
                            _token: cscfToken
                        },
                        success: function(response) {
                            loadUserData();
                            Swal.fire({
                                title: 'Success',
                                text: 'Thêm người dùng thành công',
                                icon: 'success',
                                confirmBbuttonText: 'Cool'
                            })
                            $('#user-form').trigger("reset");
                            $('#formModal').modal('hide');
                        },
                        error: function(error) {
                            let responseJSON = error.responseJSON.errors;
                            if (Object.keys(responseJSON).length > 0) {
                                for (let key in responseJSON) {
                                    $('.' + key + '_error').text(responseJSON[key][0])
                                }
                            }
                        }
                    })

                })
            })

            $(document).ready(function() {
                $(document).on('click', '.btn-edit', function(e) {
                    e.preventDefault();
                    let href = $(this).attr('href');
                    $('.error').text('');

                    $.ajax({
                        url: href,
                        type: 'GET',
                        dataType: "json",
                        headers: {
                            'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function(response) {
                            let data = response.data;
                            $('#user-form').trigger("reset");
                            loadUserEdit(data.id);
                            $('#ajaxModel').modal('show');
                        },
                        error: function(error) {
                            Swal.fire({
                                title: 'Error',
                                text: error.message,
                                icon: 'error',
                                confirmBbuttonText: 'Cool'
                            })
                        }
                    })

                })
            })

            $(document).ready(function() {
                $(document).on('click', '.btn-delete', function(e) {

                    e.preventDefault();
                    let href = $(this).attr('href');

                    if (confirm("Are you sure you want to delete this record?")) {
                        $.ajax({
                            url: href,
                            type: 'DELETE',
                            dataType: "json",
                            headers: {
                                'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')
                            },
                            success: function(response) {
                                loadUserData();
                                Swal.fire({
                                    title: 'Success',
                                    text: response.message,
                                    icon: 'success',
                                    confirmBbuttonText: 'Cool'
                                })
                            },
                            error: function(error) {
                                Swal.fire({
                                    title: 'Error',
                                    text: error.message,
                                    icon: 'error',
                                    confirmBbuttonText: 'Cool'
                                })
                            }
                        })
                    }

                })
            })
        </script>
    @endsection
