$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    },
});

$(document).ready(function() {

    $.fn.dataTable.ext.errMode = 'none';

    $('#returnToolHistoryTable').on( 'error.dt', function ( e, settings, techNote, message ) {
    console.log( 'An error has been reported by DataTables: ', message );
    } );
    
   $('#returnToolHistoryTable').DataTable( {
        
        "searching": true,
        "processing": true,
        "serverSide": true,
        "responsive": true,
        language: {
            searchPlaceholder: "Search"
        },
        "ajax": {
            "url": APP_URL + '/engineer/history-return-tools-list',
            "type": "GET",
        },
        columns: [
            { "data": null},
            {
                "data": "description",
                "render": function (data, type, row) {
                    return row.description;
                }
            },{
                "data": "product_no",
                "render": function (data, type, row) {
                    return row.product_no;
                }
            },{
                 "data": "serial_no",
                "render": function (data, type, row) {
                    return row.serial_no;
                }
            },{
                "data": "drop_type",
                "render": function (data, type, row) {
                    return `<span>${row.drop_type}${(row.details != null  ? `<br/>(${row.details})` : ``)}</span>`;
                }
            },{
                "data": "created_at",
                "render": function (data, type, row) {
                    return row.created_at;
                }
            },{
                "data": "siteAddress",
                "render": function (data, type, row) {
                    return row.siteAddress;
                }
            },{
                "data": "status",
                "render": function (data, type, row) {
                    switch(row.status){
                        case 'returned':
                            return `<span class="text-success">Returned<span>`;
                        break;

                        default:
                            return 'Pending';
                        break;
                    }
                }
            }
        ],
        "fnRowCallback": function (row, data, iDisplayIndex) {
            if(data.tool_condition_status == "green"){
                $("td:nth-child(1)", row).addClass('border-left-green');
            }else if(data.tool_condition_status == "red"){
                
                $("td:nth-child(1)", row).addClass('border-left-red');
            }else{
                $("td:nth-child(1)", row).addClass('border-left-yellow');
            }
             $("td:nth-child(1)", row).html(iDisplayIndex + 1);
             return row;
         },
        'columnDefs':[{
            'targets': [0],
            'orderable': false
        }],
        "order": [
            [1, "desc"]
        ]
    } );
} );

$('#AddModalityModal').on('hidden.bs.modal', function(event){
        $('#addEditModalityForm')[0].reset();
});

$('#addEditModalityForm').submit(function (event) {
    event.preventDefault();

    let path = APP_URL + '/admin/modality/create';

    let modalityId = $.trim($('#modalityId').val());

    if (modalityId) {
        /**
         * if edit request then change its method
         */
        path = APP_URL + '/admin/modality/update';
    }
    let modalityName = $.trim($('#modalityName').val());

    if (!modalityName) {
        toastr.error('Please enter Name.')
        return false;
    }

    const formData = new FormData(this);
    $.ajax({
        url: path,
        type: 'POST',
        cache: false,
        contentType: false,
        processData: false,
        data: formData,
        success: function (response) {
            if (response.success != true) {
                toastr.error(response.message);
            } else {
                toastr.success(response.message)
                
                let datatable = $('#returnToolHistoryTable').DataTable();
                datatable.clear().draw();
                datatable.columns.adjust().draw();
                
                $('#AddModalityModal').modal('hide');              
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
 * This function returns the info of user
 * @param modalityId
 */
function getModalityInfo(modalityId) {
    //$('#resetBtnadd').click();

    // var row = $(this).parent().parent().children().index($(this).parent())

    // var table = $('#returnToolHistoryTable').DataTable();

    $.ajax({
        type: "GET",
        url: APP_URL + '/admin/modality/edit/' + modalityId,
        data: {
            modalityId: modalityId
        },
        success: function (result) {
            $('#modalityId').val(result.id);
            $("#modalityName").val(result.name);
            $('#AddModalityModal').modal('show');
        },
        error: function () {
            toastr.error("Please try again later.");
        }
    });
}


/**
 * This function deletes the user
 * @param modalityId
 * 
 */
function deleteModality(modalityId) {
    var deleteUsertoast = toastr.warning("<br /><button type='button' class='btn btn-sm btn-primary' value='yes'>Yes</button><button class='btn btn-sm btn-danger' type='button'  value='no' >No</button>",'Are you sure you want to delete this user?',
    {
        allowHtml: true,
        "fadeOut": 1000,
        onclick: function (toast) {
        deleteUsertoast.hide();
        value = toast.target.value
        if (value == 'yes') {
            $.ajax({
                    type: 'DELETE',
                    url: APP_URL + '/admin/modality/delete/' + modalityId,
                    data: {
                        modalityId: modalityId,
                    },
                    success: function (response) {
                        if (response.success != true) {
                            toastr.error(response.message);
                        } else {
                            toastr.success(response.message)

                            let datatable = $('#returnToolHistoryTable').DataTable();
                            datatable.clear().draw();
                            datatable.columns.adjust().draw();
                           
                        }
                    },
                    error: function (response) {
                        toastr.error("Please try again later.");
                    }
                });  
          }
        }

    })
}

