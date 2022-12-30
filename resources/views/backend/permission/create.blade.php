@extends('admin.index')
@section('admin')
    <div class="content-wrapper ">
        <div class="container-full">
            <!-- Main content -->
            <div id="content" class="container-fluid">
                <div class="card">
                    <div class="card-header font-weight-bold">
                        Thêm quyền
                    </div>
                    <div class="card-body ">
                        @if (session('status'))
                            <div class="alert alert-success" role="alert">
                                {{ session('status') }}
                            </div>
                        @endif
                        <form action="{{ route('admin.permissions.store') }}" method="post">
                            @csrf
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="name">Chọn Module:</label>
                                    <select class='form-control' name="module_parent" id="">
                                        <option value="">Chọn</option>
                                        @foreach (config('permissions.table_module') as $moduleItem)
                                            <option value="{{ $moduleItem }}">{{ $moduleItem }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group">
                                    <div class='row'>
                                        @foreach (config('permissions.module_child') as $moduleChild)
                                        <div class="col-md-3">
                                            <div class="form-check">
                                                <input class="form-check-input checkbox_wrapper" type="checkbox"
                                                    value="{{ $moduleChild  }}" id="flexCheckDefault_{{ $moduleChild  }}" name='module_child[]'>
                                                <label class="form-check-label" for="flexCheckDefault_{{ $moduleChild  }}">
                                                    {{ $moduleChild  }}
                                                </label>
                                            </div>
                                        </div>
                                        @endforeach
                                    </div>
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

