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
            <h1 class="m-0">{{ __('common.cities')}}</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="{{ url('admin/dashboard') }}">{{ __('admin.sidebar.menu.dashboard') }}</a></li>
              <li class="breadcrumb-item active">{{ __('common.cities')}}</li>
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
                <h3 class="card-title">{{ __('common.cities')}} {{ __('common.list')}}</h3>
                <span style="display: block; float: right;">
                  
                  <button class="btn btn-primary m-btn m-btn--icon" id="addCity" data-toggle="modal" data-target="#AddCityModal">
                  <span>
                    <i class="fa fa-plus"></i>
                    <span>
                    {{ __('common.add')}} {{ __('common.city')}}
                    </span>
                  </span>
              </div>
              <!-- /.card-header -->
              <div class="card-body">
                <table id="cityTable" class="table table-bordered table-striped">
                    <thead>
                      <tr>
                        <th>{{ __('common.s_no')}}</th>
                        <th>{{ __('common.city')}}</th>
                        <th>{{ __('common.province')}}</th>
                        <th>{{ __('common.created_at')}}</th>
                        <th>{{ __('common.actions')}}</th>
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

      <div class="modal fade" id="AddCityModal">
        <div class="modal-dialog modal-lg">
          <div class="modal-content">
            <div class="modal-header">
              <h4 class="modal-title">{{ __('common.city') }}</h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
              <form name="addEditCityForm" id="addEditCityForm">
                
                <div class="row">
                  <div class="col-sm-12">
                    <div class="form-group">
                      <label for="province">{{ __('common.province') }}<span class="text-danger">*</span></label>
                      <select class="form-control" name="province"  id="province" required>
                        @foreach ($allProvinces as $province)
                          
                            <option {{ old("province", 3152)== $province->id ? 'selected' : ''  }} value="{{ $province->id}}">{{ $province->name}}</option>
                         
                        @endforeach
                      </select>
                    </div>
                  </div>
                  <div class="col-sm-12">
                    <div class="form-group">
                      <label for="cityName">{{ __('common.name') }}<span class="text-danger">*</span></label>
                      <input type="text" class="form-control" id="cityName" name="cityName" value="{{ old('cityName') }}" placeholder="Enter {{ __('common.city') }} {{ __('common.name') }}">
                    </div>
                  </div>                  
                </div>
                
              </div>
              <div class="modal-footer pull-left">
                <input class="d-none" name="cityId" id="cityId" value="" />
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
<script type="text/javascript" src="{{url('/assets/js/admin/city.js')}}"></script>
@endsection
