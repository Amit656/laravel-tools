@extends('admin.layouts.master')
@section('css')
<link rel="stylesheet" href="{{ asset('assets/plugins/daterangepicker/daterangepicker.css') }}">
<link rel="stylesheet" href="{{ asset('assets/plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css') }}">
@stop
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
              <li class="breadcrumb-item"><a href="#">{{ __('admin.navbar.menu.home') }}</a></li>
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
          <div class="col-md-12">
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
            <!-- general form elements -->
            <div class="card card-primary">
              <div class="card-header">
                <h3 class="card-title">{{ @$data ? __('admin.tools.update') : __('admin.tools.add') }}</h3>
              </div>
              <!-- /.card-header -->
              <!-- form start -->
              <form method="POST" action="{{ @$data ? route('updateTools') : route('storeTools') }}" enctype="multipart/form-data">
                <div class="card-body">
                  @csrf

                  <div class="row">
                    <div class="col-sm-6">
                      <div class="form-group">
                        <label for="toolId">{{ __('admin.tools.fields.tool_id') }}</label>
                        <input type="text" class="form-control" id="toolId" name="toolId" value="{{@$data['tool_id'] ?? (old('toolId') ?? '')}}" placeholder="Enter {{ __('admin.tools.fields.tool_id') }}" @if(@$detail) readonly @endif>
                      </div>
                    </div>
                    <div class="col-sm-6">
                      <div class="form-group">
                        <label for="modility">{{ __('common.modality') }}</label>
                        <select class="form-control" name="modality" id="modality" required @if(@$detail) disabled @endif>
                          @foreach ($allModalities as $modality)
                            <option {{ @$data['modality_id']== $modality->id ? 'selected' : ''  }} value="{{ $modality->id}}">{{ $modality->name}}</option>
                          @endforeach
                        </select>
                      </div>
                    </div>
                    <div class="col-sm-6">
                      <div class="form-group">
                        <label for="site">{{ __('common.site') }}</label>
                        <select class="form-control" name="site" id="site" required @if(@$detail) disabled @endif>
                          @foreach ($allSites as $site)
                            <option {{ @$data['site_id']== $site->id ? 'selected' : ''  }} value="{{ $site->id}}">{{ $site->name}}</option>
                          @endforeach
                        </select>
                      </div>
                    </div>
                    
                    <div class="col-sm-6">
                      <div class="form-group">
                        <label for="serialNo">{{ __('common.serial_no') }}</label>
                        <input type="text" class="form-control" id="serialNo" name="serialNo" value="{{@$data['serial_no'] ?? (old('serialNo') ?? '')}}" placeholder="Enter {{ __('common.serial_no') }}" @if(@$detail) readonly @endif>
                      </div>
                    </div>
                    
                    <div class="col-sm-6">
                      <div class="form-group">
                        <label for="productNo">{{ __('common.product_no') }}</label>
                        <input type="text" class="form-control" id="productNo" name="productNo" value="{{@$data['product_no'] ?? (old('productNo') ?? '')}}" placeholder="Enter {{ __('common.product_no') }}" @if(@$detail) readonly @endif>
                      </div>
                    </div>
                    <div class="col-sm-6">
                      <div class="form-group">
                        <label for="typeOfUse">{{ __('admin.tools.fields.type_of_use') }}</label>
                        <input type="text" class="form-control" id="typeOfUse" name="typeOfUse" value="{{@$data['type_of_use'] ?? (old('typeOfUse') ?? '')}}" placeholder="Enter {{ __('admin.tools.fields.type_of_use') }}" @if(@$detail) readonly @endif>
                      </div>
                    </div>
                    <div class="col-sm-6">
                      <div class="form-group">
                        <label for="asset">{{ __('admin.tools.fields.asset') }}</label>
                        <input type="text" class="form-control" id="asset" name="asset" value="{{@$data['asset'] ?? (old('asset') ?? '')}}" placeholder="Enter {{ __('admin.tools.fields.asset') }}" @if(@$detail) readonly @endif>
                      </div>
                    </div>
                    <div class="col-sm-6">
                      <div class="form-group">
                        <label for="sortField">{{ __('admin.tools.fields.sort_field') }}</label>
                        <input type="text" class="form-control" id="sortField" name="sortField" value="{{@$data['sort_field'] ?? (old('sortField') ?? '')}}" placeholder="Enter {{ __('admin.tools.fields.sort_field') }}" @if(@$detail) readonly @endif>
                      </div>
                    </div>
                    <div class="col-sm-6">
                      <div class="form-group">
                        <label for="calibrationDate">{{ __('admin.tools.fields.calibration_date') }}</label>
                        
                        <div class="input-group date" id="calibrationDate" data-target-input="nearest">
                          <input type="text" class="form-control datetimepicker-input" data-target="#calibrationDate" value={{@$data['calibration_date'] ?? (old('calibrationDate') ?? '')}} @if(@$detail) disabled @endif />
                          <div class="input-group-append" data-target="#calibrationDate" data-toggle="datetimepicker">
                              <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                          </div>
                        </div>
                      
                      </div>
                    </div>
                    <div class="col-sm-6">
                      <div class="form-group">
                        <label for="desc">{{ __('admin.tools.fields.description') }}</label>
                        <textarea name="desc" class="form-control" rows="2" placeholder="Enter ..." @if(@$detail) readonly @endif>{{ @$data['description'] }}</textarea>
                      </div>
                    </div>
                    <div class="col-sm-6">
                      <div class="form-group">
                        <label>{{ __('admin.tools.fields.image') }}</label>
                        <input type="file" name="image" class="form-control" @if(@$detail) disabled @endif accept="image/*">
                      </div>

                      @if( @$data['image'] )
                      <input type="hidden" name="oldImage" value="{{@$data['image']}}" class="form-control">
                      <img src="{{ asset('tools/' .@$data['image']) }}" class="img-thumbnail" width="100" height="100">
                      @endif
                    </div>
                    <div class="col-sm-6">
                      
                      <label>{{ __('admin.tools.fields.status') }}</label>
                       
                      <div class="form-group">
                        <div class="input-group">
                          @if( !@$data )
                            @foreach (config('services.status') as $status => $statusLabel)
                              <div class="form-check" style="padding-left: 1.70rem;">
                                <input class="form-check-input" value="{{$status}}" id="{{$status}}" type="radio" name="toolStatus" @if( old("toolStatus", 'available') == $status )checked  @endif>
                                <label for="{{$status}}" class="form-check-label">{{ $statusLabel }}</label>
                              </div>
                            @endforeach
                          @else
                            <input type="hidden" name='id' value="{{ $data['id'] }}">
                            @foreach (config('services.status') as $status => $statusLabel)
                              <div class="form-check" style="padding-left: 1.70rem;">
                                <input class="form-check-input" value="{{$status}}" id="{{$status}}" type="radio" name="toolStatus" @if( @$data['status'] == $status )checked  @endif @if(@$detail) disabled @endif>
                                <label for="{{$status}}" class="form-check-label">{{ $statusLabel }}</label>
                              </div>
                            @endforeach
                          @endif
                        </div>
                      </div>
                    </div>  
                    
                  </div>
                </div>
                <!-- /.card-body -->
                @if( !@$detail )
                <div class="card-footer">
                  <input type="submit" class="btn btn-primary" value="{{ __('common.submit') }}">
                </div>
                @endif
              </form>
            </div>
            <!-- /.card -->
          </div>
          <!-- ./col -->
        </div>
        <!-- /.row -->
        
      </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
  @include('admin.includes.footer')


@endsection
@section('script')
<script type="text/javascript" src="{{ asset('assets/plugins/moment/moment.min.js') }}"></script>
<!-- Datepicker -->
<script type="text/javascript" src="{{ asset('assets/plugins/daterangepicker/daterangepicker.js') }}"></script>
<script type="text/javascript" src="{{ asset('assets/plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js') }}"></script>
<script type="text/javascript">
// BS-Stepper Init
document.addEventListener('DOMContentLoaded', function () {
$(function () {
//Date picker
    $('#calibrationDate').datetimepicker({
    format: 'L'
    });
    
})
});
</script>
@endsection