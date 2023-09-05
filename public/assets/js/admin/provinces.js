$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    },
});

$(document).ready(function() {

    $.fn.dataTable.ext.errMode = 'none';

    $('#provincesTable').on( 'error.dt', function ( e, settings, techNote, message ) {
    console.log( 'An error has been reported by DataTables: ', message );
    } ) ;
    
   $('#provincesTable').DataTable( {
        
        "searching": true,
        "processing": true,
        "serverSide": true,
        "responsive": true,
        language: {
            searchPlaceholder: "Search by province name"
        },
        "ajax": {
            "url": APP_URL + '/admin/provinces/list',
            "type": "GET",
        },
        columns: [
            { "data": null},
            { "data": "name" },
            { "data": "created_at" },
            {
                "render": function (data, type, row) {
                    return `<a href="javascript:void(0)" onclick=getProvinceInfo(${row.id}) id="${row.id}" data-toggle="tooltip" data-title='Edit' class="mr-1">
                           <i class="fas fa-edit"></i>
                      </a><a href="javascript:void(0)" onclick=deleteProvince(${row.id}) id="${row.id}" data-toggle="tooltip" data-title='Delete' class="mr-1">
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

$('#addProvincesModal').on('hidden.bs.modal', function(event){
        $('#addEditProvincesForm')[0].reset();
});

$('#addEditProvincesForm').submit(function (event) {
    event.preventDefault();

    let path = APP_URL + '/admin/provinces/create';

    let provinceId = $.trim($('#provinceId').val());

    if (provinceId) {
        /**
         * if edit request then change its method
         */
        path = APP_URL + '/admin/provinces/update';
    }
    let provinceName = $.trim($('#provinceName').val());

    if (!provinceName) {
        toastr.error('Please enter Province Name.')
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
                let datatable = $('#provincesTable').DataTable();
                datatable.draw();
                
                $('#addProvincesModal').modal('hide');              
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
 * This function returns the info of province
 * @param provinceId
 */
 function getProvinceInfo(provinceId) {
    $.ajax({
        type: "GET",
        url: APP_URL + '/admin/provinces/edit/' + provinceId,
        success: function (result) {
            $('#provinceId').val(result.data.id);
            $("#provinceName").val(result.data.name);
            $('#addProvincesModal').modal('show');
        },
        error: function () {
            toastr.error("Please try again later.");
        }
    });
}

/**
 * This function deletes the province
 * @param provinceId
 * 
 */
 function deleteProvince(provinceId) {
    var deleteUsertoast = toastr.warning("<br /><button type='button' class='btn btn-sm btn-primary' value='yes'>Yes</button><button class='btn btn-sm btn-danger' type='button'  value='no' >No</button>",'Are you sure you want to delete this province?',
    {
        "timeOut": 0,
        "extendedTimeOut": 0,
        onclick: function (toast) {

        deleteUsertoast.hide();
          value = toast.target.value
          if (value == 'yes') {
            $.ajax({
                    type: 'DELETE',
                    url: APP_URL + '/admin/provinces/delete/' + provinceId,
            
                    success: function (response) {
                        if (response.success != true) {
                            toastr.error(response.message);
                        } else {
                            toastr.success(response.message)
                            let datatable = $('#provincesTable').DataTable();
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