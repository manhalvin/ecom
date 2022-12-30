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
                                @if (session('status'))
                                    <div class="alert alert-success" role="alert">
                                        {{ session('status') }}
                                    </div>
                                @endif
                                <h4 class="box-title align-items-start flex-column">
                                    Danh sách groups
                                </h4>
                                <div class="row">
                                    <div class="col-md-12">
                                        <a href="" class="btn btn-sm btn-primary btn-add float-right">Thêm</a>
                                    </div>
                                </div>
                            </div>
                            <div class="box-body">
                                <div class="table-responsive">
                                    <table class="table table-dark table-bordered border-primary text-white">
                                        <thead>
                                            <tr>
                                                <th scope="col">#</th>
                                                <th scope="col">Tên </th>
                                                <th>Người đăng</th>
                                                <th scope="col">Phân quyền</th>
                                                <th scope="col">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @php
                                                $t = 0;
                                            @endphp
                                            @foreach ($groups as $group)
                                                @php
                                                    $t++;
                                                @endphp
                                                <tr>
                                                    <th scope="row">{{ $t }}</th>
                                                    <td>{{ $group->name }}</td>
                                                    <th>
                                                        {{ !empty($group->postBy->name) ? $group->postBy->name:false}}
                                                    </th>
                                                    <td>
                                                        <a href="{{ route('admin.groups.permission', $group->id) }}"
                                                            class="btn btn-sm btn-primary btn-edit">Phân quyền</a>
                                                    </td>
                                                    <td>
                                                        <a href="{{ route('admin.groups.edit', $group->id) }}"
                                                            class="btn btn-sm btn-primary btn-edit">Sửa</a>
                                                        <br>
                                                        <a href="{{ route('admin.groups.destroy', $group->id) }}"
                                                            class="btn btn-sm btn-primary btn-delete mt-2">Xóa</a>
                                                    </td>
                                                </tr>
                                            @endforeach
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
