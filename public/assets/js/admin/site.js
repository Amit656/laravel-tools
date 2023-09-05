$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    },
});

$(document).ready(function() {

    $.fn.dataTable.ext.errMode = 'none';

    $('#sitesTable').on( 'error.dt', function ( e, settings, techNote, message ) {
    console.log( 'An error has been reported by DataTables: ', message );
    } ) ;

    $('#sitesTable').DataTable( {
         
         "searching": true,
         "processing": true,
         "serverSide": true,
         "responsive": true,
         language: {
            searchPlaceholder: "Search by site name"
        },
         "ajax": {
             "url": APP_URL + '/admin/sites/list',
             "type": "GET",
         },
         columns: [
             { "data": null},
             { "data": "name" },
             { "data": "address" },
             { "data": "cityName" },
             { "data": "provinceName" },
             { "data": "type" },
             { "data": "created_at" },
             {
                 "render": function (data, type, row) {
                     return `<a href="javascript:void(0)" onclick=getSiteInfo(${row.id}) id="${row.id}" data-toggle="tooltip" data-title='Edit' class="mr-1">
                            <i class="fas fa-edit"></i>
                       </a><a href="javascript:void(0)"  onclick=deleteSite(${row.id}) id="${row.id}" data-toggle="tooltip" data-title='Delete' class="mr-1">
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
            {bSortable: false, targets: [0,7]},
        ],
        "order": [
            [1, "asc"]
        ]
     } );
 } );

$('#AddSiteModal').on('hidden.bs.modal', function(event){
        $('#addEditSiteForm')[0].reset();
        $('#cityName').val('');
        $('#cityName').html('');
        $('#desc').html('');
});


/**
 * Create and update site
 */
$('#addEditSiteForm').submit(function (event) {
    event.preventDefault();
    let path = APP_URL + '/admin/sites/create';

    if ($.trim($('#siteId').val())) {
        /**
         * if edit request then change its method
         */
        path = APP_URL + '/admin/sites/update';
    }
    let siteName = $.trim($('#siteName').val());
    if (!siteName) {
        toastr.error("Please enter site name");
        return false;
    }
    let siteAddress = $.trim($('#siteAddress').val());
    if (!siteAddress) {
        toastr.error("Please enter site address");
        return false;
    }
    let city = $.trim($('#city').val());
    if (!city) {
        toastr.error("Please select city");
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

                let datatable = $('#sitesTable').DataTable();
                datatable.draw();

                $('#AddSiteModal').modal('hide');
                
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
 * This function returns the info of site
 * @param siteId
 */
function getSiteInfo(siteId) {
    $('#resetBtnadd').click();
    $.ajax({
        type: "GET",
        url: APP_URL + '/admin/sites/getinfo/' + siteId,
        
        success: function (result) {
            
            $('#siteId').val(result.data.id);
            $("#siteName").val(result.data.name);
            $('#siteAddress').val(result.data.address);
            $('#province').val(result.data.province_id);

            let promise = getCities(result.data.province_id);

            fillCities(promise, result.data.city_id);
            $('#desc').html(result.data.description);
            //$('#city').append($("<option></option>").attr("value", result.data.address).attr("selected", "true").text(result.data.city.name));

            var type = result.data.type;
            let siteType = $('.siteType').map((_,ed) => ed.value).get();
            siteType.forEach((data, index) => {
                $(`#siteType_${type}`).prop('checked', true);
          
            });
            $('#AddSiteModal').modal('show');
        },
        error: function () {
            toastr.error("Please try again later.");
        }
    });
}


/**
 * This function deletes the site
 * @param siteId
 * 
 */
function deleteSite(siteId) {
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
                    url: APP_URL + '/admin/sites/delete/' + siteId,
                    
                    success: function (response) {
                        if (response.success != true) {
                            toastr.error(response.message);
                        } else {
                            toastr.success(response.message)

                            let datatable = $('#sitesTable').DataTable();
                            datatable.draw();
                        }
                        //usersList.reload();
                    },
                    error: function (response) {
                        toastr.error("Please try again later.");
                    }
                });  
          }
        }

    })
}

$('body').on('change','#province',function(e){
    let provinceID = parseInt($('#province').val());

    let promise = getCities(provinceID);

    fillCities(promise);
    
  });

function fillCities(promise, cityID = 0){
    promise.then(function (result){
      $('#city').empty();
      if (result.data.length > 0) {
        $('#city').append(`<option value>-- Please Select City --</option>`);
        result.data.map(function(item){
           $('#city').append(`<option value="${item.id}" ${ (cityID == item.id) ? `selected` : ``}>${item.name}</option>`);
        });
      }else{
        toastr.error(result.message);
      }
    });
}