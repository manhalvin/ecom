@extends('admin.index')
@section('admin')
    <div class="content-wrapper ">
        <div class="container-full">
            <!-- Main content -->
            <div id="content" class="container-fluid">
                <div class="card">
                    <div class="card-header font-weight-bold">
                        Danh sách posts
                    </div>
                    <div class="card-body ">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">Name</th>
                                </tr>
                            </thead>
                            <tbody>
                            @php
                                unset($item);
                                $i = 0;
                            @endphp
                                @foreach ($result as $item )
                                    @php
                                        $i++
                                    @endphp
                                    <tr>
                                        <td>
                                                {{ $i }}
                                        </td>
                                        <td>
                                            {{ str_repeat('||----',$item->level) }} {{ $item->title }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <!-- /.content -->
        </div>
    </div>
@endsection

