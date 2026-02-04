<div class="app-menu navbar-menu">
    <!-- LOGO -->
    <div class="navbar-brand-box">
        <!-- Dark Logo-->
        <a href="{{ route('admin.dashboard.index') }}" class="logo logo-dark">
            <span class="logo-sm">
                <img src="{{asset(get_app_setting('favicon')??'admin-assets/images/favicon.png')}}" alt="" height="22">
            </span>
            <span class="logo-lg">
                <img src="{{asset(get_app_setting('logo')??'admin-assets/images/logo.png')}}" alt="" height="17">
            </span>
        </a>
        <!-- Light Logo-->
        <a href="{{ route('admin.dashboard.index') }}" class="logo logo-light">
            <span class="logo-sm">
                <img src="{{asset(get_app_setting('favicon')??'admin-assets/images/favicon.png')}}" alt="" width="40">
            </span>
            <span class="logo-lg">
                <img src="{{asset(get_app_setting('logo')??'admin-assets/images/logo.png')}}" alt="" width="140">
            </span>
        </a>
        <button type="button" class="p-0 btn btn-sm fs-20 header-item float-end btn-vertical-sm-hover" id="vertical-hover">
            <i class="ri-record-circle-line"></i>
        </button>
    </div>

    @php
        $menus = \App\Models\Menu::select('name','slug','icon')
        ->where(function($query){
            $query->whereNull('parent')->whereNull('grand');
            $query->whereStatus(1);
            $query->whereHas('rolePermissions',function($query){
                $query->where('role_permissions.role_id','=',auth('admin')->user()->role_id);
                $query->whereRaw("role_permissions.permission_key = concat('browse_',menus.slug)");
            });
        })
        ->orWhere(function($query){
            $query->whereStatus(1);
            $query->orderBy('ordering','asc');
            $query->whereHas('childs',function($query){
                $query->select('slug','parent','name');
                $query->whereStatus(1);

                $query->whereHas('rolePermissions',function($query){
                    $query->where('role_permissions.role_id','=',auth('admin')->user()->role_id);
                    $query->whereRaw("role_permissions.permission_key = concat('browse_',laravel_reserved_0.slug)");
                });
            });
        })->with(['childs'=>function($query){
            $query->select('slug','parent','name', 'grand');
            $query->whereStatus(1);

            $query->whereHas('rolePermissions',function($query){
                $query->where('role_permissions.role_id','=',auth('admin')->user()->role_id);
                $query->whereRaw("role_permissions.permission_key = concat('browse_',menus.slug)");
            })->with(['grands'=>function($query){
                $query->select('slug','parent','name', 'grand');
                $query->with('grands');
                $query->whereStatus(1);

                $query->whereHas('rolePermissions',function($query){
                    $query->where('role_permissions.role_id','=',auth('admin')->user()->role_id);
                    $query->whereRaw("role_permissions.permission_key = concat('browse_',menus.slug)");
                });
            }]);
        }])
        ->orderBy('ordering','asc')
        ->get();
    @endphp

    <div id="scrollbar">
        <div class="container-fluid">

            <div id="two-column-menu"></div>

            <ul class="navbar-nav justify-content-center" id="navbar-nav">
                @foreach ($menus as $menu)
                    @if(!$menu->childs->count() && Route::has("admin.".Str::slug($menu->slug, '-').".index"))
                        <li class="nav-item {{ request()->segment(2) == Str::slug($menu->slug, '-') ? 'mm-active pr-active' : '' }}">
                            <a href="{{ route("admin.".Str::slug($menu->slug, '-').".index")}}" class="nav-link {{ request()->segment(2) == Str::slug($menu->slug, '-') ? 'active' : '' }}">
                                <i class="{{ $menu->icon ?? 'fa fa-arrow-right' }}"></i> 
                                <span>{{ $menu->name }}</span>
                            </a>
                        </li>
                    @endif

                    @if($menu->childs->count())
                    <li class="nav-item {{ $menu->childs->whereIn('slug',str_replace('-', '_', request()->segment(2)))->count() ? 'mm-active pr-active' : '' }}">
                        <a href="#menu-{{ $menu->slug }}" class="nav-link menu-link {{ $menu->childs->whereIn('slug',str_replace('-', '_', request()->segment(2)))->count() ? 'collapsed active' : '' }}" data-bs-toggle="collapse" aria-expanded="false" aria-controls="menu-{{ $menu->slug }}">
                            <i class="{{ $menu->icon ?? 'fa fa-list' }}"></i>
                            <span data-key="{{ $menu->slug }}">{{ $menu->name }}</span>
                        </a>
                        <div class="collapse menu-dropdown {{ $menu->childs->whereIn('slug',str_replace('-', '_', request()->segment(2)))->count() ? 'show' : '' }}" id="menu-{{ $menu->slug }}">
                            <ul class="nav nav-sm flex-column" aria-expanded="false">
                                @foreach($menu->childs as $child)
                                    @if(!$child->grands->count())
                                        @if(Route::has("admin.".Str::slug($child->slug, '-').".index"))
                                            <li class="nav-item {{ $child->slug == str_replace('-', '_', request()->segment(2)) ? 'mm-active pr-active' : '' }}">
                                                <a data-key="{{$child->slug}}" class="nav-link {{ $child->slug == str_replace('-', '_', request()->segment(2)) ? 'active' : '' }}" href="{{ route('admin.'.Str::slug($child->slug, '-').'.index')}}">{{ $child->name }}</a>
                                            </li>
                                        @endif
                                    @endif

                                    @if($child->grands->count())
                                        <li class="nav-item {{ $child->grands->whereIn('slug',str_replace('-', '_', request()->segment(2)))->count() ? 'mm-active pr-active' : '' }}">
                                            <a href="#submenu-{{ $child->slug }}" class="nav-link menu-link {{ $child->grands->whereIn('slug',str_replace('-', '_', request()->segment(2)))->count() ? 'collapsed active' : '' }}" data-bs-toggle="collapse" aria-expanded="false" aria-controls="submenu-{{ $child->slug }}">
                                                <span data-key="{{ $child->slug }}">{{ $child->name }}</span>
                                            </a>
                                            <div class="collapse menu-dropdown {{ $child->grands->whereIn('slug',str_replace('-', '_', request()->segment(2)))->count() ? 'show' : '' }}" id="submenu-{{ $child->slug }}">
                                                <ul class="nav nav-sm flex-column" aria-expanded="false">
                                                    @foreach($child->grands as $grand)
                                                        @if(Route::has("admin.".Str::slug($grand->slug, '-').".index"))
                                                        @if($grand->slug == str_replace('-', '_', request()->segment(2)))
                                                        <script>
                                                            document.getElementById('menu-{{ $menu->slug }}').classList.add('show');
                                                            const div = document.querySelector('[aria-controls="menu-config"]');
                                                            div.setAttribute('aria-expanded', 'true');
                                                        </script>
                                                        @endif
                                                            <li class="nav-item {{ $grand->slug == str_replace('-', '_', request()->segment(2)) ? 'mm-active pr-active' : '' }}">
                                                                <a data-key="{{$grand->slug}}" class="nav-link {{ $grand->slug == str_replace('-', '_', request()->segment(2)) ? 'active' : '' }}" href="{{ route('admin.'.Str::slug($grand->slug, '-').'.index')}}">{{ $grand->name }}</a>
                                                            </li>
                                                        @endif
                                                    @endforeach
                                                </ul>
                                            </div>
                                        </li>
                                    @endif
                                @endforeach
                            </ul>
                        </div>
                    </li>
                    @endif
                @endforeach
            </ul>
        </div>
    </div>
    <div class="sidebar-background"></div>
</div>