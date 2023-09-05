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
                    <form name="toolRequestForm" id="toolRequestForm">
                        @csrf
                        <div class="card">
                            <div class="card-header d-none" id="search-div">
                                <div class="row float-right">
                                    <input type="search" class="form-control col-md-12" id="search" name="search" placeholder="Search">
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="bs-stepper">
                                    <div class="bs-stepper-header" role="tablist">
                                        <!-- your steps here -->
                                        <div class="step" data-target="#request-details">
                                            <button type="button" class="step-trigger" role="tab" aria-controls="request-details" id="request-details-trigger">
                                                <span class="bs-stepper-circle">1</span>
                                                <span class="bs-stepper-label">{{ __('common.request') }}&nbsp;{{ __('common.details') }}</span>
                                            </button>
                                        </div>
                                        <div class="line"></div>
                                        <div class="step" data-target="#select-tools">
                                            <button type="button" class="step-trigger" role="tab" aria-controls="select-tools" id="select-tools-trigger">
                                                <span class="bs-stepper-circle">2</span>
                                                <span class="bs-stepper-label">{{ __('common.select') }}&nbsp;{{ __('common.tools') }}</span>
                                            </button>
                                        </div>
                                        <div class="line"></div>
                                        <div class="step" data-target="#final-check">
                                            <button type="button" class="step-trigger" role="tab" aria-controls="final-check" id="final-check-trigger">
                                                <span class="bs-stepper-circle">3</span>
                                                <span class="bs-stepper-label">{{ __('common.final') }}&nbsp;{{ __('common.check') }}</span>
                                            </button>
                                        </div>
                                        <div class="line"></div>
                                        <div class="step" data-target="#submit">
                                            <button type="button" class="step-trigger" role="tab" aria-controls="submit" id="submit-trigger">
                                                <span class="bs-stepper-circle">3</span>
                                                <span class="bs-stepper-label">{{ __('common.submit') }}&nbsp;{{ __('common.request') }}</span>
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
                                    <div class="bs-stepper-content">
                                        <!-- your steps content here -->
                                        <div id="request-details" class="content" role="tabpanel" aria-labelledby="request-details-trigger">
                                            <div class="form-group">
                                                <label for="modality">{{ __('common.modality') }}<span class="text-danger">*</span></label>
                                                <select class="form-control" name="modality" id="modality" required>
                                                    @foreach ($allModalities as $modality)
                                                    <option {{ old("modality", 1)== $modality->id ? 'selected' : ''  }} value="{{ $modality->id}}">{{ $modality->name}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="form-group">
                                                <label for="province">{{ __('common.province') }}<span class="text-danger">*</span></label>
                                                <select class="form-control" name="province" id="province" required>
                                                    @foreach ($allProvinces as $province)
                                                    <option {{ old("province", 3152)== $province->id ? 'selected' : ''  }} value="{{ $province->id}}">{{ $province->name}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="form-group">
                                                <label for="city">{{ __('common.city') }}<span class="text-danger">*</span></label>
                                                <select class="form-control" name="city" id="city" required>
                                                    @foreach ($allCities as $city)
                                                    {{-- by default selecting lucknow can change as per client origin --}}
                                                    <option {{ old("city", 3152)== $city->id ? 'selected' : ''  }} value="{{ $city->id}}">{{ $city->name}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="form-group">
                                                <label for="site">{{ __('common.site') }}<span class="text-danger">*</span></label>
                                                <select class="form-control" name="site" id="site" required>
                                                    @foreach ($allSites as $site)
                                                    <option {{ old("site")== $site->id ? 'selected' : ''  }} value="{{ $site->id}}">{{ $site->name}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="form-group">
                                                <label for="deliveryDate">{{ __('common.delivery') }}&nbsp;{{ __('common.date') }}<span class="text-danger">*</span></label>
                                                <div class="input-group date" id="deliveryDate" data-target-input="nearest">
                                                    <input type="text" class="form-control datetimepicker-input" data-target="#deliveryDate" name="deliveryDate" id="deliveryDateId" value={{ old("deliveryDate") }} />
                                                    <div class="input-group-append" data-target="#deliveryDate" data-toggle="datetimepicker">
                                                        <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="expectedReturnDate">{{ __('common.expected') }}&nbsp;{{ __('common.return') }}&nbsp;{{ __('common.date') }}<span class="text-danger">*</span></label>
                                                <div class="input-group date" id="expectedReturnDate" data-target-input="nearest">
                                                    <input type="text" class="form-control datetimepicker-input" data-target="#expectedReturnDate" name="expectedReturnDate" id="expectedReturnDateId" value={{ old("expectedReturnDate") }} />
                                                    <div class="input-group-append" data-target="#expectedReturnDate" data-toggle="datetimepicker">
                                                        <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="card-footer">
                                            <a href="{{ url('engineer/dashboard') }}" class="btn btn-danger">{{ __('common.cancel') }}</a>
                                            <button type="button" class="btn btn-primary" id="nextBtn1" >{{ __('common.next') }}</button>
                                        </div>
                                        </div>
                                        
                                       
                                    </div>
                                    <div id="select-tools" class="content" role="tabpanel" aria-labelledby="select-tools-trigger">
                                        <div class="card-body table-responsive p-0" style="@if (count($allTools) > 15)  height: 600px; @else height: auto; @endif">
                                            <table id="" class="table table-bordered example2 @if (count($allTools) > 15)  table-head-fixed @endif">
                                                <thead>
                                                    <tr>
                                                        <th class="text-center">{{ __('common.tool') }}</th>
                                                        <th class="text-center">{{ __('common.product_no') }}</th>
                                                        <th class="text-center">{{ __('common.serial_no') }}</th>
                                                        <th class="text-center">{{ __('common.owner') }}</th>
                                                        <th class="text-center">{{ __('engineer.request_tool.cal_due_date') }}</th>
                                                        <th class="text-center">{{ __('common.location') }}</th>
                                                        <th class="text-center">{{ __('common.action') }}
                                                            <input class="checkAll" type="checkbox" name="checkAll" value="1">
                                                        </th>
                                                    </tr>
                                                </thead>
                                                <tbody id="toolList">
                                                @foreach ($allTools as $tool)
                                                <tr>
                                                    @if($tool['toolColor'] == 'green') 
                                                    <td class="text-wrap text-center border-left-green">{{$tool['description'] ? $tool['description'] : 'N/A'}}</td>
                                                    @elseif($tool['toolColor'] == 'red')
                                                    <td class="text-wrap text-center border-left-red">{{$tool['description'] ? $tool['description'] : 'N/A'}}</td>
                                                    @else
                                                    <td class="text-wrap text-center border-left-yellow">{{$tool['description'] ? $tool['description'] : 'N/A'}}</td>
                                                    @endif
                                                    
                                                    <td class="text-center">{{$tool['product_no']}}</td>
                                                    <td class="text-center">{{$tool['serial_no']}}</td>
                                                    <td class="text-center">{{$tool['owner']}}</td>
                                                    <td class="text-center">{{$tool['calibration_date'] ? $tool['calibration_date'] : 'N/A'}}</td>
                                                    <td class="text-center text-wrap">{{ $tool['site_address']}}</td>
                                                    <td class="text-center @if($tool['status'] == 'busy') text-danger @endif">
                                                    @if($tool['status'] == 'busy')
                                                    {{$tool['statusMSG']  }}<br/>
                                                    <input class="notify notify-availability" type="checkbox" class="notify-availability" id="{{$tool['id']}}" name="notify[]"data-on-text="Notify"data-off-text="No" data-bootstrap-switch data-off-color="danger" data-on-color="success" @if(in_array($tool['id'], $allNotifyTools)) checked  @endif value="{{$tool['id']}}">
                                                    @else
                                                    
                                                    <input class="requested-tool" type="checkbox" name="requestedTool[]" id="requestedTool" value="{{$tool['id']}}" @if(is_array(old("requestedTool")) &&  in_array($tool['id'], old("requestedTool")))checked  @endif>
                                                    @endif
                                                    
                                                            </td>
                                                        </tr>
                                                        @endforeach

                                                    </tbody>
                                                </table>
                                            </div>
                                            <div class="card-footer">
                                                <a href="{{ url('engineer/dashboard') }}" class="btn btn-danger">{{ __('common.cancel') }}</a>
                                                <button type="button" class="btn btn-primary" id="previousBtn1" onclick="stepper.previous()">{{ __('common.previous') }}</button>
                                                <button type="button" class="btn btn-primary select-tool" id="nextBtn2">{{ __('common.next') }}</button>
                                            </div>

                                        </div>

                                        <div id="final-check" class="content" role="tabpanel" aria-labelledby="final-check-trigger">
                                            <div class="card-body table-responsive p-0" id="finalCheckDataDiv" style="height: auto;">
                                                <table class="table table-bordered table-head-fixed example2">
                                                    <thead>
                                                        <tr>
                                                            <th class="text-center">{{ __('common.tool') }}</th>
                                                            <th class="text-center">{{ __('common.product_no') }}</th>
                                                            <th class="text-center">{{ __('common.serial_no') }}</th>
                                                            <th class="text-center">{{ __('common.owner') }}</th>
                                                            <th class="text-center">{{ __('common.cal_due_date') }}</th>
                                                            <th class="text-center">{{ __('common.location') }}</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody id="finalCheckData">

                                                    </tbody>
                                                </table>
                                            </div>
                                            <div class="card-footer">
                                                <a href="{{ url('engineer/dashboard') }}" class="btn btn-danger">{{ __('common.cancel') }}</a>
                                                <button type="button" class="btn btn-primary" id="previousBtn2" onclick="stepper.previous()">Previous</button>

                                                <button type="button" class="btn btn-primary select-tool" onclick="stepper.next()">{{ __('common.next') }}</button>
                                            </div>

                                        </div>

                                        <div id="submit" class="content" role="tabpanel" aria-labelledby="submit-trigger">
                                            <div class="card-body">
                                                <div class="row">
                                                    <div class="col-lg-6">
                                                        <div class="form-group">
                                                            <label for="site">{{ __('common.please') }}&nbsp;
                                                                {{ __('common.select') }}&nbsp;
                                                                {{ __('common.pickup') }}&nbsp;
                                                                {{ __('common.way') }}&nbsp;</label>

                                                            @foreach (config('services.pick_by') as $pickBy => $pickupLabel)

                                                            <div class="form-check">
                                                                <input class="form-check-input pickBy" value="{{$pickBy}}" id="{{$pickBy}}" type="radio" name="pickup" @if( old("pickup", 'UPS' )==$pickBy )checked @endif>
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
                                                <a href="{{ url('engineer/dashboard') }}" class="btn btn-danger requestToolBtn">{{ __('common.cancel') }}</a>
                                                <button type="button" class="btn btn-primary requestToolBtn" onclick="stepper.previous()">{{ __('common.previous') }}</button>
                                                <input type="submit" class="btn btn-warning submit requestToolBtn" disabled id="submitBtn" value="{{ __('common.submit') }}">
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
        <!-- /.content -->
    </div>
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
<script type="text/javascript" src="{{ asset('assets/js/engineer/request_tools.js') }}"></script>
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

            $("input[data-bootstrap-switch]").each(function() {
                $(this).bootstrapSwitch('state', $(this).prop('checked'));
            })
        })
    });
</script>
@stop