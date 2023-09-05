@extends('engineer.layouts.master')
@section('content')

  <!-- Navbar -->
@include('engineer.includes.header')
@section('css')
<!-- Toastr -->
<link rel="stylesheet" href="{{ asset('assets/plugins/toastr/toastr.min.css') }}">
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
        <div class="row" style="margin-bottom: 11rem;">
            <div class="col-lg-12">
                
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-4">

                                    <!-- Profile Image -->
                                    <div class="card card-primary card-outline">
                                    <div class="card-body box-profile">
                                        <div class="text-center">
                                        <a href="#" data-toggle="modal" data-target="#changeProfileImage">
                                            <img class="profile-user-img img-fluid img-circle profile-img"
                                                src=""
                                                alt="User profile picture">
                                        </a>
                                        </div>

                                        <h3 class="profile-username text-center user-name"></h3>

                                        <p class="text-muted text-center user-email"></p>

                                        <a href="#" class="btn btn-primary btn-block" data-toggle="modal" data-target="#changePasswordModal">
                                        <b><i class="fa fa-lock" aria-hidden="true"></i>&nbsp;{{ __('common.change') }} {{ __('admin.users.fields.password') }}</b>
                                        </a>
                                    </div>
                                    <!-- /.card-body -->
                                    </div>
                                    <!-- /.card -->
                                </div>
                                <!-- /.col -->

                                <div class="col-md-8">
                                    <div class="card">
                                    <div class="card-header p-2">
                                    <h3 class="card-title">{{ __('common.edit') }} {{ __('common.profile') }}</h3>
                                    </div><!-- /.card-header -->
                                    <div class="card-body">
                                        
                                    <form class="form-horizontal" name="editProfileForm" id="editProfileForm">
                                            <div class="form-group row">
                                                <label for="userName" class="col-sm-2 col-form-label">{{ __('admin.users.fields.name') }}</label>
                                                <div class="col-sm-10">
                                                <input type="text" class="form-control" id="userName" name="userName" placeholder="{{ __('admin.users.fields.name') }}" value="{{ old('userName') }}" >
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label for="userEmail" class="col-sm-2 col-form-label">{{ __('common.email') }}</label>
                                                <div class="col-sm-10">
                                                <input type="email" class="form-control" id="userEmail" name="userEmail" placeholder="{{ __('common.email') }}" value="{{ old('userEmail') }}">
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label for="EmployeeNumber" class="col-sm-2 col-form-label">{{ __('admin.users.fields.emp_no') }}</label>
                                                <div class="col-sm-10">
                                                <input type="text" class="form-control" id="EmployeeNumber" name="EmployeeNumber" placeholder="{{ __('admin.users.fields.emp_no') }}" value="{{ old('EmpNo') }}" readonly>
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label for="mobile" class="col-sm-2 col-form-label">{{ __('admin.users.fields.mobile') }}</label>
                                                <div class="col-sm-10">
                                                <input type="text" class="form-control" id="mobile" name="mobile" placeholder="{{ __('admin.users.fields.mobile') }}" value="{{ old('mobile') }}">
                                                </div>
                                            </div>
                                            
                                            <div class="form-group row">
                                                <div class="offset-sm-2 col-sm-10">
                                                    <input type="hidden" class="form-control" id="userId" name="userId">
                                                <button type="submit" class="btn btn-primary">{{ __('common.update') }}</button>
                                                </div>
                                            </div>
                                            </form>
                                        
                                    </div><!-- /.card-body -->
                                    </div>
                                    <!-- /.card -->
                                </div>
                                <!-- /.col -->
                            </div>
                            <!-- /.row -->
                        </div>
                    </div>
                
            </div>
        </div>
        <!-- /.row -->
        
        <div class="modal fade" id="changePasswordModal">
            <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                <h4 class="modal-title">{{ __('common.change') }} {{ __('admin.users.fields.password') }}</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
                </div>
                <div class="modal-body">
                <form name="editPasswordForm" id="editPasswordForm">
                    
                    <div class="row">
                    <div class="col-sm-12">
                        <div class="form-group">
                        <label for="oldPassword">{{ __('common.old') }} {{ __('admin.users.fields.password') }}</label>
                        <input type="password" class="form-control" id="oldPassword" name="oldPassword" value="{{ old('oldPassword') }}" placeholder="Enter {{ __('admin.modality.fields.name') }}">
                        </div>
                    </div> 
                    <div class="col-sm-12">
                        <div class="form-group">
                        <label for="newPassword">{{ __('common.new') }} {{ __('admin.users.fields.password') }}</label>
                        <input type="password" class="form-control" id="newPassword" name="newPassword" value="{{ old('newPassword') }}" placeholder="Enter {{ __('admin.modality.fields.name') }}">
                        </div>
                    </div>
                    <div class="col-sm-12">
                        <div class="form-group">
                        <label for="confirmPassword">{{ __('common.confirm') }} {{ __('admin.users.fields.password') }}</label>
                        <input type="text" class="form-control" id="confirmPassword" name="confirmPassword" value="{{ old('confirmPassword') }}" placeholder="Enter {{ __('admin.modality.fields.name') }}">
                        </div>
                    </div>                 
                    </div>
                    
                </div>
                <div class="modal-footer pull-left">
                    
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

      <div class="modal fade" id="changeProfileImage">
        <div class="modal-dialog modal-lg">
          <div class="modal-content">
            <div class="modal-header">
              <h4 class="modal-title">{{ __('common.change') }} {{ __('common.profile') }} {{ __('common.image') }}</h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
              <form name="editProfileImgForm" id="editProfileImgForm">
                
                <div class="row">
                  <div class="col-sm-12">
                    <div class="form-group">
                      <label for="profileImg">{{ __('common.image') }}</label>
                      
                      <input type="file" class="form-control" id="profileImg" name="profileImg" value="{{ old('oldPassword') }}" placeholder="Enter {{ __('admin.modality.fields.name') }}">
                    </div>
                  </div> 
                  
              </div>
              <div class="modal-footer pull-left">
                
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
  </div>
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
<!-- Toastr -->
<script src="{{ asset('assets/plugins/toastr/toastr.min.js') }}"></script>
<script type="text/javascript" src="{{url('/assets/js/profile.js')}}"></script>
@stop
