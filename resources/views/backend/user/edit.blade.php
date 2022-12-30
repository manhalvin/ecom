<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<form id="userEdit" name="userEdit">
    @csrf
    <input type="hidden" name="id" value="{{ $user->id }}">
    <div class="form-group">
        <label for="name">Họ và tên</label>
        <input class="form-control" type="text" name="name" value="{{ $user->name }}" id="name">
        <span style='color:red' class="error nameError"></span>
    </div>

    <div class="form-group">
        <label for="email">Email</label>
        <input class="form-control" type="text" name="email" value="{{ $user->email }}" id="email" disabled>
    </div>

    <div class="form-group">
        <label for="password">Mật khẩu</label> (Không nhập nếu không đổi)
        <input class="form-control" type="password" name="password" id="password">
        <span style='color:red' class="error passwordError"></span>
    </div>

    <div class="form-group ">
        <label for="group_id">Nhóm</label> <br>
        <select name="group_id" class="form-control" id='group_id'>
            <option value="0">Chọn nhóm</option>
            @if ($groups->count() > 0)
                @foreach ($groups as $group)
                    <option value="{{ $group->id }}" {{ $user->group_id == $group->id ? 'selected' :''}}>{{ $group->name }}</option>
                @endforeach
            @endif
        </select> <br>
        <span style='color:red' class="error group_idError"></span>
    </div>

    <div class="form-group ">
        <label for="role">Vai trò</label> <br>
        <select name="role[]" class="form-control select2_init" multiple id='rolesOfUser'>
            <option value=""></option>
            @foreach ($roles as $role)
                <option {{ $rolesOfUser->contains('id', $role->id) ? 'selected' : '' }} value="{{ $role->id }}">
                    {{ $role->name }}</option>
            @endforeach
        </select> <br>
        <span style='color:red' class="error roleError"></span>
    </div> <br>
</form>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
    $(document).ready(function() {
        $('.select2_init').select2();
    });

    $('#saveBtn').click(function(e) {

        e.preventDefault();
        let id = $('input[name="id"]').val();
        let name = $('#name').val().trim();
        let email = $('#email').val().trim();
        let password = $('#password').val();
        let roleId = $('select[name="role[]"]').val();
        let groupId = $('#group_id').val();
        $('.error').text('');

        $.ajax({
            url: "https://quocmanh.com/Laravel/Auth/admin/users" + '/' +
                id,
            type: "PUT",
            dataType: 'json',
            headers: {
                'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')
            },
            data: {
                name: name,
                email: email,
                password: password,
                group_id:groupId,
                role: roleId,
            },
            success: function(data) {
                Swal.fire({
                    title: 'Success',
                    text: 'Sửa thành viên thành công',
                    icon: 'success',
                    confirmBbuttonText: 'Cool'
                })
                setTimeout(function() {
                    loadUserData();
                    $('#userEdit').trigger("reset");
                    $('#ajaxModel').modal('hide');
                }, 2000);
            },
            error: function(error) {
                Swal.fire({
                    title: 'Error',
                    text: 'Sửa thành viên không thành công',
                    icon: 'error',
                    confirmBbuttonText: 'Cool'
                })
                let responseJSON = error.responseJSON.errors;
                if (Object.keys(responseJSON).length > 0) {
                    for (let key in responseJSON) {
                        $('.' + key + 'Error').text(responseJSON[key][0])
                    }
                }
            }
        });
    })
</script>
