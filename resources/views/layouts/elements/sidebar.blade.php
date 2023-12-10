<nav class="sidebar">
    <div class="sidebar-header">
        <a href="#" class="sidebar-brand">
            <h6>{{ trans('panel.site_title') }}</h6>
        </a>
        <div class="sidebar-toggler not-active">
            <span></span>
            <span></span>
            <span></span>
        </div>
    </div>
    <div class="sidebar-body">
        <ul class="nav">

            <li class="nav-item {{ request()->routeIs('admin.home') ? 'active' : '' }}" href="{{ route('admin.home') }}">
                <a href="{{ url('/') }}" class="nav-link">
                    <i class="link-icon" data-feather="clipboard"></i>
                    <span class="link-title">{{ trans('global.dashboard') }}</span>
                </a>
            </li>

            @can('user_management_access')

                <li class="nav-item {{ active_class(['email/*']) }}">
                    <a class="nav-link" data-bs-toggle="collapse" href="#email" role="button"
                        aria-expanded="{{ is_active_route(['email/*']) }}" aria-controls="email">
                        <i class="link-icon" data-feather="users"></i>
                        <span class="link-title">{{ trans('cruds.userManagement.title') }}</span>
                        <i class="link-arrow" data-feather="chevron-down"></i>
                    </a>
                    <div class="collapse {{ show_class(['email/*']) }}" id="email">
                        <ul class="nav sub-menu">
                            @can('permission_access')
                                <li class="nav-item">
                                    <a href="{{ route('admin.permissions.index') }}"
                                        class="nav-link {{ request()->is('admin/permissions') || request()->is('admin/permissions/*') ? 'active' : '' }}">
                                        {{ trans('cruds.permission.title') }}
                                    </a>
                                </li>
                            @endcan

                            @can('role_access')
                                <li class="nav-item">
                                    <a href="{{ route('admin.roles.index') }}"
                                        class="nav-link {{ request()->is('admin/roles') || request()->is('admin/roles/*') ? 'active' : '' }}">
                                        {{ trans('cruds.role.title') }}
                                    </a>
                                </li>
                            @endcan


                            @can('user_access')
                                <li class="nav-item">
                                    <a href="{{ route('admin.users.index') }}"
                                        class="nav-link {{ request()->is('admin/users') || request()->is('admin/users/*') ? 'active' : '' }}">
                                        {{ trans('cruds.user.title') }}
                                    </a>
                                </li>
                            @endcan

                            @can('audit_log_access')
                                <li class="nav-item">
                                    <a href="{{ route('admin.audit-logs.index') }}"
                                        class="nav-link {{ request()->is('admin/audit-logs') || request()->is('admin/audit-logs/*') ? 'active' : '' }}">
                                        {{ trans('cruds.auditLog.title') }}
                                    </a>
                                </li>
                            @endcan





                        </ul>
                    </div>
                </li>

            @endcan

            @if(Gate::check('alta_restaurante_access') or Auth::user()->type=='member')
                <li class="nav-item">
                    <a href="{{ route('admin.alta-restaurantes.index') }}"
                        class="nav-link {{ request()->is('admin/alta-restaurantes') || request()->is('admin/alta-restaurantes/*') ? 'active' : '' }}">
                        <i class="link-icon" data-feather="toggle-right"></i>
                        <span class="link-title"> {{ trans('cruds.altaRestaurante.title') }}</span>
                    </a>
                </li>
            @endif



            @can('team_access')
                <li class="nav-item">
                    <a href="{{ route('admin.team.index') }}"
                        class="nav-link {{ request()->is('admin/team') || request()->is('admin/team/*') ? 'active' : '' }}">
                        
                        <i class="link-icon" data-feather="users"></i>
                        <span class="link-title">{{ trans('cruds.team.title') }}</span>
                    </a>
                </li>
            @endcan

            @if (file_exists(app_path('Http/Controllers/Auth/ChangePasswordController.php')))
                @can('profile_password_edit')
                    <li class="nav-item">
                        <a class="nav-link {{ request()->is('profile/password') || request()->is('profile/password/*') ? 'active' : '' }}"
                            href="{{ route('profile.password.edit') }}">
                            <i class="link-icon" data-feather="hash"></i>
                            <span class="link-title"> {{ trans('global.change_password') }}</span>
                        </a>
                    </li>
                @endcan
            @endif

            @if(Gate::check('alta_empleado_access') or Auth::user()->type=='member')
                <li class="nav-item">
                    <a href="{{ route('admin.alta-empleados.index') }}"
                        class="nav-link {{ request()->is('admin/alta-empleados') || request()->is('admin/alta-empleados/*') ? 'active' : '' }}">
                        <i class="link-icon" data-feather="user-plus"></i>
                        <span class="link-title">{{ trans('cruds.altaEmpleado.title') }}</span>
                    </a>
                </li>
            @endif
            <li class="nav-item">
                <a href="#" class="nav-link"
                    onclick="event.preventDefault(); document.getElementById('logoutform').submit();">
                    <i class="link-icon" data-feather="log-out"></i>
                    <span class="link-title">{{ trans('global.logout') }}</span>
                </a>
            </li>


        </ul>
    </div>
</nav>
<nav class="settings-sidebar">
    <div class="sidebar-body">
        <a href="#" class="settings-sidebar-toggler">
            <i data-feather="settings"></i>
        </a>
        <h6 class="text-muted mb-2">Barra Lateral:</h6>
        <div class="mb-3 pb-3 border-bottom">
            <div class="form-check form-check-inline">
                <label class="form-label form-check-label">
                    <input type="radio" class="form-check-input" name="sidebarThemeSettings" id="sidebarLight"
                        value="sidebar-light" checked>
                    Blanco
                </label>
            </div>
            <div class="form-check form-check-inline">
                <label class="form-label form-check-label">
                    <input type="radio" class="form-check-input" name="sidebarThemeSettings" id="sidebarDark"
                        value="sidebar-dark">
                    Oscuro
                </label>
            </div>
        </div>
    </div>
</nav>
