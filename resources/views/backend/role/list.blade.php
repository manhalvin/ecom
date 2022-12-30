<table class="table table-dark table-bordered border-primary text-white">
    <thead>
        <tr>
            <th scope="col">#</th>
            <th scope="col">Tên vai trò </th>
            <th scope="col">Mô tả vai trò</th>
            <th scope="col">Action</th>
        </tr>
    </thead>
    <tbody>
        @php
            $t = 0;
        @endphp
        @foreach ($roles as $role)
            @php
                $t++;
            @endphp
            <tr>
                <th scope="row">{{ $t }}</th>
                <td>{{ $role->name }}</td>
                <td>{{ $role->display_name }}</td>
                <td>
                    <a href="{{ route('admin.roles.edit',$role->id) }}" class="btn btn-sm btn-primary btn-edit">Sửa</a>
                    <br>
                    <a href="{{ route('admin.roles.destroy',$role->id) }}" class="btn btn-sm btn-primary btn-delete mt-2">Xóa</a>
                </td>
            </tr>
        @endforeach
    </tbody>

</table>
{{ $roles->links() }}

<script>
    function loadRole(page) {
        $(document).ready(function() {
            $.ajax({
                url: '{{ route('admin.roles.list') }}?page=' + page,
                type: 'get',
                dataType: 'html',
                success: function(data) {
                    $('.list-role').html(data);
                }
            });
        })
    }

    $(document).ready(function() {
        $('.pagination a').on('click', function(e) {
            e.preventDefault();
            var page = $(this).attr('href').split('page=')[1];
            loadRole(page);
        });
    });
</script>
