@extends('engineer.layouts.master')
@section('content')

  <!-- Navbar -->
  @include('engineer.includes.header')
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0"></h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <div class="content">
      <div class="container">
        <div class="row">
          <!-- /.col-md-6 -->
          <div class="col-lg-6">
            <div class="card">
            <a href="{{ url('engineer/request-tools') }}" class="btn btn-primary btn-lg btn-block">{{ __('common.request') }}&nbsp;{{ __('common.tool') }}</a>
            </div>
          </div>
          <div class="col-lg-6">
            <div class="card">
              <a href="{{ url('engineer/return-tools') }}" class="btn btn-primary btn-lg btn-block">{{ __('common.return') }}&nbsp;{{ __('common.tool') }}</a>
            </div>
          </div>
          <!-- /.col-md-6 -->
        </div>
        <!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->

  <!-- Control Sidebar -->
  <aside class="control-sidebar control-sidebar-dark">
    <!-- Control sidebar content goes here -->
  </aside>
  <!-- /.control-sidebar -->
  @include('engineer.includes.footer')


@endsection
