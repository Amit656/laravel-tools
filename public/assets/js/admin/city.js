$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    },
});

$(document).ready(function() {
    $.fn.dataTable.ext.errMode = 'none';

    $('#cityTable').on( 'error.dt', function ( e, settings, techNote, message ) {
    console.log( 'An error has been reported by DataTables: ', message );
    } ) ;

   $('#cityTable').DataTable( {
        
        "searching": true,
        "processing": true,
        "serverSide": true,
        "responsive": true,
        language: {
            searchPlaceholder: "Search by city name"
        },
        "ajax": {
            "url": APP_URL + '/admin/city/list',
            "type": "GET",
        },
        columns: [
            { "data": null},
            { "data": "name" },
            { "data": "provinceName" },
            { "data": "created_at" },
            {
                "render": function (data, type, row) {
                    return `<a href="javascript:void(0)" onclick=getCityInfo(${row.id}) id="${row.id}" data-toggle="tooltip" data-title='Edit' class="mr-1">
                           <i class="fas fa-edit"></i>
                      </a><a href="javascript:void(0)" onclick=deleteCity(${row.id}) id="${row.id}" data-toggle="tooltip" data-title='Delete' class="mr-1">
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
            {bSortable: false, targets: [0,4]},
        ],
        "order": [
            [1, "asc"]
        ]
    } );
} );

$('#AddCityModal').on('hidden.bs.modal', function(event){
    $('#addEditCityForm')[0].reset();
});

$('#addEditCityForm').submit(function (event) {
event.preventDefault();

let path = APP_URL + '/admin/city/create';

let cityId = $.trim($('#cityId').val());

if (cityId) {
    /**
     * if edit request then change its method
     */
    path = APP_URL + '/admin/city/update';
}
let cityName = $.trim($('#cityName').val());
let province = $.trim($('#province').val());

if (!cityName) {
    toastr.error('Please enter City Name.')
    return false;
}

if (!province) {
    toastr.error('Please select province.')
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
            
            let datatable = $('#cityTable').DataTable();
            datatable.draw();
            
            $('#AddCityModal').modal('hide');              
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
 * This function returns the info of city
 * @param cityId
 */
 function getCityInfo(cityId) {
    $.ajax({
        type: "GET",
        url: APP_URL + '/admin/city/getinfo/' + cityId,
        
        success: function (result) {
            $('#cityId').val(result.data.id);
            $("#province").val(result.data.province_id);
            $("#cityName").val(result.data.name);
            $('#AddCityModal').modal('show');
        },
        error: function () {
            toastr.error("Please try again later.");
        }
    });
}

/**
 * This function deletes the city
 * @param cityId
 * 
 */
 function deleteCity(cityId) {
    var deleteUsertoast = toastr.warning("<br /><button type='button' class='btn btn-sm btn-primary' value='yes'>Yes</button><button type='button' class='btn btn-sm btn-danger'  value='no' >No</button>",'Are you sure you want to delete this site?',
    {
        "timeOut": 0,
        "extendedTimeOut": 0,
        onclick: function (toast) {
          deleteUsertoast.hide();
          value = toast.target.value
          if (value == 'yes') {
            $.ajax({
                    type: 'DELETE',
                    url: APP_URL + '/admin/city/delete/' + cityId,
                   
                    success: function (response) {
                        if (response.success != true) {
                            toastr.error(response.message);
                        } else {
                            toastr.success(response.message)

                            let datatable = $('#cityTable').DataTable();
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



