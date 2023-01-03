@extends('admin.index')
@section('admin')
    <div class="content-wrapper ">
        <div class="container-full">
            <!-- Main content -->
            <div id="content" class="container-fluid">
                <div class="card">
                    <div class="card-header font-weight-bold">
                        Thêm vai trò
                    </div>
                    <div class="card-body ">
                        @if (session('status'))
                            <div class="alert alert-success" role="alert">
                                {{ session('status') }}
                            </div>
                        @endif
                        <form action="{{ route('admin.roles.store') }}" method="post">
                            @csrf
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="name">Tên vai trò</label>
                                    <input class="form-control" type="text" name="name" id='slug_update'
                                        value="{{ old('name') }}" placeholder="Vui lòng nhập tên vai trò" onkeyup="ChangeToSlug('slug_update','convert_slug_update')">
                                    @error('name')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label for="slug">Slug</label>
                                    <input class="form-control" type="text" name="slug"
                                        value="{{ old('slug') }}" id="convert_slug_update">
                                    <span style='color:red' class="error slug_error"></span>
                                </div>
                                <div class="form-group">
                                    <label for="description">Mô tả vai trò</label>
                                    <textarea name="description" placeholder="Vui lòng nhập mô tả vai trò" class="form-control content" id="description"
                                        cols="30" rows="2"></textarea>
                                    @error('description')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-12 ">
                                <div class="row">
                                    <div class="col-md-12">
                                        <h3>Chọn các quyền</h3>
                                        @error('permission_id')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input checkall mb-3" type="checkbox" value=""
                                        id="flexCheckDefault">
                                    <label class="form-check-label" for="flexCheckDefault">
                                        Check All
                                    </label>
                                </div>
                                @foreach ($permissions as $item)
                                    <div class="card mb-3">
                                        <div class="card-header">
                                            <div class="form-check">
                                                <input class="form-check-input checkbox_wrapper" type="checkbox"
                                                    value="{{ $item->id }}" id="flexCheckDefault_{{ $item->id }}">
                                                <label class="form-check-label" for="flexCheckDefault_{{ $item->id }}">
                                                    {{ $item->name }}
                                                </label>
                                            </div>
                                        </div>
                                        <div class="row">
                                            @foreach ($item->permissionsChild as $permission)
                                                <div class="card-body text-dark col-md-3 ">
                                                    <h6 class="card-title">
                                                        <input
                                                            class="form-check-input_{{ $permission->id }} checkbox-child"
                                                            type="checkbox" data-id="{{ $item->id }}"
                                                            name="permission_id[]" value="{{ $permission->id }}"
                                                            id="flexCheck_{{ $permission->id }}">
                                                        <label class="form-check-label"
                                                            for="flexCheck_{{ $permission->id }}">
                                                            {{ $permission->display_name }}
                                                        </label>

                                                    </h6>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                @endforeach
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
@section('js')
    <script>
        $(document).ready(function() {
            $('.checkall').click(function(event) {
                if (this.checked) {
                    // Iterate each checkbox
                    $(':checkbox').each(function() {
                        this.checked = true;
                    });
                } else {
                    $(':checkbox').each(function() {
                        this.checked = false;
                    });
                }
            });

            $('.checkbox_wrapper').on('click', function() {
                $(this).parents('.card').find('.checkbox-child').prop('checked', $(this).prop('checked'))
            });
        });
    </script>

<script>
    function ChangeToSlug(slugInput,covertSlug) {
        let slug;

        //Lấy text từ thẻ input title
        slug = document.getElementById(slugInput).value;
        slug = slug.toLowerCase();
        //Đổi ký tự có dấu thành không dấu
        slug = slug.replace(/á|à|ả|ạ|ã|ă|ắ|ằ|ẳ|ẵ|ặ|â|ấ|ầ|ẩ|ẫ|ậ/gi, 'a');
        slug = slug.replace(/é|è|ẻ|ẽ|ẹ|ê|ế|ề|ể|ễ|ệ/gi, 'e');
        slug = slug.replace(/i|í|ì|ỉ|ĩ|ị/gi, 'i');
        slug = slug.replace(/ó|ò|ỏ|õ|ọ|ô|ố|ồ|ổ|ỗ|ộ|ơ|ớ|ờ|ở|ỡ|ợ/gi, 'o');
        slug = slug.replace(/ú|ù|ủ|ũ|ụ|ư|ứ|ừ|ử|ữ|ự/gi, 'u');
        slug = slug.replace(/ý|ỳ|ỷ|ỹ|ỵ/gi, 'y');
        slug = slug.replace(/đ/gi, 'd');
        //Xóa các ký tự đặt biệt
        slug = slug.replace(/\`|\~|\!|\@|\#|\||\$|\%|\^|\&|\*|\(|\)|\+|\=|\,|\.|\/|\?|\>|\<|\'|\"|\:|\;|_/gi, '');
        //Đổi khoảng trắng thành ký tự gạch ngang
        slug = slug.replace(/ /gi, "-");
        //Đổi nhiều ký tự gạch ngang liên tiếp thành 1 ký tự gạch ngang
        //Phòng trường hợp người nhập vào quá nhiều ký tự trắng
        slug = slug.replace(/\-\-\-\-\-/gi, '-');
        slug = slug.replace(/\-\-\-\-/gi, '-');
        slug = slug.replace(/\-\-\-/gi, '-');
        slug = slug.replace(/\-\-/gi, '-');
        //Xóa các ký tự gạch ngang ở đầu và cuối
        slug = '@' + slug + '@';
        slug = slug.replace(/\@\-|\-\@|\@/gi, '');
        //In slug ra textbox có id “slug”
        document.getElementById(covertSlug).value = slug;
    }
</script>
@endsection
