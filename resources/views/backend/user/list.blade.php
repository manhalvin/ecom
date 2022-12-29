<div class="table-responsive">
    <table class="table table-dark table-bordered border-primary text-white" id='tableCategoryBook'>
        <thead>
            <tr>
                <th scope="col">#</th>
                <th scope="col">Tên</th>
                <th scope="col">Email</th>
                <th>Vai trò</th>
                <th scope="col">Action</th>
            </tr>
        </thead>
        <tbody>
            @php
                $t = 0;
            @endphp
            @if ($users->count() > 0)
                @foreach ($users as $user)
                    @php
                        $t++;
                    @endphp
                    <tr>
                        <th scope="row">{{ $t }}</th>
                        <td>{{ $user->name }}</td>
                        <td>{{ $user->email }}</td>
                        <td>
                            @foreach ($user->roles as $role)
                                <span class='badge badge-danger'>{{ $role->name }}</span>
                            @endforeach
                        </td>
                        <td>
                            <a href="{{ route('admin.users.edit', $user->id) }}" class="btn btn-sm btn-primary btn-edit"
                                data-id='{{ $user->id }}'>Sửa</a>
                            <br>
                            @if (Auth::id() != $user->id)
                                <a href="{{ route('admin.users.destroy', $user->id) }}"
                                    class="btn btn-sm btn-primary btn-delete mt-2">Xóa</a>
                            @endif
                        </td>
                    </tr>
                @endforeach
            @else
                <tr>
                    <td colspan="5" style="text-align: center">Không có bản ghi nào !</td>
                </tr>
            @endif
        </tbody>
    </table>
</div>
<script>
    $(document).ready(function() {
        $('#tableCategoryBook').DataTable({
            "language": {
                "url": "https://cdn.datatables.net/plug-ins/1.13.1/i18n/vi.json"
            }
        });
    });
</script>
