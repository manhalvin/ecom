<div class="table-responsive">
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
                        <a href="{{ route('admin.roles.edit', $role->id) }}"
                            class="btn btn-sm btn-primary btn-edit">Sửa</a>
                        <br>
                        <a href="{{ route('admin.roles.destroy', $role->id) }}"
                            class="btn btn-sm btn-primary btn-delete mt-2">Xóa</a>
                    </td>
                </tr>
            @endforeach
        </tbody>

    </table>
</div>
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
{{-- {{ $roles->links() }} --}}

<script>
    function loadRole(page, search) {
        $(document).ready(function() {
            $.ajax({
                url: "{{ route('admin.roles.list') }}?page=" + page,
                type: 'GET',
                data: {
                    search: search
                },
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
            let page = $('.pagination a').attr('href').split('page=')[1];

            loadRole(page, $('#txtSearch').val());
        });
    });

    // function pagination(search) {

    //     $.ajax({
    //         url: '{{ route('admin.roles.list') }}?page=' + page,
    //         type: 'GET',
    //         data: {
    //             search: search
    //         },
    //         dataType: 'html',
    //         success: function(data) {
    //             $('.list-role').html(data);
    //         }
    //     });
    // }
</script>
