@extends('engineer.layouts.master')
@section('content')

<!-- Navbar -->
@include('engineer.includes.header')
@section('css')
<link rel="stylesheet" href="{{ asset('assets/plugins/bs-stepper/css/bs-stepper.min.css') }}">
<link rel="stylesheet" href="{{ asset('assets/plugins/daterangepicker/daterangepicker.css') }}">
<link rel="stylesheet" href="{{ asset('assets/plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css') }}">
<link rel="stylesheet" href="{{ asset('assets/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
<link rel="stylesheet" href="{{ asset('assets/plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
<link rel="stylesheet" href="{{ asset('assets/plugins/datatables-buttons/css/buttons.bootstrap4.min.css') }}">
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
                    <form name="returnToolForm" id="returnToolForm">

                        <div class="card">
                            <div class="card-header" id="search-div">
                                <div class="row float-right">
                                    <input type="search" class="form-control col-md-12" id="search" name="search" placeholder="Search">
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="bs-stepper">
                                    <div class="bs-stepper-header" role="tablist">
                                        <!-- your steps here -->
                                        <div class="step" data-target="#tools-list">
                                            <button type="button" class="step-trigger" role="tab" aria-controls="tools-list" id="tools-list-trigger">
                                                <span class="bs-stepper-circle">1</span>
                                                <span class="bs-stepper-label">{{ __('common.tools') }}&nbsp;{{ __('common.list') }}</span>
                                            </button>
                                        </div>
                                        <div class="line"></div>
                                        <div class="step" data-target="#return-tools">
                                            <button type="button" class="step-trigger" role="tab" aria-controls="return-tools" id="return-tools-trigger">
                                                <span class="bs-stepper-circle">2</span>
                                                <span class="bs-stepper-label">{{ __('common.return') }}&nbsp;{{ __('common.tools') }}</span>
                                            </button>
                                        </div>
                                        <div class="line"></div>
                                        <div class="step" data-target="#submit">
                                            <button type="button" class="step-trigger" role="tab" aria-controls="submit" id="submit-trigger">
                                                <span class="bs-stepper-circle">3</span>
                                                <span class="bs-stepper-label">{{ __('common.submit') }}&nbsp;{{ __('common.return') }}</span>
                                            </button>
                                        </div>
                                    </div>
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
                                    <br />
                                    <div class="bs-stepper-content">
                                        <!-- your steps content here -->
                                        <div id="tools-list" class="content" role="tabpanel" aria-labelledby="tools-list-trigger">
                                            <div class="card-body table-responsive p-0" style="@if (count($allCurrrentInHandTools) > 15)  height: 600px; @else height: auto; @endif">
                                                <table id="DataTables_Table_0" class="table table-bordered example2 @if (count($allCurrrentInHandTools) > 15)  table-head-fixed @endif">
                                                    <thead>
                                                        <tr>
                                                            <th class="text-center">{{ __('common.tool') }}</th>
                                                            <th class="text-center">{{ __('common.product_no') }}</th>
                                                            <th class="text-center">{{ __('common.serial_no') }}</th>
                                                            <th class="text-center">{{ __('engineer.request_tool.cal_due_date') }}</th>
                                                            <th class="text-center">{{ __('common.expected') }}&nbsp;{{ __('common.return') }}&nbsp;{{ __('common.date') }}</th>
                                                            <th class="text-center">{{ __('common.action') }}
                                                                <input class="checkAll" type="checkbox" name="checkAll" value="1" @if(is_array(old("returnTool")) && count(old("returnTool"))==count($allCurrrentInHandTools) )checked @endif>
                                                            </th>
                                                        </tr>
                                                    </thead>
                                                    <tbody id="tableData">
                                                        @foreach ($allCurrrentInHandTools as $tool)
                                                        <tr>
                                                            @if($tool['toolColor'] == 'green')
                                                            <td class="text-wrap text-center border-left-green">{{$tool['tool_description'] ? $tool['tool_description'] : 'N/A'}}</td>
                                                            @elseif($tool['toolColor'] == 'red')
                                                            <td class="text-wrap text-center border-left-red">{{$tool['tool_description'] ? $tool['tool_description'] : 'N/A'}}</td>
                                                            @else
                                                            <td class="text-wrap text-center border-left-yellow">{{$tool['tool_description'] ? $tool['tool_description'] : 'N/A'}}</td>
                                                            @endif

                                                            <td class="text-center">{{$tool['tool_product_no']}}</td>
                                                            <td class="text-center">{{$tool['tool_serial_no']}}</td>
                                                            <td class="text-center">{{$tool['tool_calibration_date'] ? $tool['tool_calibration_date'] : 'N/A'}}</td>
                                                            <td class="text-center">{{$tool['expected_return_date']}}</td>
                                                            <td class="text-center">
                                                                <input class="return-tool" type="checkbox" name="returnTool[]" value="{{$tool['id']}}" @if(is_array(old("returnTool")) && in_array($tool['id'], old("returnTool"))) checked @endif>
                                                            </td>
                                                        </tr>
                                                        @endforeach

                                                    </tbody>
                                                </table>
                                            </div>
                                            <div class="card-footer">
                                                <a href="{{ url('engineer/dashboard') }}" class="btn btn-danger">{{ __('common.cancel') }}</a>
                                                <button type="button" class="btn btn-primary  return-tools-next" id="nextBtn">{{ __('common.next') }}</button>
                                            </div>



                                        </div>

                                        <div id="return-tools" class="content" role="tabpanel" aria-labelledby="return-tools-trigger">
                                            <div class="card-body" id="finalCheckDataDiv" style="height: auto;">
                                                <table class="table table-bordered table-head-fixed">
                                                    <thead>
                                                        <tr>
                                                            <th class="text-center">{{ __('common.tool') }}</th>
                                                            <th class="text-center">{{ __('common.product_no') }}</th>
                                                            <th class="text-center">{{ __('common.serial_no') }}</th>
                                                            <th class="text-center">{{ __('common.condition') }}</th>
                                                            <th class="text-center">{{ __('common.comment') }}</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody id="finalCheckData">

                                                    </tbody>
                                                </table>
                                            </div>
                                            <div class="card-footer">
                                                <a href="{{ url('engineer/dashboard') }}" class="btn btn-danger">{{ __('common.cancel') }}</a>
                                                <button type="button" class="btn btn-primary" id="previousbtn" onclick="stepper.previous()">Previous</button>

                                                <button type="button" class="btn btn-primary" onclick="stepper.next()">{{ __('common.next') }}</button>
                                            </div>
                                        </div>

                                        <div id="submit" class="content" role="tabpanel" aria-labelledby="submit-trigger">
                                            <div class="card-body">
                                                <div class="row">
                                                    <div class="col-lg-6">
                                                        <div class="form-group">
                                                            <label for="site">{{ __('common.please') }}&nbsp;
                                                                {{ __('common.select') }}&nbsp;
                                                                {{ __('common.drop') }}&nbsp;
                                                                {{ __('common.way') }}&nbsp;</label>

                                                            @foreach (config('services.drop_by') as $pickBy => $pickupLabel)

                                                            <div class="form-check">
                                                                <input class="form-check-input pickup" value="{{$pickBy}}" id="{{$pickBy}}" type="radio" name="pickup" @if( old("pickup", 'UPS' )==$pickBy )checked @endif>
                                                                <label for="{{$pickBy}}" class="form-check-label">{{ __( 'engineer.request_tool.'.$pickupLabel) }}</label>
                                                            </div>

                                                            @endforeach
                                                        </div>
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
                                            <div class="card-footer">
                                                <a href="{{ url('engineer/dashboard') }}" id="cancel" class="btn btn-danger">{{ __('common.cancel') }}</a>
                                                <button type="button" class="btn btn-primary returnToolBtn" onclick="stepper.previous()">{{ __('common.previous') }}</button>
                                                <input type="submit" id="submitbtn" class="btn btn-warning submit returnToolBtn" disabled value="{{ __('common.submit') }}">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
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
<script type="text/javascript" src="{{ asset('assets/plugins/bs-stepper/js/bs-stepper.min.js') }}"></script>
<!-- DataTables  & Plugins -->
<script type="text/javascript" src="{{ asset('assets/plugins/datatables/jquery.dataTables.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('assets/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('assets/plugins/datatables-responsive/js/dataTables.responsive.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('assets/plugins/datatables-responsive/js/responsive.bootstrap4.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('assets/plugins/datatables-buttons/js/dataTables.buttons.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('assets/plugins/datatables-buttons/js/buttons.bootstrap4.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('assets/plugins/datatables-buttons/js/buttons.html5.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('assets/plugins/datatables-buttons/js/buttons.print.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('assets/plugins/datatables-buttons/js/buttons.colVis.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('assets/plugins/bootstrap-switch/js/bootstrap-switch.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('assets/js/common.js') }}"></script>
<script type="text/javascript" src="{{ asset('assets/js/engineer/return_tools.js') }}"></script>
<script type="text/javascript">
    // BS-Stepper Init
    document.addEventListener('DOMContentLoaded', function() {
        window.stepper = new Stepper(document.querySelector('.bs-stepper'));
        $(function() {
            //Date picker
            $('#deliveryDate').datetimepicker({
                format: 'L'
            });

            $('#expectedReturnDate').datetimepicker({
                format: 'L'
            });

            $('.example2').DataTable({
                "paging": false,
                "lengthChange": false,
                "searching": false,
                "ordering": false,
                "info": false,
                "autoWidth": false,
                "responsive": false,
            });
        })
    });
</script>
@stop