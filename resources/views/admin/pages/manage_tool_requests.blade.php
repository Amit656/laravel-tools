@extends('admin.layouts.master')
@section('css')
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
          <h1 class="m-0">{{ __('admin.manage_tool_requests.name') }}</h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{ url('admin/dashboard') }}">{{ __('admin.sidebar.menu.dashboard') }}</a></li>
            <li class="breadcrumb-item active">{{ __('admin.manage_tool_requests.name') }}</li>
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
              <h3 class="card-title">{{ __('admin.manage_tool_requests.list') }}</h3>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
              <form>
                <div class="row">
                  <div class="col-sm-4">
                    <div class="form-group">
                      <label for="filter">Filter</label>
                      <select class="form-control" name="filter" id="filter">
                        @foreach (config('services.admin_tool_request_return_filters') as $filter => $filterLabel)
                        <option value="{{$filter}}"  @if( 'new' == $filter )selected  @endif>{{$filterLabel}}</option>
                        @endforeach
                      </select>
                    </div>
                  </div>
                </div>

              </form>
              <table id="toolRequestsTable" class="table table-bordered">

                <thead>
                  <tr>
                    <th>{{ __('common.s_no')}}</th>
                    <th class="text-center">{{ __('common.tool') }}</th>
                    <th class="text-center">{{ __('common.product_no') }}</th>
                    <th class="text-center">{{ __('common.serial_no') }}</th>
                    <th class="text-center">{{ __('common.requested') }}&nbsp;{{ __('common.by') }}</th>
                    <th class="text-center">{{ __('common.pickup') }}</th>
                    <th class="text-center">{{ __('common.request') }}&nbsp;{{ __('common.date') }}</th>
                    <th class="text-center">{{ __('common.delivery') }}&nbsp;{{ __('common.date') }}</th>
                    <th class="text-center">{{ __('common.request') }}&nbsp;{{ __('common.location') }}</th>
                    <th class="text-center">{{ __('admin.tools.fields.image') }}</th>
                    <th class="text-center">{{ __('admin.tools.fields.QR') }}</th>
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

    </div><!-- /.container-fluid -->
  </section>
  <!-- /.content -->
</div>
<!-- /.content-wrapper -->

<!-- /.modal -->

<div class="modal fade" id="acceptToolRequestModal">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">{{ __('common.tool') }}&nbsp;{{ __('common.request') }}</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form name="addToolRequestForm" id="addToolRequestForm">

          <div class="row">
            <div class="col-lg-6">
              <div class="form-group">
                <label for="">{{ __('common.drop') }}&nbsp;{{ __('common.way') }}&nbsp;</label>

                @foreach (config('services.pick_by') as $pickBy => $pickupLabel)
                <div class="form-check">
                  <input class="form-check-input" value="{{$pickBy}}" id="{{$pickBy}}" type="radio" name="pickup">
                  <label for="{{$pickBy}}" class="form-check-label">{{ __( 'engineer.request_tool.'.$pickupLabel) }}</label>
                </div>
                @endforeach

              </div>
              <!-- /input-group -->
            </div>
            <div class="col-lg-6">
              <div class="form-group">
                <label class="" id="UPSDetails">{{ __('common.UPS_details') }}</label>
                <label class="d-none" id="EPTDetails">{{ __('common.EPT_details') }}</label>
                <textarea class="form-control" name="details" id="details"></textarea>
              </div>
              <!-- /input-group -->
            </div>
          </div>
      </div>
      <div class="modal-footer pull-left">
        <input type="hidden" name="requestToolId" id="requestToolId" value="" />
        <button type="button" class="btn btn-danger cancelBtn" data-dismiss="modal">{{ __('common.cancel') }}</button>
        <input type="submit" class="btn btn-primary submitBtn" value="{{ __('common.save') }}">
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
</script>
<script type="text/javascript" src="{{ asset('assets/js/admin/tool_requests.js') }}"></script>
@endsection