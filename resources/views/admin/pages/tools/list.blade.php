@extends('admin.layouts.master')

<style type="text/css">
  .w-5 {
    display: none;
  }
</style>
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
        <div class="row">
          <div class="col-md-12">
            <a href="{{ route('createTools') }}" class="btn btn-danger  float-right">
              {{__('admin.tools.add')}}
            </a>
          </div>
        </div>
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
        <!-- Small boxes (Stat box) -->
        <div class="row">
          <div class="col-12">
            @if( Session::has('success') || Session::has('error') )

            <div class="alert {{ Session::has('success') ? 'alert-success': 'alert-danger' }}">
              {{ @Session::get('success') }}
              {{ @Session::get('error') }}
            </div>

            @endif
            <div class="card">
              <div class="card-header">
                <h3 class="card-title">{{ __('admin.tools.list') }}</h3>
              </div>
              <!-- /.card-header -->
              <div class="card-body">
                <table id="toolsTable" class="table table-bordered table-striped">
                  <thead>
                  <tr>
                    <th>{{ __('common.s_no') }}</th>
                    <th>{{ __('admin.tools.fields.description') }}</th>
                    <th>{{ __('admin.tools.fields.tool_id') }}</th>
                    <th>{{ __('common.modality') }}</th>
                    <th>{{ __('common.site') }}</th>
                    <th>{{ __('common.serial_no') }}</th>
                    <th>{{ __('common.product_no') }}</th>
                    <!-- <th>{{ __('admin.tools.fields.type_of_use') }}</th>
                    <th>{{ __('admin.tools.fields.asset') }}</th> -->
                    <th>{{ __('admin.tools.fields.sort_field') }}</th>
                    
                    <th>{{ __('admin.tools.fields.calibration_date') }}</th>
                    <th>{{ __('admin.tools.fields.status') }}</th>
                    
                    <th>{{ __('admin.tools.fields.image') }}</th>
                    <th>{{ __('common.action') }}</th>
                  </tr>
                  </thead>
                  <tbody>
                  @foreach ($allTools as $key => $tool)
                  <tr>
                    <td>{{ ++$key }}</td>
                    <td>{{$tool->description}}</td>
                    <td>{{$tool->tool_id}}</td>
                    <td>{{$tool->modality->name}}</td>
                    <td>{{$tool->site->name}}</td>
                    <td>{{$tool->serial_no}}</td>
                    <td>{{$tool->product_no}}</td>
                    <!-- <td>{{$tool->type_of_use}}</td>
                    <td>{{$tool->asset}}</td> -->
                    <td>{{$tool->sort_field}}</td>
                    
                    <td>{{$tool->calibration_date}}</td>
                    <td>{{$tool->status}}</td>
                    
                    <td><img src="{{ asset('tools/' .$tool->image) }}" alt="" class="img-thumbnail"></td>
                    <td><a href="{{ url('admin/tools/edit/'. $tool->id ) }}" data-toggle="tooltip" title='Edit' class="mr-1">
                           <i class="fas fa-edit"></i>
                      </a>
                      <a href="{{ url('admin/tools/delete/'. $tool->id ) }}" data-toggle="tooltip" title='Delete' class="mr-1" onclick="return confirm('Are you sure want to delete this?');">
                          <i class="fa fa-trash"></i>
                      </a>
                      <a href="{{ url('admin/tools/view/'. $tool->id ) }}" data-toggle="tooltip" title='View' class="mr-1">
                          <i class="fa fa-eye"></i>
                      </a>
                    </td>
                    
                  </tr>
                  @endforeach
                  </tbody>
                
                </table>
                <span>
                  {{ $allTools->links() }}
                </span>
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
  @include('admin.includes.footer')
@endsection

@section('script')
  <script>
  $(function () {
    $("#toolsTable").DataTable({
      "paging": false,
      "lengthChange": false,
      "searching": false,
      "ordering": true,
      "info": false,
      "autoWidth": false,
      "responsive": true,
    })
    
  });
</script>
@endsection
