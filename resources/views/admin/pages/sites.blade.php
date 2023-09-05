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
            <h1 class="m-0">{{ __('admin.sites.name') }}</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">{{ __('admin.sidebar.menu.dashboard') }}</a></li>
              <li class="breadcrumb-item active">{{ __('admin.sites.name') }}</li>
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
                <h3 class="card-title">{{ __('admin.sites.list') }}</h3>
               
                  <button class="btn btn-primary m-btn m-btn--icon float-right" data-toggle="modal" data-target="#AddSiteModal">
                  <span>
                    <i class="fa fa-plus"></i>
                    <span>
                      {{__('admin.sites.add')}}
                    </span>
                 
                </button>
                </span>
              </div>
              <!-- /.card-header -->
              <div class="card-body">
                <table id="sitesTable" class="table table-bordered table-striped">
                  <thead>
                  <tr>
                    <th>{{ __('common.s_no') }}</th>
                    <th>{{ __('admin.sites.fields.name') }}</th>
                    <th>{{ __('admin.sites.fields.address') }}</th>
                    <th>{{ __('common.city') }}</th>
                    <th>{{ __('common.province') }}</th>
                    <th>{{ __('admin.sites.fields.type') }}</th>
                    <th>{{ __('common.created_at') }}</th>
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
        
      </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->

  <div class="modal fade" id="AddSiteModal">
        <div class="modal-dialog modal-lg">
          <div class="modal-content">
            <div class="modal-header">
              <h4 class="modal-title">{{ __('admin.sites.name') }}</h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
              <form name="addEditSiteForm" id="addEditSiteForm">
                 
                <div class="row">
                  <div class="col-sm-6">
                    <div class="form-group">
                      <label for="siteName">{{ __('admin.sites.fields.name') }}<span class="text-danger">*</span></label>
                      <input type="text" class="form-control" id="siteName" name="siteName" value="{{ old('siteName') }}" placeholder="Enter {{ __('admin.sites.fields.name') }}">
                    </div>
                  </div>
                  <div class="col-sm-6">
                    <div class="form-group">
                      <label for="siteAddress">{{ __('admin.sites.fields.address') }}<span class="text-danger">*</span></label>
                      <input type="text" class="form-control" id="siteAddress" name="siteAddress" value="{{ old('siteName') }}" placeholder="Enter {{ __('admin.sites.fields.address') }}">
                    </div>
                  </div>
                  <div class="col-sm-6">
                    <div class="form-group">
                      <label for="province">{{ __('common.province') }}<span class="text-danger">*</span></label>
                      <select class="form-control" name="province"  id="province" required>
                        @foreach ($allProvinces as $province)
                          
                            <option {{ old("province", 3152)== $province->id ? 'selected' : ''  }} value="{{ $province->id}}">{{ $province->name}}</option>
                         
                        @endforeach
                      </select>
                    </div>
                  </div>
                  <div class="col-sm-6">
                    <div class="form-group">
                      <label for="city">{{ __('common.city') }}<span class="text-danger">*</span></label>
                      <select class="form-control" name="city" id="city" required>
                        
                          @foreach ($allCities as $city)
                            {{-- by default selecting lucknow can change as per client origin --}}
                            
                              <option id="cityName" {{ old("city", 3152)== $city->id ? 'selected' : ''  }} value="{{ $city->id}}">{{ $city->name}}</option>
                            

                          @endforeach
                        
                      </select>
                    </div>
                  </div>
                  <div class="col-sm-6">
                    <div class="form-group">
                      <label for="desc">{{ __('admin.sites.fields.description') }}</label>
                      <textarea name="desc" id="desc" class="form-control" rows="3" placeholder="Enter ..."></textarea>
                    </div>
                  </div>
                  <div class="col-sm-4">
                    <div class="form-group">
                      <label for="siteType">{{ __('admin.sites.fields.type') }}<span class="text-danger">*</span></label>
                      
                      <div class="input-group">
                       
                          @foreach (config('services.type') as $type => $typeLabel)
                            <div class="form-check" style="padding-left: 1.70rem;">
                              <input class="form-check-input siteType" value="{{$type}}" type="radio" name="siteType" id="siteType_{{$type}}" @if( old("siteType", 'hospital') == $type )checked  @endif>
                              <label for="{{$type}}" class="form-check-label">{{ $typeLabel }}</label>
                            </div>
                          @endforeach
                          
                        </div>
                      </div>
                    
                  </div>
                </div>
                
            </div>
            <div class="modal-footer pull-left">
              <input class="d-none" name="siteId" id="siteId" value="" />
              <input style="display: none;" type="reset" value="reset" id="resetBtnadd">
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

  @include('admin.includes.footer')
@endsection

@section('script')
<script type="text/javascript" src="{{ asset('assets/js/common.js') }}"></script>
<script type="text/javascript" src="{{ asset('assets/js/admin/site.js') }}"></script>
@endsection
