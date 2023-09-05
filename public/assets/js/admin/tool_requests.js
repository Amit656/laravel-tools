$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    },
});
let dataTable;
let path = APP_URL + '/admin/tool-requests/list';


$(document).ready(function() {

    $.fn.dataTable.ext.errMode = 'none';

    $('#toolRequestsTable').on( 'error.dt', function ( e, settings, techNote, message ) {
    console.log( 'An error has been reported by DataTables: ', message );
    } ) ;

    dataTable = $('#toolRequestsTable').DataTable( {
        
        "searching": true,
        "processing": true,
        "serverSide": true,
        "responsive": true,
        language: {
            searchPlaceholder: "Search"
        },
        "ajax": {
            "url": path,
            "type": "GET",
            "data": function ( d ) {
              d.type = $('#filter').val();
            },
        },
        columns: [
            { "data": null},
            { "data": "description" },
            { "data": "product_no" },
            { "data": "serial_no" },
            { "data": "name" },
            { "data": "pickup_type" },
            { "data": "created_at" },
            { "data": "delivery_date" },
            {
                "render": function (data, type, row) {
                    return `${row.siteAddress}, ${row.cityName}, ${row.provinceName}`;
                }
            },
            {
                "render": function (data, type, row) {
                    if(row.qr_code != null) {
                      return `<a target="_blank" href="${APP_URL}/storage/${row.qr_code}"><img src="${APP_URL}/storage/${row.qr_code}" alt="qr code" class="img-thumbnail" width="100px"></a>`;  
                  }else{ return 'N/A';}
                }
            },
            {
                "render": function (data, type, row) {
                    if(row.image != null) {
                      return `<a target="_blank" href="${APP_URL}/storage/${row.image}"><img src="${APP_URL}/storage/${row.image}" alt="image" class="img-thumbnail" width="100px"></a>`;  
                  }else{ return 'N/A';}
                }
            },
            {
                "render": function (data, type, row) {
                    switch(row.status) {
                      case 'Pending':
                        return `<a href="javascript:void(0)" onclick="acceptRequest(${row.id}, '${row.pickup_type}', ${row.details ? `'${row.details}'` : ''})" title="Accept Request" id="${row.id}" data-toggle="tooltip" data-title='Accept' class="mr-1">
                               <i class="fas fa-check"></i>
                          </a><a href="javascript:void(0)" onclick=rejectRequest(${row.id}) title="Reject Request" id="${row.id}" data-toggle="tooltip" data-title='Rejact' class="mr-1">
                               <i class="fa fa-times red-color"></i>
                          </a>`;
                        break;
                      case 'Rejected':
                        return `<span class="text-danger">${row.status}</span>`;
                        break;

                      default:
                        return `<span class="text-success">${row.status}</span>`;
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
        columnDefs: [
            {bSortable: false, targets: [0, 9, 10, 11]},
        ],
        "order": [
            [6, "desc"]
        ],
    } );
} );

$('#filter').change(function(){
    dataTable.ajax.url(path).load();
});

$('#acceptToolRequestModal').on('hidden.bs.modal', function(event){
        $('#addToolRequestForm')[0].reset();
        $("#details").html("");
});

$('#addToolRequestForm').submit(function (event) {
    event.preventDefault();
    $('.submitBtn').prop('disabled', true);
    $('.cancelBtn').prop('disabled', true);
    let path = APP_URL + '/admin/tool-requests/accept';

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
                $('.submitBtn').prop('disabled', false);
                $('.cancelBtn').prop('disabled', false);
                toastr.success(response.message)
                
                let datatable = $('#toolRequestsTable').DataTable();
                datatable.draw();
                
                $('#acceptToolRequestModal').modal('hide');              
            }
        },
        
        error: function(xhr, status, error)
        {
          $('.submitBtn').prop('disabled', false);
          $('.cancelBtn').prop('disabled', false);
          $.each(xhr.responseJSON.errors, function (key, item)
          {
            toastr.error(item);
          });

        }
    });
});

/**
 * @param requestToolId
 * 
 */

function acceptRequest(requestToolId, pickupType, details) {
   $('#acceptToolRequestModal').modal('show');
   $('#requestToolId').val(requestToolId);
   $("#details").html(details);

   $('input[name=pickup]').each(function () {
        if (($(this).val().toLowerCase()) == pickupType.toLowerCase()) {
            $(this).prop('checked',true);
        }
    });

    if (pickupType == 'EPT') {
        $("#EPTDetails").removeClass("d-none");
        $("#UPSDetails").addClass("d-none");
        $("#details").removeClass("d-none");
    }else if (pickupType == 'UPS') {
        $("#UPSDetails").removeClass("d-none");
        $("#EPTDetails").addClass("d-none");
        $("#details").removeClass("d-none");
    }else{
        $("#EPTDetails").addClass("d-none");
        $("#UPSDetails").addClass("d-none");
        $("#details").addClass("d-none");
    }
}

$('.form-check-input').click(function(){
    $('#details').val("");
    if ($(this).val() == 'EPT') {
        $("#EPTDetails").removeClass("d-none");
        $("#UPSDetails").addClass("d-none");
		$("#details").removeClass("d-none");
    }else if ($(this).val() == 'UPS') {
        $("#UPSDetails").removeClass("d-none");
        $("#EPTDetails").addClass("d-none");
		$("#details").removeClass("d-none");
    }else{
        $("#EPTDetails").addClass("d-none");
        $("#UPSDetails").addClass("d-none");
		$("#details").addClass("d-none");
    }
})

function rejectRequest(requestToolId) {
    var deletetoast = toastr.warning("<br /><button type='button' class='btn btn-sm btn-primary' value='yes'>Yes</button><button class='btn btn-sm btn-danger' type='button'  value='no' >No</button>",'Are you sure you want to reject this tool request?',
    {
        "timeOut": 0,
        "extendedTimeOut": 0,
        onclick: function (toast) {

        deletetoast.hide();
        value = toast.target.value
          if (value == 'yes') {
            $.ajax({
                    type: 'POST',
                    url: APP_URL + '/admin/tool-requests/reject/' + requestToolId,
                   
                    success: function (response) {
                        if (response.success != true) {
                            toastr.error(response.message);
                        } else {
                            toastr.success(response.message)

                            let datatable = $('#toolRequestsTable').DataTable();
                            datatable.draw();
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

