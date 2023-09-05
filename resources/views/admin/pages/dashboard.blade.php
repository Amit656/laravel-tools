@extends('admin.layouts.master')
@section('content')

  <!-- Navbar -->
  @include('admin.includes.header')
  <!-- /.navbar -->

  <!-- Main Sidebar Container -->
  @include('admin.includes.sidebar')

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">{{ __('admin.sidebar.menu.dashboard') }}</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="{{ url('admin/dashboard') }}">{{ __('admin.sidebar.menu.dashboard') }}</a></li>
            </ol>
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
        <!-- Small boxes (Stat box) -->
        <div class="row">
          @if(Auth::user()->role == 'admin')
          <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-info">
              <div class="inner">
                <h3>{{$users}}</h3>

                <p>{{__('common.engineers')}}</p>
              </div>
              <div class="icon">
                <i class="nav-icon fas fa-users"></i>
              </div>
              <a href="{{ url('admin/users/engineer') }}" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
            </div>
          </div>
          <!-- ./col -->
          @endif
          @if(Auth::user()->role == 'admin')
          <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-success">
              <div class="inner">
                <h3>{{$sites}}</h3>

                <p>{{__('common.sites')}}</p>
              </div>
              <div class="icon">
                <i class="nav-icon fas fa-sitemap"></i>
              </div>
              <a href="{{ url('admin/sites') }}" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
            </div>
          </div>
          <!-- ./col -->
          @endif
          @if(Auth::user()->role == 'admin')
          <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-warning">
              <div class="inner">
                <h3>{{$modalities}}</h3>

                <p>{{__('common.modalities')}}</p>
              </div>
              <div class="icon">
                <i class="nav-icon fas fa-tools"></i>
              </div>
              <a href="{{ url('admin/modalities') }}" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
            </div>
          </div>
          <!-- ./col -->
          @endif
          @if(Auth::user()->role == 'admin')
          <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-danger">
              <div class="inner">
                <h3>{{$tools}}</h3>

                <p>{{__('common.tools')}}</p>
              </div>
              <div class="icon">
                <i class="nav-icon fas fa-tools"></i>
              </div>
              <a href="{{ url('admin/tools') }}" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
            </div>
          </div>
          <!-- ./col -->
          @endif
          @if(Auth::user()->role == 'manager')
          <div class="col-lg-6 col-6">
            <!-- small box -->
            <div class="small-box bg-info">
              <div class="inner">
                <h3>{{$requestTools}}</h3>

                <p>{{ __('common.request') }}&nbsp;{{ __('common.tools') }}</p>
              </div>
              <div class="icon">
                <i class="nav-icon fas fa-tools"></i>
              </div>
              <a href="{{ route('toolRequests') }}" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
            </div>
          </div>
          <!-- ./col -->
          @endif
          @if(Auth::user()->role == 'manager')
          <div class="col-lg-6 col-6">
            <!-- small box -->
            <div class="small-box bg-success">
              <div class="inner">
                <h3>{{$returnTools}}</h3>

                <p>{{ __('common.return') }}&nbsp;{{ __('common.tools') }}</p>
              </div>
              <div class="icon">
                <i class="nav-icon fas fa-tools"></i>
              </div>
              <a href="{{ route('toolReturn') }}" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
            </div>
          </div>
          <!-- ./col -->
          @endif
        </div>
        <!-- /.row -->
        
      </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
  @include('admin.includes.footer')


@endsection
