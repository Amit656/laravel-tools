$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    },
});

$('#changePasswordModal').on('hidden.bs.modal', function(event){
    $('#editPasswordForm')[0].reset();
});

$('#changeProfileImage').on('hidden.bs.modal', function(event){
    $('#editProfileImgForm')[0].reset();
});

$('document').ready(function () {
    getUserInfo();
});

$('#editProfileForm').submit(function (event) {
    event.preventDefault();

    const formData = new FormData(this);
    $.ajax({
        url: APP_URL + '/profile/update',
        type: 'POST',
        cache: false,
        contentType: false,
        processData: false,
        data: formData,
        success: function (response) {
            if (response.success != true) {
                toastr.error(response.message);
            } else {
                getUserInfo();
                toastr.success(response.message)
                         
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
 * 
 */
function getUserInfo() {
    $.ajax({
        type: "GET",
        url: APP_URL + '/profile/getInfo',
        success: function (result) {
            $('#userId').val(result.data.id);
            $("#userName").val(result.data.name);
            $("#userEmail").val(result.data.email);
            $('#EmployeeNumber').val(result.data.employee_id);
            $("#mobile").val(result.data.phone);
            $(".user-name").html(result.data.name);
            $(".user-email").html(result.data.email);
            let image = result.data.image_url ? result.data.image_url : APP_URL + `/assets/dist/img/user2-160x160.jpg`;
            $(".profile-img").attr("src", image); 
            $(".sidebar-image").attr("src", image); 
        },
        error: function () {
            toastr.error("Please try again later."); 
        }
    });
}

/**
 * This function update password
 * 
 */
$('#editPasswordForm').submit(function (event) {
    event.preventDefault();

    const formData = new FormData(this);
    $.ajax({
        url: APP_URL + '/change/password',
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
                $('#changePasswordModal').modal('hide');        
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
 * This function update profile image
 * 
 */
$('#editProfileImgForm').submit(function (event) {
    event.preventDefault();

    const formData = new FormData(this);
    $.ajax({
        url: APP_URL + '/change/image',
        type: 'POST',
        cache: false,
        contentType: false,
        processData: false,
        data: formData,
        success: function (response) {
            if (response.success != true) {
                toastr.error(response.message);
            } else {
                getUserInfo();
                toastr.success(response.message)
                $('#changeProfileImage').modal('hide');         
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


