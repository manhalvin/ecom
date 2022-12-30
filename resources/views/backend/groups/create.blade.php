@extends('admin.index')
@section('admin')
    <div class="content-wrapper ">
        <div class="container-full">
            <!-- Main content -->
            <div id="content" class="container-fluid">
                <div class="card">
                    <div class="card-header font-weight-bold">
                        Thêm groups
                    </div>
                    <div class="card-body ">
                        @if (session('status'))
                            <div class="alert alert-success" role="alert">
                                {{ session('status') }}
                            </div>
                        @endif
                        <form action="{{ route('admin.groups.store') }}" method="post">
                            @csrf
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="name">Tên nhóm</label>
                                    <input class="form-control" type="text" name="name" id="name"
                                        value="{{ old('name') }}" placeholder="Vui lòng nhập tên nhóm">
                                    @error('name')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>
                            <input type="submit" class="btn btn-primary" value="Submit">
                        </form>
                    </div>
                </div>
            </div>
            <!-- /.content -->
        </div>
    </div>
@endsection

