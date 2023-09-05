$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    },
});

$(document).ready(function() {

    $.fn.dataTable.ext.errMode = 'none';

    $('#modalityTable').on( 'error.dt', function ( e, settings, techNote, message ) {
    console.log( 'An error has been reported by DataTables: ', message );
    } ) ;
    
   $('#modalityTable').DataTable( {
        
        "searching": true,
        "processing": true,
        "serverSide": true,
        "responsive": true,
        language: {
            searchPlaceholder: "Search by modality name"
        },
        "ajax": {
            "url": APP_URL + '/admin/modality/list',
            "type": "GET",
        },
        columns: [
            { "data": null},
            { "data": "name" },
            { "data": "created_at" },
            {
                "render": function (data, type, row) {
                    return `<a href="javascript:void(0)" onclick=getModalityInfo(${row.id}) id="${row.id}" data-toggle="tooltip" data-title='Edit' class="mr-1">
                           <i class="fas fa-edit"></i>
                      </a><a href="javascript:void(0)"  onclick=deleteModality(${row.id}) id="${row.id}" data-toggle="tooltip" data-title='Delete' class="mr-1">
                           <i class="fa fa-trash"></i>
                      </a>`;
                }
            }
        ],
        "fnRowCallback": function (nRow, aData, iDisplayIndex) {
            $("td:nth-child(1)", nRow).html(iDisplayIndex + 1);
            return nRow;
        },
        columnDefs: [
            {bSortable: false, targets: [0,3]},
        ],
        "order": [
            [1, "asc"]
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
                
                let datatable = $('#modalityTable').DataTable();
                datatable.draw();
                
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
    $.ajax({
        type: "GET",
        url: APP_URL + '/admin/modality/edit/' + modalityId,
        
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
    var deleteUsertoast = toastr.warning("<br /><button type='button' class='btn btn-sm btn-primary' value='yes'>Yes</button><button class='btn btn-sm btn-danger' type='button'  value='no' >No</button>",'Are you sure you want to delete this modality?',
    {
        "timeOut": 0,
        "extendedTimeOut": 0,
        onclick: function (toast) {

        deleteUsertoast.hide();
        value = toast.target.value
          if (value == 'yes') {
            $.ajax({
                    type: 'DELETE',
                    url: APP_URL + '/admin/modality/delete/' + modalityId,
                   
                    success: function (response) {
                        if (response.success != true) {
                            toastr.error(response.message);
                        } else {
                            toastr.success(response.message)

                            let datatable = $('#modalityTable').DataTable();
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

