<?php

?>
<aside class="main-sidebar sidebar-dark-primary elevation-4">
  <!-- Brand Logo -->
  <a href="javascript:void(0);" class="brand-link">
    <span class="brand-text font-weight-light">{{ config('app.name') }}</span>
  </a>
  <!-- Sidebar -->
  <div class="sidebar">
    <!-- Sidebar user panel (optional) -->
    <div class="user-panel mt-3 pb-3 mb-3 d-flex">
      <div class="image">
        <img src="{{ Auth::user()->image_url ? Auth::user()->image_url : asset('assets/dist/img/user2-160x160.jpg') }}" class="img-circle elevation-2 sidebar-image" alt="User Image">
      </div>
      <div class="info">
        <a href="#" class="d-block">{{ Auth::user()->name }}</a>
      </div>
    </div>
    <!-- SidebarSearch Form -->
    <!-- Sidebar Menu -->
    <nav class="mt-2">
      <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
        <!-- Add icons to the links using the .nav-icon class
        with font-awesome or any other icon font library -->
        <li class="nav-item menu-open">
          <a href="{{ url('admin/dashboard') }}" class="nav-link {{ request()->is('admin/dashboard*') ? 'active' : '' }}">
            <i class="nav-icon fas fa-tachometer-alt"></i>
            <p>
              {{ __('admin.sidebar.menu.dashboard') }}
            </p>
          </a>
        </li>
        @if(Auth::user()->role == 'admin')
        <li class="nav-item">
          <a href="{{ url('admin/users/engineer') }}" class="nav-link {{ request()->is('admin/users/engineer*') ? 'active' : '' }}">
            <i class="nav-icon fas fa-users"></i>
            <p>
              {{ __('admin.sidebar.menu.engineers') }}
              
            </p>
          </a> 
        </li>
        @endif
        @if(Auth::user()->role == 'admin')
        <li class="nav-item">
          <a href="{{ url('admin/users/manager') }}" class="nav-link {{ request()->is('admin/users/manager*') ? 'active' : '' }}">
            <i class="nav-icon fas fa-users"></i>
            <p>
              {{ __('admin.sidebar.menu.managers') }}
              
            </p>
          </a> 
        </li>
        @endif
        @if(Auth::user()->role == 'admin')
        <li class="nav-item">
          <a href="{{ url('admin/users/admin') }}" class="nav-link {{ request()->is('admin/users/admin*') ? 'active' : '' }}">
            <i class="nav-icon fas fa-users"></i>
            <p>
              {{ __('admin.sidebar.menu.admin') }}
              
            </p>
          </a> 
        </li>
        @endif
        @if(Auth::user()->role == 'admin')
        <li class="nav-item">
          <a href="{{ route('sites') }}" class="nav-link {{ request()->is('admin/sites*') ? 'active' : '' }}">
            <i class="nav-icon fas fa-sitemap"></i>
            <p>
              {{ __('admin.sidebar.menu.sites') }}
             
            </p>
          </a>
        </li>
        @endif
        @if(Auth::user()->role == 'admin')
        <li class="nav-item">
          <a href="{{ route('modality') }}" class="nav-link  {{ request()->is('admin/modalities*') ? 'active' : '' }}">
            <i class="nav-icon fas fa-list-alt"></i>
            <p>
              {{ __('admin.sidebar.menu.modality') }}
              
            </p>
          </a>
        </li>
        @endif
        @if(Auth::user()->role == 'admin')
        <li class="nav-item">
          <a href="{{ route('tools') }}" class="nav-link {{ request()->is('admin/tools*') ? 'active' : '' }}">
            <i class="nav-icon fas fa-tools"></i>
            <p>
              {{ __('admin.sidebar.menu.tools') }}
              
            </p>
          </a>
        </li>
        @endif
        
        <li class="nav-item">
          <a href="{{ route('toolRequests') }}" class="nav-link {{ request()->is('admin/tool-requests*') ? 'active' : '' }}">
            <i class="nav-icon fas fa-tools"></i>
            <p>
              {{ __('admin.sidebar.menu.manage_tool_requests') }}
              
            </p>
          </a>
        </li>
        
        <li class="nav-item">
          <a href="{{ route('toolReturn') }}" class="nav-link {{ request()->is('admin/tool-return*') ? 'active' : '' }}">
            <i class="nav-icon fas fa-tools"></i>
            <p>
              {{ __('admin.sidebar.menu.manage_tool_return') }}
              
            </p>
          </a>
        </li>
        @if(Auth::user()->role == 'admin')
        <li class="nav-item">
          <a href="{{ route('city') }}" class="nav-link {{ request()->is('admin/cities*') ? 'active' : '' }}">
            <i class="nav-icon fas fa-sitemap"></i>
            <p>
              {{ __('admin.sidebar.menu.city') }}
             
            </p>
          </a>
        </li>
        @endif
        @if(Auth::user()->role == 'admin')
        <li class="nav-item">
          <a href="{{ route('provinces') }}" class="nav-link  {{ request()->is('admin/provinces*') ? 'active' : '' }}">
            <i class="nav-icon fas fa-list-alt"></i>
            <p>
              {{ __('admin.sidebar.menu.provinces') }}
              
            </p>
          </a>
        </li>
        @endif
      </ul>
    </nav>
    <!-- /.sidebar-menu -->
  </div>
  <!-- /.sidebar -->
</aside>