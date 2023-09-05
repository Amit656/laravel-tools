$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    },
});

let calibrationReportTable

$(document).ready(function() {
    var toolId = $('#tool_id').val();

    $.fn.dataTable.ext.errMode = 'none';

    $('#calibrationReportTable').on( 'error.dt', function ( e, settings, techNote, message ) {
    console.log( 'An error has been reported by DataTables: ', message );
    } ) ;

    calibrationReportTable = $('#calibrationReportTable').DataTable( {
        
        "searching": true,
        "processing": true,
        "serverSide": true,
        "responsive": true,
        language: {
            searchPlaceholder: "Search by date"
        },
        "ajax": {
            "url": APP_URL + '/admin/calibration-report/list/' + toolId,
            "type": "GET",
        },
        columns: [
            { 
                "width": "50",
                "data": null
            },
            { 
                "width": "250",
                "data": "calibrated_on"
             },
            {
                "width": "300",
                "render": function (data, type, row) {
                    if(row.report != null) {
                        return `<a href="${APP_URL}/admin/calibration-report/download/${toolId}/${row.report}" data-toggle="tooltip" data-title='Download' class="mr-1">
                        ${row.report}
                          </a>`;  
                    }else{ return 'N/A';}
                }
            },
        ],
        "fnRowCallback": function (nRow, aData, iDisplayIndex) {
            $("td:nth-child(1)", nRow).html(iDisplayIndex + 1);
            return nRow;
        },
        columnDefs: [
            {bSortable: false, targets: [0,2]},
        ],
        "order": [
            [1, "asc"]
        ]
    } );
});

$('#calibrationReportModal').on('hidden.bs.modal', function(event){
        $('#calibrated_onInput').val(moment(new Date()).format('MM/DD/YYYY')); 
        $('#next_calibration_due_dateInput').val("");
        $('#tool_condition').val("");
        $('#report').val("");
});


/**
 * Create and update user
 */
$('#addCalibrationReport').submit(function (event) {
    event.preventDefault();
    var toolCondition = $.trim($('#tool_condition').val());

    if (!toolCondition) {
        toastr.error('Please select tool condition.')
        return false;
    }
    
    const formData = new FormData(this);

    $.ajax({
        url: APP_URL + '/admin/calibration-report/create',
        type: 'POST',
        cache: false,
        contentType: false,
        processData: false,
        stateSave: true,
        data: formData,
        success: function (response) {
            if (response.success != true) {
                toastr.error(response.message);
            } else {
               
                toastr.success(response.message)
                $('#calibrationReportModal').modal('hide');
                calibrationReportTable.row.add([
                    this.toolId,
                    this.created_at,
                ]).draw();
            }
        },
        
        error: function(xhr, status, error)
        {
          $.each(xhr.responseJSON.errors, function (key, item)
          {
            toastr.error(item);
          });

        }
    });
});

