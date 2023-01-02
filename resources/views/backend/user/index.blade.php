@extends('admin.index')
@section('admin')
    <style>
        .error {
            color: white;
        }
    </style>

    <div class="content-wrapper">
        <div class="container-full">
            <!-- Main content -->
            <section class="content">
                <div class="row">
                    <div class="col-12">
                        <div class="box">
                            <div class="box-header">
                                <h4 class="box-title align-items-start flex-column">
                                    Danh sách người dùng
                                </h4>
                                <div class="row">
                                    <div class="col-md-12">
                                        @can('create', App\Models\User::class)
                                            <a href="" class="btn btn-sm btn-primary btn-add float-right m-2">Thêm</a>
                                        @endcan
                                    </div>
                                    <form action="{{ route('admin.users.list') }}" id='user-list'>
                                        <div class='row'>
                                            <div class="col-3">
                                                <input type="text" name="search" id="" class='form-control'
                                                    placeholder="Từ khóa tìm kiếm">
                                            </div>
                                            <div class="col-3">
                                                <select name="group" class='form-control'>
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
                                            <div class='col-3'>
                                                <input type="submit" name="btn_submit"
                                                    class="btn btn-primary btn-sm btn-search" value='Tìm kiếm'
                                                    id="btn_search">
                                            </div>
                                            <div class="col-3">
                                                <select name="paginate" class='form-control mt-4' id='paginate'>
                                                    <option value="">Hiển thị số lượng bản ghi</option>
                                                    <option value="20"
                                                        {{ request()->paginate == '20' ? 'selected' : false }}>20 </option>
                                                    <option value="40"
                                                        {{ request()->paginate == '40' ? 'selected' : false }}>40</option>
                                                    <option value="100"
                                                        {{ request()->paginate == '100' ? 'selected' : false }}>100</option>
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
                            <span class="error name_error"></span>
                        </div>

                        <div class="form-group">
                            <label for="email">Email</label>
                            <input class="form-control" type="text" name="email" value="{{ old('email') }}">
                            <span class="error email_error"></span>
                        </div>

                        <div class="form-group">
                            <label for="password">Mật khẩu</label>
                            <input class="form-control" type="password" name="password">
                            <span class="error password_error"></span>
                        </div>

                        <div class="form-group">
                            <label for="password-confirm">Xác nhận mật khẩu</label>
                            <input class="form-control" type="password" name="password_confirmation"> <br>
                            <span class="error password_confirmation_error"></span>
                        </div>

                        <div class="form-group">
                            <label>@lang('Ảnh đại diện:')</label>
                            <input type="file" class="form-control @error('avatar') is-invalid @enderror mb-2"
                                name="avatar" value='{{ old('avatar') }}' id='avatar'
                                onchange="return checkAndFilterAvatar()">
                            <div id='avatarPreview'></div>
                        </div>

                        <div class="form-group">
                            <label>@lang('Quốc tịch')</label>
                            <input type="text" name="country" list='coutries' class="form-control"
                                placeholder="Nhập quốc tịch">
                            <datalist id='coutries'>
                                @if ($countries->count() > 0)
                                    @foreach ($countries as $v)
                                        <option value="{{ $v->name }}">
                                    @endforeach
                                @endif
                            </datalist>
                        </div>

                        <div class="form-group" role="textbox">
                            <div class="row">
                                <div class='col-md-12'>
                                    <label>Tỉnh/thành phố </label>
                                    <select name="province" class="form-control" id='province'>
                                        <option value="">Chọn tỉnh/thành phố
                                        </option>
                                        @if ($provinces->count() > 0)
                                            @foreach ($provinces as $province)
                                                <option value="{{ $province->id }}">
                                                    {{ $province->name }}
                                                </option>
                                            @endforeach
                                        @endif
                                    </select> <br>
                                    <span class="error province_error"></span>
                                </div> <br>
                                <div class='col-12'>
                                    <label>Quận/huyện</label>
                                    <select name="district" id='district' class="form-control">
                                        <option value=""> chọn quận/huyện </option>
                                    </select> <br>
                                    <span class="error district_error"></span>
                                </div>
                                <div class='col-12'>
                                    <label>Phường/xã </label>
                                    <select name="ward" class="form-control" id='ward'>
                                        <option value="">Chọn phường/xã </option>
                                    </select> <br>
                                    <span class="error ward_error"></span>
                                </div>
                            </div>
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
                            <span class="error group_id_error"></span>
                        </div>

                        <div class="form-group ">
                            <label for="role_id">Vai trò</label> <br>
                            <select name="role_id[]" class="form-control select2_init" multiple>
                                <option value=""></option>
                                @if ($roles->count() > 0)
                                    @foreach ($roles as $role)
                                        <option value="{{ $role->id }}">{{ $role->name }}</option>
                                    @endforeach
                                @endif
                            </select> <br>
                            <span class="error role_id_error"></span>
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
            const urlConst = "https://quocmanh.com/Laravel/Auth/admin/users/ajax";

            loadUserData();

            // 1. Load trang danh sách người dùng
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

            // 2. hiển thị số lượng bản ghi
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


            // 3. lọc dữ liệu
            $(document).ready(function() {
                $('#user-list').on('submit', function(e) {
                    e.preventDefault();
                    let search = $('input[name="search"]').val().trim();
                    let groupId = $('select[name="group"]').val();
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
                            $('#user-list').trigger("reset");
                        }
                    })

                })
            })

            // 4. Hiển thị tự động chi tiết người dùng
            function loadUserEdit(id) {
                $(document).ready(function() {
                    $.ajax({
                        type: 'GET',
                        url: `${urlConst}/${id}/edit`,
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

            // 5. Hiển thị modal thêm user
            $(document).ready(function() {
                $('.btn-add').on('click', function(e) {
                    e.preventDefault();
                    $('.error').text('');
                    $('#btn-save').val("Submit");
                    $('#user-form').trigger("reset");
                    $('#formModal').modal('show');
                })
            })

            // 6. Ajax thêm người dùng
            $(document).ready(function() {
                $('#user-form').on('submit', function(e) {

                    e.preventDefault();
                    let name = $('input[name="name"]').val().trim();
                    let email = $('input[name="email"]').val().trim();
                    let password = $('input[name="password"]').val();
                    let password_confirmation = $('input[name="password_confirmation"]').val();
                    let roleId = $('select[name="role_id[]"]').val();
                    let groupId = $('select[name="group_id"]').val();
                    let province = $('select[name="province"]').val();
                    let district = $('select[name="district"]').val();
                    let ward = $('select[name="ward"]').val();
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
                            province: province,
                            district: district,
                            ward: ward,
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

            // 6. Hiển thị modal sửa user
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

            // 7. Xóa người dùng
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

        {{-- 8. Load ajax province --}}
        <script>
            $(document).ready(function() {
                $('#province').on('change', function() {
                    var idCountry = this.value;
                    $("#district").html('');
                    $.ajax({
                        url: "{{ url('admin/users/districts/ajax') }}/" + idCountry,
                        type: "POST",
                        data: {
                            country_id: idCountry,
                            _token: '{{ csrf_token() }}'
                        },
                        dataType: 'json',
                        success: function(result) {
                            $('#district').html('<option value="">Chọn quận/huyện</option>');
                            $.each(result, function(key, value) {
                                $("#district").append('<option value="' + value
                                    .id + '">' + value.name + '</option>');
                            });
                            $('#ward').html('<option value="">Chọn phường/xã</option>');
                        }
                    });
                });
                $('#district').on('change', function() {
                    var idState = this.value;
                    $("#ward").html('');
                    $.ajax({
                        url: "{{ url('admin/users/wards/ajax') }}/" + idState,
                        type: "POST",
                        data: {
                            state_id: idState,
                            _token: '{{ csrf_token() }}'
                        },
                        dataType: 'json',

                        success: function(res) {
                            $('#ward').html('<option value="">Chọn phường/xã </option>');
                            $.each(res, function(key, value) {

                                $("#ward").append('<option value="' + value
                                    .id + '">' + value.name + '</option>');
                            });
                        }
                    });
                });
            });
        </script>

        <script>
            function checkAndFilterAvatar() {
                let userFileImg = document.getElementById('avatar');
                let destOrignalFile = userFileImg.value;
                let allowedExtensions = /(\.jpg|\.jpeg|\.png|\.gif)$/i;
                if (!allowedExtensions.exec(destOrignalFile)) {
                    toastr.error('Bạn vui lòng tải lên tệp có phần mở rộng: .jpeg/.jpg/.png/.gif', {
                        timeOut: 5000
                    })
                    userFileImg.value = '';
                    return false;
                } else {
                    if (userFileImg.files && userFileImg.files[0]) {
                        let reader = new FileReader();
                        reader.onload = function(e) {
                            document.getElementById('avatarPreview').innerHTML = '<img class="w-25 h-25" src="' + e.target
                                .result + '"/>';
                        };
                        reader.readAsDataURL(userFileImg.files[0]);
                    }
                }
            }
        </script>
    @endsection
