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
            <h1 class="m-0">{{ __('common.tools') }}</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">{{ __('admin.sidebar.menu.dashboard') }}</a></li>
              <li class="breadcrumb-item active">{{ __('common.tools') }}</li>
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
            <div class="card">
              <div class="card-header">
                <h3 class="card-title">{{ __('admin.tools.list') }}</h3>
                <div class="float-right">
                             
                  <button class="btn btn-primary m-btn m-btn--icon text-center export-csv">
                  <span>
                    <i class="fa fa-minus"></i>
                    <span>
                      {{__('common.export')}}
                    </span>
                  </span>
                  </button>
               
      
                <button class="btn btn-primary m-btn m-btn--icon" data-toggle="modal" data-target="#AddToolModal">
                  <span>
                    <i class="fa fa-plus"></i>
                    <span>
                      {{__('admin.tools.add')}}
                    </span>
                  </span>
                </button>
                </div>
                
                
              </div>
              <!-- /.card-header -->
              <div class="card-body">
                <table id="toolsTable" class="table table-bordered">
                  <thead>
                  <tr>
                    <th>{{ __('common.s_no') }}</th>
                    <th>{{ __('admin.tools.fields.description') }}</th>
                    <th>{{ __('admin.tools.fields.tool_id') }}</th>
                    <th>{{ __('common.modality') }}</th>
                    <th>{{ __('common.site') }}</th>
                    <th>{{ __('common.serial_no') }}</th>
                    <th>{{ __('admin.tools.fields.asset') }}</th>
                    <th>{{ __('common.cal_due_date') }}</th>
                    <th>{{ __('common.due_in_days') }}</th>
                    <th>{{ __('admin.tools.fields.calibration_date') }}</th>
                    <th>{{ __('admin.tools.fields.status') }}</th>
                    
                    <th>{{ __('admin.tools.fields.sort_field') }}</th>
                    <th>{{ __('admin.tools.fields.QR') }}</th>
                    <th>{{ __('admin.tools.fields.image') }}</th>
                    <th>{{ __('common.action') }}</th>
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
        
        <div class="modal fade" id="AddToolModal">
          <div class="modal-dialog modal-lg">
            <div class="modal-content">
              <div class="modal-header">
                <h4 class="modal-title">{{ __('admin.tools.add') }}</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <div class="modal-body">
                <form name="addEditToolForm" id="addEditToolForm" enctype="multipart/form-data">
                  
                  <div class="row">
                      <div class="col-sm-6">
                        <div class="form-group">
                          {{-- client has request to change tool no as asset --}}
                          <label for="toolId">{{ __('admin.tools.fields.asset') }}<span class="text-danger">*</span></label>
                          <input type="text" class="form-control" id="toolNo" name="toolNo" value="{{old('toolId')}}" placeholder="Enter {{ __('admin.tools.fields.asset') }}">
                        </div>
                      </div>
                      <div class="col-sm-6">
                        <div class="form-group">
                          <label for="modility">{{ __('common.modality') }}</label>
                          <select class="form-control" name="modality" id="modality" required>
                            @foreach ($allModalities as $modality)
                              <option value="{{ $modality->id}}">{{ $modality->name}}</option>
                            @endforeach
                          </select>
                        </div>
                      </div>
                      <div class="col-sm-6">
                        <div class="form-group">
                          <label for="site">{{ __('common.site') }}</label>
                          <select class="form-control" name="site" id="site" required>
                            @foreach ($allSites as $site)
                              <option id="siteName" value="{{ $site->id}}">{{ $site->name}}</option>
                            @endforeach
                          </select>
                        </div>
                      </div>
                      
                      <div class="col-sm-6">
                        <div class="form-group required">
                          <label for="serialNo">{{ __('common.serial_no') }}<span class="text-danger">*</span></label>
                          <input type="text" class="form-control" id="serialNo" name="serialNo" value="{{old('serialNo')}}" placeholder="Enter {{ __('common.serial_no') }}">
                        </div>
                      </div>
                      
                      <div class="col-sm-6">
                        <div class="form-group">
                          <label for="productNo">{{ __('common.product_no') }}<span class="text-danger">*</span></label>
                          <input type="text" class="form-control" id="productNo" name="productNo" value="{{old('productNo')}}" placeholder="Enter {{ __('common.product_no') }}">
                        </div>
                      </div>
                      <div class="col-sm-6">
                        <div class="form-group">
                          <label for="typeOfUse">{{ __('admin.tools.fields.type_of_use') }}<span class="text-danger">*</span></label>
                          <input type="text" class="form-control" id="typeOfUse" name="typeOfUse" value="{{old('typeOfUse')}}" placeholder="Enter {{ __('admin.tools.fields.type_of_use') }}">
                        </div>
                      </div>
                      <div class="col-sm-6">
                        <div class="form-group">
                          <label for="sortField">{{ __('admin.tools.fields.sort_field') }}</label>
                          <input type="text" class="form-control" id="sortField" name="sortField" value="{{old('sortField')}}" placeholder="Enter {{ __('admin.tools.fields.sort_field') }}">
                        </div>
                      </div>
                      <div class="col-sm-6">
                        <div class="form-group">
                          <label for="calibrationDate">{{ __('admin.tools.fields.calibration_date') }}</label>

                          <div class="input-group date" id="calibrationDate" data-target-input="nearest">
                              <input type="text" class="form-control datetimepicker-input" name="calibrationDate" id="calibrationDateInput" data-target="#calibrationDate" name="calibrationDate" value="{{ old("calibrationDate") }}">
                              <div class="input-group-append" data-target="#calibrationDate" data-toggle="datetimepicker">
                                  <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                              </div>
                          </div>
                        </div>
                      </div>
                      <div class="col-sm-6">
                        <div class="form-group">
                          <label for="desc">{{ __('admin.tools.fields.description') }}</label>
                          <textarea name="desc" id="desc" class="form-control" rows="2" placeholder="Enter ..."></textarea>
                        </div>
                      </div>
                      <div class="col-sm-6">
                        <div class="form-group">
                          <label>{{ __('admin.tools.fields.image') }}</label>
                          <input type="file" name="image" class="form-control" accept="image/*">
                        </div>

                        
                        <input type="hidden" name="oldImage" id="oldImage" value="" class="form-control">
                        <img src="" class="img-thumbnail tools-image" style="display: none;" width="100" height="100">
                      
                      </div>
                      <div class="col-sm-6">
                        <div class="form-group">
                          <label>{{ __('admin.tools.fields.QR') }}</label>
                          <input type="file" name="qr_code" class="form-control">
                        </div>
                       
                      </div>
                      <div class="col-sm-6">
                        
                        <label>{{ __('admin.tools.fields.status') }}</label>
                        
                        <div class="form-group">
                          <div class="input-group">
                            
                              @foreach (config('services.status') as $status => $statusLabel)
                                <div class="form-check" style="padding-left: 1.70rem;">
                                  <input class="form-check-input toolStatus" value="{{$status}}" id="toolStatus_{{$status}}" type="radio" name="toolStatus" @if( old("toolStatus", 'available') == $status )checked  @endif>
                                  <label for="{{$status}}" class="form-check-label">{{ $statusLabel }}</label>
                                </div>
                              @endforeach
                            
                          </div>
                        </div>
                      </div>  
                      
                  </div>
              </div>
              <div class="modal-footer pull-left">
                    <input class="d-none" name="toolId" id="toolId" value="" />
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
<script type="text/javascript" src="{{ asset('assets/js/admin/tools.js') }}"></script>
<script type="text/javascript">
$(function () {
//Date picker
    $('#calibrationDate').datetimepicker({
    format: 'L'
    });
})
</script>
@endsection
