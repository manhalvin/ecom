@extends('admin.index')
@section('admin')
    <div class="content-wrapper">
        <div class="container-full">

            <!-- Main content -->
            <section class="content">
                <div class="row">
                    <div class="col-xl-3 col-6">

                        <div class="box overflow-hidden pull-up">
                            <div class="box-body">
                                <div class="icon bg-primary-light rounded w-60 h-60">
                                    <i class="text-primary mr-0 font-size-24 mdi mdi-account-multiple"></i>
                                </div>
                                <div>
                                    <p class="text-mute mt-20 mb-0 font-size-16">New Customers</p>
                                    <h3 class="text-white mb-0 font-weight-500">3400 <small class="text-success"><i
                                                class="fa fa-caret-up"></i> +2.5%</small></h3>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="box">
                            <div class="box-header">
                                <h4 class="box-title align-items-start flex-column">
                                    Danh sách người dùng
                                </h4>
                            </div>
                            <div class="box-body">
                                <div class="table-responsive">
                                    <table class="table table-dark table-bordered border-primary text-white" id='user'>
                                        <thead>
                                            <tr>
                                                <th scope="col">#</th>
                                                <th scope="col"><a href="#" id="link">Tên</a></th>
                                                <th scope="col">Email</th>
                                                <th scope="col">Trạng thái</th>
                                                <th>Vai trò</th>
                                                <th>Status</th>
                                                <th>Last Seen</th>
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
                                                            @if(Cache::has('is_online' . $user->id))
                                                                <span class="text-success">Online</span>
                                                            @else
                                                                <span class="text-secondary">Offline</span>
                                                            @endif
                                                        </td>
                                                        <td>{{ \Carbon\Carbon::parse($user->last_seen)->diffForHumans() }}</td>
                                                    </tr>
                                                @endforeach
                                            @else
                                                <tr>
                                                    <td colspan="7" style="text-align: center">Không có bản ghi nào !</td>
                                                </tr>
                                            @endif
                                        </tbody>
                                    </table>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
            <!-- /.content -->
        </div>
    </div>
@endsection
@section('js')
<script>;
    $(document).ready(function() {
        $('#user').DataTable({
            "language": {
                "url": "https://cdn.datatables.net/plug-ins/1.13.1/i18n/vi.json"
            }
        });
    });
</script>
@endsection
