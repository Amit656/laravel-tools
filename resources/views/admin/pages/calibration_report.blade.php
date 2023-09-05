@extends('admin.layouts.master')
@section('css')
<link rel="stylesheet" href="{{ asset('assets/plugins/daterangepicker/daterangepicker.css') }}">
<link rel="stylesheet" href="{{ asset('assets/plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css') }}">
@endsection
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
            <h1 class="m-0">{{ __('common.calibration')}} {{ __('common.reports')}}</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="{{ url('admin/dashboard') }}">{{ __('admin.sidebar.menu.dashboard') }}</a></li>
              <li class="breadcrumb-item active">{{$tool->description }}</li>
              <li class="breadcrumb-item active">{{ __('common.calibration')}} {{ __('common.report')}}</li>
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
          <div class="col-12">
            <!-- /.card -->
            <div class="card">
              <div class="card-header">
                <h3 class="card-title">{{$tool->description }} {{ __('common.calibration')}} {{ __('common.report')}} {{ __('common.list')}}</h3>
                <span style="display: block; float: right;">
                  
                  <button class="btn btn-primary m-btn m-btn--icon" data-toggle="modal" data-target="#calibrationReportModal">
                  <span>
                    <i class="fa fa-plus"></i>
                    <span>
                    {{ __('common.add')}} {{ __('common.report')}}
                    </span>
                  </span>
              </div>
              <!-- /.card-header -->
              <div class="card-body">
                <table id="calibrationReportTable" class="table table-bordered table-striped">
                    <thead>
                      <tr>
                        <th>{{ __('common.s_no')}}</th>
                        <th>{{ __('common.calibrated_on')}}</th>
                        <th>{{ __('common.report')}}</th>
                      </tr>
                    </thead>
                </table>
              </div>
              <!-- /.card-body -->
            </div>
            <!-- /.card -->
          </div>
          <!-- ./col -->
        </div>
        <!-- /.row -->

        <!-- /.modal -->

      <div class="modal fade" id="calibrationReportModal">
        <div class="modal-dialog modal-lg">
          <div class="modal-content">
            <div class="modal-header">
              <h4 class="modal-title">{{ __('common.report') }}</h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
              <form name="addCalibrationReport" id="addCalibrationReport">
                
                <div class="row">
                  <div class="col-sm-12">
                    <div class="form-group">
                        <label>{{ __('common.calibrated_on') }}</label>
                        <div class="input-group date" id="calibrated_on" data-target-input="nearest">
                              <input type="text" class="form-control datetimepicker-input" name="calibrated_on" id="calibrated_onInput" data-target="#calibrated_on" name="calibrated_on" >
                              <div class="input-group-append" data-target="#calibrated_on" data-toggle="datetimepicker">
                                  <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                              </div>
                          </div>
                    </div>
                  </div>
                  <div class="col-sm-12">
                    <div class="form-group">
                        <label>{{ __('common.next') }} {{ __('common.cal_due_date') }}</label>
                        <div class="input-group date" id="next_calibration_due_date" data-target-input="nearest">
                              <input type="text" class="form-control datetimepicker-input" name="next_calibration_due_date" id="next_calibration_due_dateInput" data-target="#next_calibration_due_date" name="next_calibration_due_date" value="{{ old('next_calibration_due_date') }}">
                              <div class="input-group-append" data-target="#next_calibration_due_date" data-toggle="datetimepicker">
                                  <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                              </div>
                          </div>
                    </div>
                  </div>  
                  <div class="col-sm-12">
                    <div class="form-group">
                      <label for="tool_condition">{{ __('common.tools')}} {{ __('common.condition')}}<span class="text-danger">*</span></label>
                      <select class="form-control" name="tool_condition"  id="tool_condition">
                            <option value="">{{ __('common.please')}} {{ __('common.select')}} {{ __('common.tool')}} {{ __('common.condition')}}</option>
                        @foreach (config('services.tool_condition') as $toolCondition)
                            <option value="{{$toolCondition}}">{{ __('common.'.$toolCondition)}}</option> 
                            </div>
                        @endforeach
                      </select>
                    </div>
                  </div>
                  <div class="col-sm-12">
                    <div class="form-group">
                        <label>{{ __('common.report') }}</label>
                        <input type="file" name="report" id="report" class="form-control" accept="pdf/*">
                    </div>
                  </div> 
                </div>
                
              </div>
              <div class="modal-footer pull-left">
                <input class="d-none" name="tool_id" id="tool_id" value="{{$tool->id }}" />
                <button type="button" class="btn btn-danger" data-dismiss="modal">{{ __('common.cancel') }}</button>
                <input type="submit" class="btn btn-primary" value="{{ __('common.save') }}">
              </div>
            </form>
          </div>
          <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
      </div>
      <!-- /.modal -->
        
      </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
  @include('admin.includes.footer')
@endsection

@section('script')
<script type="text/javascript" src="{{ asset('assets/plugins/moment/moment.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('assets/plugins/daterangepicker/daterangepicker.js') }}"></script>
<script type="text/javascript" src="{{ asset('assets/plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('assets/js/admin/calibration_report.js') }}"></script>
<script type="text/javascript">
$(function () {
//Date picker
    $('#calibrated_on').datetimepicker({
      format: 'L',
      defaultDate: new Date(),
    });
    $('#next_calibration_due_date').datetimepicker({
    format: 'L'
    });
})
</script>
@endsection
