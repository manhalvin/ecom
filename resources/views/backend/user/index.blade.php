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
                                                <th scope="col">Tên</th>
                                                <th scope="col">Email</th>
                                                <th scope="col">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @php
                                                $t = 0;
                                            @endphp
                                            @foreach ($users as $user)
                                                @php
                                                    $t++;
                                                @endphp
                                                <tr>
                                                    <th scope="row">{{ $t }}</th>
                                                    <td>{{ $user->name }}</td>
                                                    <td>{{ $user->email }}</td>
                                                    <td>
                                                        <a href="" class="btn btn-sm btn-primary btn-edit">Sửa</a>
                                                        <br>
                                                        <a href=""
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
