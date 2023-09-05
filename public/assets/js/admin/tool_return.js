$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    },
});

let filterData = "new";
let dataTable;

$(document).ready(function() {

    $.fn.dataTable.ext.errMode = 'none';

    $('#toolReturnTable').on( 'error.dt', function ( e, settings, techNote, message ) {
    console.log( 'An error has been reported by DataTables: ', message );
    } ) ;

    dataTable = $('#toolReturnTable').DataTable( {
        
        "searching": true,
        "processing": true,
        "serverSide": true,
        "responsive": true,
        language: {
            searchPlaceholder: "Search"
        },
        "ajax": {
            "url": APP_URL + '/admin/tool-return/list',
            "type": "GET",
            "data": function ( d ) {
              d.type = $('#filter').val();
            }
        },
        columns: [
            { "data": null},
            { "data": "description" },
            { "data": "product_no" },
            { "data": "serial_no" },
            { "data": "name" },
            { "data": "drop_type" },
            { "data": "created_at" },
            { "data": "return_status" ,
                
                "render": function (data, type, row) {
                    return `<span>${row.return_status}${(row.comment != ''  ? `<br/>(${row.comment})` : ``)}</span>`;
                }
            },
            { "data": "siteAddress" },
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
                      case 'approved':
                        return `<a href="javascript:void(0)" data-toggle="modal" data-target="#acceptToolReturnModal" onclick="acceptReturn(${row.id}, '${row.drop_type}', ${row.details ? `'${row.details}'` : ''})" title="Accept Return" id="${row.id}" data-toggle="tooltip" data-title='Accept' class="mr-1">
                               <i class="fas fa-check"></i>
                          </a>`
                        break;
                      case 'returned':
                        return `<span class="text-success">${row.status}</span>`;
                        break                   
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
            {bSortable: false, targets: [0,9]},
        ],
        "order": [
            [6, "desc"]
        ],
    } );
} );

$('#filter').change(function(){
    filterData = $('#filter').val();

    dataTable.ajax.url(APP_URL + '/admin/tool-return/list').load();
});

$('#acceptToolReturnModal').on('hidden.bs.modal', function(event){
        $('#addToolReturnForm')[0].reset();
        $("#details").html("");
});

$('#addToolReturnForm').submit(function (event) {
    event.preventDefault();
    $('.acceptReturnBtn').prop('disabled', true);

    let path = APP_URL + '/admin/tool-return/accept';

    $('#sveForm').prop('disabled', true);
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
                $('.acceptReturnBtn').prop('disabled', false);

                toastr.success(response.message)
                
                let datatable = $('#toolReturnTable').DataTable();
                datatable.draw();
                
                $('#acceptToolReturnModal').modal('hide');              
            }
        },
        
        error: function(xhr, status, error)
        {
            $('.acceptReturnBtn').prop('disabled', false);
            $.each(xhr.responseJSON.errors, function (key, item)
            {
                toastr.error(item);
            });

        }
    });

    $('#sveForm').prop('disabled', false);
});

/**
 * @param returnToolId
 * 
 */

function acceptReturn(returnToolId, pickupType, details) {
   $('#returnToolId').val(returnToolId);
   console.log('details', details);
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

$('.pickup').click(function(){
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

