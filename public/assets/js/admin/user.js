$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    },
});


$('#AddUserModal').on('hidden.bs.modal', function(event){
        $('#addEditUserForm')[0].reset();
});


/**
 * This block of code draws the user table
 */
$(document).ready(function () {
    var role = $('#role').val();
    $.fn.dataTable.ext.errMode = 'none';

    $('#usersTable').on( 'error.dt', function ( e, settings, techNote, message ) {
    console.log( 'An error has been reported by DataTables: ', message );
    } );
    
    usersList = $('#usersTable').DataTable({
        "searching": true,
        "processing": true,
        "serverSide": true,
        "responsive": true,
        language: {
            searchPlaceholder: "Search by user name"
        },
        "ajax":{
            "url" : `${APP_URL}/admin/users/${role}/list`,
            "type": "get",
            "data": function(d) {
            }
        },
        "columns": [{
                "data": null
            },
            {
                "data": "name",
            },
            {
                "data": "email",
            },
            {
                "data": "employee_id",
            },
            {
                "data": "phone",
            },
            { "data": "created_at" },
            {
                "render": function (data, type, row) {
                    return `<a href="javascript:void(0)" onclick=getUserInfo(${row.id}) id="${row.id}" data-toggle="tooltip" data-title='Edit' class="mr-1">
                           <i class="fas fa-edit"></i>
                      </a><a href="javascript:void(0)"  onclick=deleteUser(${row.id}) id="${row.id}" data-toggle="tooltip" data-title='Edit' class="mr-1">
                           <i class="fa fa-trash"></i>
                      </a>`;
                }
            }
        ],
        'columnDefs':[{
            'targets': [0,6],
            'orderable': false
        }],
        "order": [
            [2, "asc"]
        ],
        "fnRowCallback": function (nRow, aData, iDisplayIndex) {
            $("td:nth-child(1)", nRow).html(iDisplayIndex + 1);
            return nRow;
        },

        
    })
});

/**
 * Create and update user
 */
$('#addEditUserForm').submit(function (event) {
    event.preventDefault();
    let path = APP_URL + '/admin/users/create';

    let userName = $.trim($('#userName').val());
    let userEmail = $.trim($('#userEmail').val());
    let userEmpNo = $.trim($('#userEmpNo').val());
    let userPassword = $.trim($('#userPassword').val());
    let userMobile = $.trim($('#userMobile').val());

    if ((userName.length < 3) || (userName.length > 30)) {
        toastr.error('Name is required and should be between 3 to 30 characters.');
        return false;
    }

    if (!validateEmail(userEmail)) {
        toastr.error('User email should be a email.');
        return false;
    }

    if (!validateEmpNo(userEmpNo)) {
        toastr.error('Employee No. is required and spaces and special characters are not allowed.');
        return false;
    }

    if ($.trim($('#userId').val())) {
        /**
         * if edit request then change its method
         */
        path = APP_URL + '/admin/users/update';
    }

    if (userPassword == '') {
        if (!validateEmpNo(userPassword)) {
            if ((userPassword.length < 6) || (userPassword.length > 15)) {
                toastr.error('Password should be between 6 to 15 numbers.');
                return false;
            }
            toastr.error('Spaces and special characters are not allowed for password.');
            return false;
        }
    }

    if (userMobile) {
        if ((userMobile.length < 1) || (userMobile.length > 15)){
            toastr.error('mobile number should be between 1 to 15 numbers.');
            return false;
        }
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
               
                toastr.success(response.message);
                let datatable = $('#usersTable').DataTable();
                datatable.draw();
                $('#AddUserModal').modal('hide');
                
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
 * @param userId
 */
function getUserInfo(userId) {
    //$('#resetBtnadd').click();
    $.ajax({
        type: "GET",
        url: APP_URL + '/admin/users/getinfo/' + userId,
        
        success: function (result) {
            $('#userId').val(result.data.id);
            $("#userName").val(result.data.name);
            $('#userEmail').val(result.data.email);
            $('#userEmpNo').val(result.data.employee_id);
            $('#userMobile').val(result.data.phone);
            
            $('#AddUserModal').modal('show');
        },
        error: function () {
            toastr.error("Please try again later.");
        }
    });
}


/**
 * This function deletes the user
 * @param userId
 * 
 */
function deleteUser(userId) {
    var deleteUsertoast = toastr.warning("<br /><button type='button' class='btn btn-sm btn-primary' value='yes'>Yes</button><button type='button' class='btn btn-sm btn-danger'  value='no' >No</button>",'Are you sure you want to delete this user?',
    {
        "timeOut": 0,
        "extendedTimeOut": 0,
        onclick: function (toast) {

        deleteUsertoast.hide();
          value = toast.target.value
          if (value == 'yes') {
            $.ajax({
                    type: 'DELETE',
                    url: APP_URL + '/admin/users/delete/' + userId,
                    
                    success: function (response) {
                        if (response.success != true) {
                            toastr.error(response.message);
                        } else {
                            toastr.success(response.message);
                            
                            let datatable = $('#usersTable').DataTable();
                            datatable.draw();
                           
                        }
                        //usersList.reload();
                    },
                    error: function (response) {
                        toastr.error(response.responseJSON.errors.user_id[0]);
                    }
                }); 

            return true; 
          }
        }

    })
}

function validateEmail(email) {
    const re = /^(([^<>()[\]\\.,;:\s@"]+(\.[^<>()[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
    return re.test(String(email).toLowerCase());
}

function validateEmpNo(string) {
  return /^[ A-Za-z0-9]*$/.test(string);
}
