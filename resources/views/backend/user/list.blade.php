@php
    use Illuminate\Support\Facades\Input;
@endphp
<div class="table-responsive">
    <table class="table table-dark table-bordered border-primary text-white" id='tableCategoryBook'>
        <thead>
            <tr>
                <th scope="col">#</th>
                <th scope="col"><a href="?sortBy=name&sortType={{ $sortType }}" id="link">Tên</a></th>
                <th scope="col">Email</th>
                <th scope="col">Nhóm</th>
                <th scope="col">Trạng thái</th>
                <th>Vai trò</th>
                <th scope="col">Action</th>
            </tr>
        </thead>
        <tbody id="sortable">
            @php
                $t = 0;
            @endphp
            @if ($users->count() > 0)
                @foreach ($users as $user)
                    @php
                        $t++;
                    @endphp
                    <tr id='{{ $user->id }}'>
                        <th scope="row">{{ $t }}</th>
                        <td>{{ $user->name }}</td>
                        <td>{{ $user->email }}</td>
                        <td>{{ $user->group->name }}</td>
                        <td>
                            {!! $user->status == 0
                                ? '<button class="btn-danger btn btn-sm">Chưa kích hoạt</button>'
                                : '<button class="btn-success btn btn-sm">Kích hoạt</button>' !!}
                        </td>
                        <td>
                            @foreach ($user->roles as $role)
                                <span class='badge badge-danger'>{{ $role->name }}</span>
                            @endforeach
                        </td>
                        <td>
                            {{-- @can('edit_user', $user->id)
                                <a href="{{ route('admin.users.edit', $user->id) }}" class="btn btn-sm btn-primary btn-edit"
                                    data-id='{{ $user->id }}'>Sửa</a>
                                <br>
                            @endcan --}}
                            @if (Auth::id() != $user->id)
                                <a href="{{ route('admin.users.edit', $user->id) }}"
                                    class="btn btn-sm btn-primary btn-edit" data-id='{{ $user->id }}'>Sửa</a>
                            @endif
                            @if (Auth::id() != $user->id)
                                <a href="{{ route('admin.users.destroy', $user->id) }}"
                                    class="btn btn-sm btn-primary btn-delete mt-2">Xóa</a>
                            @endif
                        </td>
                    </tr>
                @endforeach
            @else
                <tr>
                    <td colspan="7" style="text-align: center">Không có bản ghi nào !</td>
                </tr>
            @endif
        </tbody>
    </table>
    {{ $users->withQueryString()->links() }}
    {{-- {{ $users->links() }} --}}
</div>
<script>
    const endpoint = "https://quocmanh.com/Laravel/Auth/admin/users/list";

    $(document).ready(function() {
        $('#tableCategoryBook').DataTable({
            "language": {
                "url": "https://cdn.datatables.net/plug-ins/1.13.1/i18n/vi.json"
            }
        });
    });
</script>
<script>
    $('#link').click(function(e) {
        e.preventDefault();
        let url = $(this).attr('href');
        let params = new URLSearchParams(url);
        let sortBy = params.get('sortBy');
        let sortType = params.get('sortType');

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $.ajax({
            type: 'GET',
            url: `${endpoint}`,
            data: {
                sortBy: sortBy,
                sortType: sortType
            },
            dataType: 'html',
            success: function(data) {
                $('.list-user').html(data);
            }
        });
    });

    $(document).ready(function() {
        $('.pagination a').on('click', function(e) {
            e.preventDefault();
            let page = $(this).attr('href').split('page=')[1];
            getData(page);
        });
    });

    function getData(page) {
        $.ajax({
            url: `${endpoint}?page=${page}`,
            type: 'get',
            dataType: 'html',
            success: function(data) {
                $('.list-user').html(data);
            }
        });
    }
</script>

<script>
    $(function() {
        $("#sortable").sortable({
            placeholder: 'ui-state-highlight',
            update: function(event, ui) {
                let array_id = [];
                $("#sortable tr").each(function() {
                    array_id.push($(this).attr('id'));
                })


                $.ajax({
                    url: '{{ route('admin.users.list') }}',
                    type: 'POST',
                    dataType: "html",
                    data: {array_id:array_id},
                    headers: {
                        'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(data) {
                        $('.list-user').html(data);
                    },
                    error: function(error) {
                        Swal.fire({
                            title: 'Error',
                            text: 'Sắp xếp người dùng không thành công',
                            icon: 'error',
                            confirmBbuttonText: 'Cool'
                        })
                    }
                })
            }
        });
    });
</script>
