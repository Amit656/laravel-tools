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
            <h1 class="m-0">{{ __('admin.users.name') }}</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">{{ __('admin.sidebar.menu.dashboard') }}</a></li>
              <li class="breadcrumb-item active">{{ __('admin.users.name') }}</li>
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
                <h3 class="card-title">{{ __('admin.users.list') }}</h3>
                
                  <button class="btn btn-primary m-btn m-btn--icon float-right" id="addUserbtn" data-toggle="modal" data-target="#AddUserModal">
                  <span>
                    <i class="fa fa-plus"></i>
                    <span>
                      {{__('admin.users.add')}}
                    </span>
                  
                </button>
                </span>
              </div>
              <!-- /.card-header -->
              <div class="card-body">
                <table id="usersTable" class="table table-bordered table-striped">
                  <thead>
                  <tr>
                    <th>{{ __('common.s_no') }}</th>
                    <th>{{ __('admin.users.fields.name') }}</th>
                    <th>{{ __('admin.users.fields.emp_email') }}</th>
                    <th>{{ __('admin.users.fields.emp_no') }}</th>
                    <th>{{ __('admin.users.fields.mobile') }}</th>
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

<div class="modal fade" id="AddUserModal">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">{{ __('admin.users.name') }}</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form name="addEditUserForm" id="addEditUserForm">
          
          <div class="row">
            <div class="col-sm-6">
              <div class="form-group">
                <label for="userName">{{ __('admin.users.fields.name') }}<span class="text-danger">*</span></label>
                <input type="text" class="form-control" id="userName" name="userName" value="{{ old('userName') }}" placeholder="Enter {{ __('admin.users.fields.name') }}" min="3" maxlength="30" required>
              </div>
            </div>
            <div class="col-sm-6">
              <div class="form-group">
                <label for="userEmail">{{ __('admin.users.fields.emp_email') }}<span class="text-danger">*</span></label>
                <input type="email" class="form-control" id="userEmail" name="userEmail" value="{{ old('userEmail') }}" placeholder="Enter {{ __('admin.users.fields.emp_email') }}" required>
              </div>
            </div>
            <div class="col-sm-6">
              <div class="form-group">
                <label for="userEmpNo">{{ __('admin.users.fields.emp_no') }}<span class="text-danger">*</span></label>
                <input type="text" class="form-control" id="userEmpNo" name="userEmpNo" value="{{ old('userEmpNo') }}" placeholder="Enter {{ __('admin.users.fields.emp_no') }}" min="1" maxlength="15" required>
              </div>
            </div>
            <div class="col-sm-6 passwordDiv">
              <div class="form-group">
                <label for="userPassword">{{ __('admin.users.fields.password') }}<span class="text-danger">*</span></label>
                <input type="password" class="form-control" id="userPassword" name="userPassword" value="{{ old('userPassword') }}" min="6" maxlength="15" placeholder="Enter {{ __('admin.users.fields.password') }}">
              </div>
            </div>
            
            <div class="col-sm-6">
              <div class="form-group">
                <label for="userMobile">{{ __('admin.users.fields.mobile') }}</label>
                <input type="text" class="form-control" id="userMobile" name="userMobile" value="{{ old('userMobile') }}" placeholder="Enter {{ __('admin.users.fields.mobile') }}">
              </div>
            </div>
            
          </div>
          
        </div>
        <div class="modal-footer pull-left">
          <input class="d-none" name="userId" id="userId" value="" />
          <input type="hidden" name="role" id="role" value="{{$role}}" />
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
<script type="text/javascript" src="{{ asset('assets/js/admin/user.js') }}"></script>

@endsection
