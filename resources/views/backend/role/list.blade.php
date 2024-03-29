<p>Tổng tất cả: {{ $allRoleNum }} bản ghi</p>
<p>Hiện tại có: {{ $roleCount }} bản ghi ở trang {{ $page }}</p>
<table class="table table-dark table-bordered border-primary text-white">
    <thead>
        <tr>
            <th scope="col">#</th>
            <th scope="col">Tên vai trò </th>
            <th scope="col">Mô tả vai trò</th>
            <th>Trạng thái</th>
            <th scope="col">Action</th>
        </tr>
    </thead>
    <tbody>
        @if ($roles->count() > 0)
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
                    <td>{!! $role->status == 0 ? '<a href="#"
                        class="btn btn-sm btn-danger">Chưa kích hoạt</a>' : '<a href="#"
                        class="btn btn-sm btn-primary">Kích hoạt</a>' !!}</td>
                    <td>
                        <a href="{{ route('admin.roles.edit', $role->id) }}"
                            class="btn btn-sm btn-primary btn-edit">Sửa</a>
                        <br>
                        <a href="{{ route('admin.roles.destroy', $role->id) }}"
                            class="btn btn-sm btn-primary btn-delete mt-2">Xóa</a>
                    </td>
                </tr>
            @endforeach
        @else
                <tr>
                    <td colspan="5">
                        <p class='text-center'>Không tìm thấy bản ghi nào !</p>
                    </td>
                </tr>
        @endif
    </tbody>

</table>
@if ($roleCount)
<nav aria-label="Page navigation example">
    <ul class="pagination">
        <?php
        if ($page > 1) {
            $prevPage = $page - 1;
            echo '<li class="page-item"><a class="page-link" href="https://quocmanh.com/Laravel/Auth/admin/roles?page=' . $prevPage . '">Trước</a></li>';
        }
        ?>
        <?php
            $begin = $page - 2;
            if($begin < 1){
                $begin = 1;
            }
            $end = $page + 2;
            if( $end > $maxPage){
                $end = $maxPage;
            }
            for($index=$begin; $index <= $end; $index++){
        ?>
        <li class="page-item <?php echo $index == $page ? 'active' : false; ?>">
            <a class="page-link" href=<?php echo 'https://quocmanh.com/Laravel/Auth/admin/roles?page=' . $index; ?>><?php echo $index; ?></a>
        </li>
        <?php
            }
        ?>
        <?php
        if ($page < $maxPage) {
            $nextPage = $page + 1;
            echo '<li class="page-item"><a class="page-link" href="https://quocmanh.com/Laravel/Auth/admin/roles?page=' . $nextPage . '">Sau</a></li>';
        }
        ?>
    </ul>
</nav>
@endif

{{-- {{ $roles->links() }} --}}
<script>
    function searchFilter(page) {
        let search = $('#txtSearch').val();
        let filterBy = $('#filterBy').val();
        $(document).ready(function() {
            $.ajax({
                url: '{{ route('admin.roles.list') }}?page=' + page,
                data: {
                    search: search,
                    status: filterBy
                },
                type: 'get',
                dataType: 'html',
                success: function(data) {
                    $('.list-role').html(data);
                    $('#txtSearch').trigger("reset");
                }
            });
        })
    }
</script>

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
            let page = $(this).attr('href').split('page=')[1];
            loadRole(page);
        });
    });
</script>
