@php
    $module_active = session('module_active');
@endphp
<aside class="main-sidebar">
    <!-- sidebar-->
    <section class="sidebar">

        <div class="user-profile">
            <div class="ulogo">
                <a href="{{ route('admin.dashboard.index') }}">
                    <!-- logo for regular state and mobile devices -->
                    <div class="d-flex align-items-center justify-content-center">
                        <img src="{{ asset('backend/images/logo-dark.png') }}" alt="">
                        <h3> Admin</h3>
                    </div>
                </a>
            </div>
        </div>

        <!-- sidebar menu-->
        <ul class="sidebar-menu" data-widget="tree">

            <li class="treeview {{ $module_active == 'dashboard' ? 'active' : '' }}">
                <a href="{{ route('admin.dashboard.index') }}">
                    <i data-feather="message-circle"></i>
                    <span>Dashboard</span>
                </a>
            </li> <br>

            @can('users')
                <li class="treeview  {{ $module_active == 'users' ? 'active' : '' }}">
                    <a href="#">
                        <i data-feather="message-circle"></i>
                        <span>Quản lý người dùng</span>
                        <span class="pull-right-container">
                            <i class="fa fa-angle-right pull-right"></i>
                        </span>
                    </a>
                    <ul class="treeview-menu">
                        <li><a href="{{ route('admin.users.index') }}"><i class="ti-more"></i>Danh sách</a></li>
                    </ul>
                </li>
            @endcan


            <li class="treeview  {{ $module_active == 'roles' ? 'active' : '' }}">
                <a href="#">
                    <i data-feather="message-circle"></i>
                    <span>Quản lý vai trò </span>
                    <span class="pull-right-container">
                        <i class="fa fa-angle-right pull-right"></i>
                    </span>
                </a>
                <ul class="treeview-menu">
                    <li><a href="{{ route('admin.roles.index') }}"><i class="ti-more"></i>Danh sách vai trò</a></li>
                    <li><a href="{{ route('admin.roles.create') }}"><i class="ti-more"></i>Thêm vai trò</a></li>
                </ul>
            </li>

            <li class="treeview  {{ $module_active == 'groups' ? 'active' : '' }}">
                <a href="#">
                    <i data-feather="message-circle"></i>
                    <span>Quản lý nhóm</span>
                    <span class="pull-right-container">
                        <i class="fa fa-angle-right pull-right"></i>
                    </span>
                </a>
                <ul class="treeview-menu">
                    <li><a href="{{ route('admin.groups.index') }}"><i class="ti-more"></i>Danh sách nhóm</a></li>
                    <li><a href="{{ route('admin.groups.create') }}"><i class="ti-more"></i>Thêm nhóm</a></li>
                </ul>
            </li>

            <li class="treeview  {{ $module_active == 'permissions' ? 'active' : '' }}">
                <a href="#">
                    <i data-feather="message-circle"></i>
                    <span>Tạo dữ liệu quyền</span>
                    <span class="pull-right-container">
                        <i class="fa fa-angle-right pull-right"></i>
                    </span>
                </a>
                <ul class="treeview-menu">
                    <li><a href="{{ route('admin.permissions.create') }}"><i class="ti-more"></i>Thêm quyền</a>
                    </li>
                </ul>
            </li>

        </ul>
    </section>

    <div class="sidebar-footer">
        <!-- item-->
        <a href="javascript:void(0)" class="link" data-toggle="tooltip" title="" data-original-title="Settings"
            aria-describedby="tooltip92529"><i class="ti-settings"></i></a>
        <!-- item-->
        <a href="javascript:void(0)" class="link" data-toggle="tooltip" title="" data-original-title="Logout"><i
                class="ti-lock"></i></a>
    </div>
</aside>
