@extends('engineer.layouts.master')
@section('content')

  <!-- Navbar -->
@include('engineer.includes.header')
@section('css')
<link rel="stylesheet" href="{{ asset('assets/plugins/daterangepicker/daterangepicker.css') }}">
<link rel="stylesheet" href="{{ asset('assets/plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css') }}">
@stop
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
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header">
                      <h3 class="card-title m-0">{{ __('common.return') }}&nbsp;{{ __('common.tools') }}&nbsp;{{ __('common.history') }}</h3>
                    </div>
                    <div class="card-body">
                        @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul class="m-0">
                                @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                        @endif
                        @if( Session::has('success') || Session::has('error') )

                        <div class="alert {{ Session::has('success') ? 'alert-success': 'alert-danger' }}">
                          {{ @Session::get('success') }}
                          {{ @Session::get('error') }}
                        </div>

                        @endif

                        <table id="returnToolHistoryTable" class="table table-bordered">
                            <thead>
                                <tr>
                                    <th class="text-center">{{ __('common.srno') }}</th>
                                    <th class="text-center">{{ __('common.tool') }}</th>
                                    <th class="text-center">{{ __('common.product_no') }}</th>
                                    <th class="text-center">{{ __('common.serial_no') }}</th>
                                    <th class="text-center">{{ __('common.pickup') }}&nbsp;{{ __('common.type') }}</th>
                                    <th class="text-center">{{ __('common.return') }}&nbsp;{{ __('common.request') }}&nbsp;{{ __('common.date') }}</th>
                                    <th class="text-center">{{ __('common.location') }}</th>
                                    <th class="text-center">{{ __('common.status') }}</th>
                                    </th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
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
@section('script')
<script type="text/javascript" src="{{ asset('assets/plugins/moment/moment.min.js') }}"></script>
<!-- Datepicker -->
<script type="text/javascript" src="{{ asset('assets/plugins/daterangepicker/daterangepicker.js') }}"></script>
<script type="text/javascript" src="{{ asset('assets/plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('assets/plugins/bootstrap-switch/js/bootstrap-switch.min.js') }}"></script>
<script type="text/javascript" src="{{url('/assets/js/engineer/history_return_tool.js')}}"></script>
@stop
