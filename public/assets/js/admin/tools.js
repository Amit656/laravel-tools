$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    },
});

$(document).ready(function() {

    $.fn.dataTable.ext.errMode = 'none';

    $('#toolsTable').on( 'error.dt', function ( e, settings, techNote, message ) {
    console.log( 'An error has been reported by DataTables: ', message );
    } );

    $('#toolsTable').DataTable( {
         
         "searching": true,
         "processing": true,
         "serverSide": true,
         "responsive": true,
         language: {
            searchPlaceholder: "Search"
        },
         "ajax": {
             "url": APP_URL + '/admin/tools/list',
             "type": "GET",
         },
         columns: [
             { "data": null},
             { "data": "description" },
             { "data": "tool_id" },
             { "data": "modalityName" },
             { "data": "siteName" },
             { "data": "serial_no" },
             { "data": "product_no" },
            {
                "data": "calibration_date",
                "render": function (data, type, row) {
                    if(row.calibration_date){
                        return `${row.calibration_date}`;
                    }else{ return 'N/A';}
                }
            },
            {
                "data": "due_in_days",
                "render": function (data, type, row) {
                    if(row.due_in_days < 1){
                        return '0';
                    }else{ return `${row.due_in_days}`;}
                }
            },
            {
                "data": "calibration_report",
                "render": function (data, type, row) {
                    if(row.calibration_report){
                        return `${row.calibration_report.calibrated_on}`;
                    }else{ return 'N/A';}
                }
            },
            { "data": "status" },
            
            { "data": "sort_field" },
            {
                "render": function (data, type, row) {
                    if(row.QR_code_url != null) {
                      return `<a target="_blank" href="${row.QR_code_url}"><img src="${row.QR_code_url}" alt="qr code" class="img-thumbnail" height="100px" width="100px"></a>`;  
                  }else{ return 'N/A';}
                }
            },
            {
                "render": function (data, type, row) {
                    if(row.image != null) {
                      return `<a target="_blank" href="${row.image_url}"><img src="${row.image_url}" alt="image" class="img-thumbnail" height="100px" width="100px"></a>`;  
                  }else{ return 'N/A';}
                }
            },
            {
                 "render": function (data, type, row) {
                     return `<a href="javascript:void(0)" onclick=getToolInfo(${row.id}) id="${row.id}" data-toggle="tooltip" data-title='Edit' class="mr-1">
                            <i class="fas fa-edit"></i>
                       </a><a href="javascript:void(0)"  onclick=deleteTool(${row.id}) id="${row.id}" data-toggle="tooltip" data-title='Delete' class="mr-1">
                            <i class="fa fa-trash"></i>
                       </a><a href="${APP_URL}/admin/calibration-report/${row.id}" id="${row.id}" data-toggle="tooltip" data-title='Report' class="mr-1">
                            <i class="fas fa-eye"></i>
                        </a>`;
                 }
            },
         ],
         "fnRowCallback": function (row, data, iDisplayIndex) {
            if(data.tool_condition_status == "green"){
                $("td:nth-child(1)", row).addClass('border-left-green');
                $("td:nth-child(8)", row).css('background-color', '#28a745');
                $("td:nth-child(9)", row).css('background-color', '#28a745');
                $("td:nth-child(10)", row).css('background-color', '#28a745');
            }else if(data.tool_condition_status == "red"){
                $("td:nth-child(1)", row).addClass('border-left-red');
                $("td:nth-child(8)", row).css('background-color', '#dc3545');
                $("td:nth-child(9)", row).css('background-color', '#dc3545');
                $("td:nth-child(10)", row).css('background-color', '#dc3545');
            }else{
                $("td:nth-child(1)", row).addClass('border-left-yellow');
                $("td:nth-child(8)", row).css('background-color', '#ffc107');
                $("td:nth-child(9)", row).css('background-color', '#ffc107');
                $("td:nth-child(10)", row).css('background-color', '#ffc107');
            }
             $("td:nth-child(1)", row).html(iDisplayIndex + 1);
             return row;
         },
         'columnDefs':[{
            'targets': [0,7,12,13,14],
            'orderable': false
        }],
        "order": [
            [2, "asc"]
        ],
     });
 } );

$('#AddToolModal').on('hidden.bs.modal', function(event){
        $('#addEditToolForm')[0].reset();
});


/**
 * Create and update user
 */
$('#addEditToolForm').submit(function (event) {
    event.preventDefault();
    let path = APP_URL + '/admin/tools/create';

    if ($.trim($('#toolId').val())) {
        /**
         * if edit request then change its method
         */
        path = APP_URL + '/admin/tools/update';
    }
    
    const formData = new FormData(this);

    $.ajax({
        url: path,
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
                let datatable = $('#toolsTable').DataTable().ajax.reload();
                // datatable.reload();
                $('#AddToolModal').modal('hide');
                
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

/**
 * This function returns the info of tool
 * @param toolId
 */
function getToolInfo(toolId) {
    //$('#resetBtnadd').click();
    $.ajax({
        type: "GET",
        url: APP_URL + '/admin/tools/getinfo/' + toolId,
        
        success: function (result) {
            $('#toolId').val(result.data.id);
            $("#toolNo").val(result.data.tool_id);
            $('#modality').val(result.data.modality_id);
            $('#site').val(result.data.site_id);
            $('#serialNo').val(result.data.serial_no);
            $("#productNo").val(result.data.product_no);
            $('#typeOfUse').val(result.data.type_of_use);
            $('#asset').val(result.data.asset);
            $("#sortField").val(result.data.sort_field);
            $('#calibrationDateInput').val(result.data.calibration_date);
            $('#desc').val(result.data.description);
            $('#oldImage').val(result.data.image_url);
            var status = result.data.status;
            let toolStatus = $('.toolStatus').map((_,ed) => ed.value).get();
            toolStatus.forEach((data, index) => {
                $(`#toolStatus_${status}`).prop('checked', true);
          
            });
            //var src = APP_URL + `/tools/${result.data.image}`;
            //$(".tools-image").css("display", "block");
            //$(".tools-image").attr("src", src);

            $('#AddToolModal').modal('show');
        },
        error: function () {
            toastr.error("Please try again later.");
        }
    });
}


/**
 * This function deletes the tool
 * @param toolId
 * 
 */
function deleteTool(toolId) {
    var deleteUsertoast = toastr.warning("<br /><button type='button' class='btn btn-sm btn-primary' value='yes'>Yes</button><button type='button' class='btn btn-sm btn-danger'  value='no' >No</button>",'Are you sure you want to delete this tool?',
    {
        "timeOut": 0,
        "extendedTimeOut": 0,
        onclick: function (toast) {

          deleteUsertoast.hide();
          value = toast.target.value
          if (value == 'yes') {
            $.ajax({
                    type: 'DELETE',
                    url: APP_URL + '/admin/tools/delete/' + toolId,
                    
                    success: function (response) {
                        if (response.success != true) {
                            toastr.error(response.message);
                        } else {
                            toastr.success(response.message)
                            let datatable = $('#toolsTable').DataTable();
                            datatable.draw();
                        }
                        //usersList.reload();
                    },
                    error: function (response) {
                        toastr.error(response.responseJSON.errors.toolId[0]);
                    }
                });  
          }
        }

    })
}

$('.export-csv').click(function(){
    var search = $('#toolsTable_filter input').val();

    window.location.href = APP_URL + '/admin/export?search='+search;
    
})