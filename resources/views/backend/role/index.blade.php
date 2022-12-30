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
                                    Danh sách role
                                </h4>
                                <div class="row">
                                    <div class="col-md-12">
                                        <a href="" class="btn btn-sm btn-primary btn-add float-right">Thêm</a>
                                    </div>
                                </div>
                            </div>
                            <div class="box-body">
                                <div class="table-responsive list-role">
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
    <script>
        loadRoleData();

        function loadRoleData() {
            $(document).ready(function() {
                $.ajax({
                    type: 'GET',
                    url: '{{ route('admin.roles.list') }}',
                    dataType: 'html',
                    success: function(data) {
                        $('.list-role').html(data);
                    }
                });
            })
        }
    </script>
@endsection
