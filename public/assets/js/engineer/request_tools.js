$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    },
});

$('body').on('click','.checkAll',function(e){
	if ($(this).is(':checked')) {
	    $('.requested-tool').prop('checked', true);
	} else {
	    $('.requested-tool').prop('checked', false);
	}
});

$('body').on('change','#modality',function(e){
    let modalityID = parseInt($('#modality').val());

    let promise = getTools(modalityID);
    promise.then(function (result){
    	$("#toolList").html('');

    	// console.log(result[0].id);
    	// console.log(result[0].notifyTools.includes(result[0].id));
    	// return true;
    	if (result.length > 0) {
			result.map(function(item){
				var desc_html = '';

				if(item.toolColor == "green"){
					desc_html = `<td class="text-wrap text-center border-left-green">${item.description ? item.description : 'N/A'}</td>`;
				}else if(item.toolColor == "red"){
					desc_html = `<td class="text-wrap text-center border-left-red">${item.description ? item.description : 'N/A'}</td>`;
				}else{
					desc_html = `<td class="text-wrap text-center border-left-yellow">${item.description ? item.description : 'N/A'}</td>`;
				}

				$('#toolList').append(`<tr>
				${desc_html}
				<td class="text-wrap text-center">${item.product_no}</td>
				<td class="text-wrap text-center">${item.serial_no}</td>
				<td class="text-wrap text-center">${item.owner}</td>
				<td class="text-wrap text-center">${item.calibration_date ? item.calibration_date : 'N/A'}</td>
				<td class="text-wrap text-center">${item.site_address}</td>

				${(item.status == 'busy') ? 
				`<td class="text-wrap text-center text-danger">${item.statusMSG}<br/><input type="checkbox" id="${item.id}" name="notify[]" data-on-text="Notify"
       			data-off-text="No" data-bootstrap-switch data-off-color="danger" data-on-color="success" value="${item.id}" class="notify notify-availability"></td>` : 
       			`<td class="text-wrap text-center"><input class="requested-tool" type="checkbox" name="requestedTool[]" value="${item.id}"></td>`}
       			
				
				</tr>`);
				let status = item.notifyTools.includes(item.id.toString());

				$('.notify').bootstrapSwitch('state', status);
			});
    	}else{
    		$("#toolList").html('<td colspan="5">No Tools Found<td>');
    	}
    });
});

$('body').on('change','#province',function(e){
    let provinceID = parseInt($('#province').val());

    let promise = getCities(provinceID);
    promise.then(function (result){
    	$('#site').empty();
    	$('#city').empty();
    	if (result.data.length > 0) {
    		$('#site').append(`<option>-- Please Select Site --</option>`);
			$('#city').append(`<option>-- Please Select City --</option>`);
			result.data.map(function(item){
				 $('#city').append(`<option value="${item.id}">${item.name}</option>`);
			});
    	}else{
	        toastr.error(result.message);
	    }
    });
});

$('body').on('change','#city',function(e){
    let cityID = parseInt($('#city').val());

    let promise = getSites(cityID);
    promise.then(function (result){
    	$('#site').empty();
    	if (result.length > 0) {
    		$('#site').append(`<option>-- Please Select Site --</option>`);
			result.map(function(item){
				 $('#site').append(`<option value="${item.id}">${item.name}</option>`);
			});
    	}else{
	        toastr.error("No sites are found!");
	    }
    });
});

$('body').on('click','.select-tool',function(e){

	var toolIDs = new Array();

	$.each($("input[name='requestedTool[]']:checked"), function(index, item) {
	  toolIDs.push($(this).val());
	});

	$.ajax({
		type: "POST",
		url: APP_URL + "/get-tools-by-id",
		data:{'id':toolIDs, "_token": $('meta[name="csrf-token"]').attr('content')},
		success: function(data){

			$("#finalCheckData").html('');
	    	if (data.length > 0) {

	    		if (data.length > 15) {
	    			$('#finalCheckDataDiv').height('600px');
	    		}

	    		$('.submit').removeAttr('disabled');
				data.map(function(item){

					 $('#finalCheckData').append(`<tr>
					<td class="text-wrap text-center">${item.description ? item.description : 'N/A'}</td>
					<td class="text-wrap text-center">${item.product_no}</td>
					<td class="text-wrap text-center">${item.serial_no}</td>
					<td class="text-wrap text-center">${item.owner}</td>
					<td class="text-wrap text-center">${item.calibration_date ? item.calibration_date : 'N/A'}</td>
					<td class="text-wrap text-center">${item.site_address}</td>
					</tr>`);
				});
	    	}
		}
	});
});

$('body').on('click','#nextBtn1',function(e){
	$('#search-div').removeClass('d-none');
	let modality = $.trim($('#modality').val());
	let province = $.trim($('#province').val());
	let city = $.trim($('#city').val());
	let site = $.trim($('#site').val());
	let deliveryDateId = $('#deliveryDateId').val();
	let expectedReturnDateId = $('#expectedReturnDateId').val();
	
	if (!modality) {
        toastr.error('Please select modality.')
       
    }else if(!province){
		toastr.error('Please select province.')
	}else if(!city){
		toastr.error('Please select city.')
	}else if(!site){
		toastr.error('Please select site.')
	}else if(!deliveryDateId){
		toastr.error('Please select delivery date.')
	}else if(!expectedReturnDateId){
		toastr.error('Please select expected return date.')
	}else{
		stepper.next()
	}
}); 

$('body').on('click','#nextBtn2',function(e){
	if($('input[name="requestedTool[]"]:checked').length == 0){
		toastr.error('Please select Tools.')
	}else{
		$('#search-div').addClass('d-none');
		stepper.next()
	}
}); 

$('#toolRequestForm').submit(function (event) {
    event.preventDefault();

    $('.requestToolBtn').prop('disabled', true);
    const formData = new FormData(this);
    $.ajax({
        url: APP_URL + '/engineer/request-tools/store',
        type: 'POST',
        cache: false,
        contentType: false,
        processData: false,
        data: formData,
        success: function (response) {
            if (response.success != true) {
                toastr.error(response.message);
            } else {
				$('.requestToolBtn').prop('disabled', false);
                toastr.success(response.message)  

                setTimeout(function(){ window.location.href= APP_URL + '/engineer/history-request-tools' }, 2000); 
            }
        },
        
        error: function(xhr, status, error)
        {	
			$('.requestToolBtn').prop('disabled', false);
          $.each(xhr.responseJSON.errors, function (key, item)
          {
            toastr.error(item);
          });

        }
    });
});

$(".notify-availability").bootstrapSwitch({
  onSwitchChange: function(e, state) {

  	const formData = new FormData();

  	formData.append('state', state);
  	formData.append('id', $(this).val());

   $.ajax({
        url: APP_URL + '/engineer/request-tools/notify-availability/',
        type: 'POST',
        cache: false,
        contentType: false,
        processData: false,
        data:formData,
        success: function (response) {
            if (response.success != true) {
                toastr.error(response.message);
            } else {
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
  }
});

$("#toolRequestForm").on('input', '#search', function (e) {
	let modalityID = parseInt($('#modality').val());
    var search = $('#search').val();
	console.log('search', search);
    let promise = getTools(modalityID, search);
    promise.then(function (result){
    	$("#toolList").html('');

    	// console.log(result[0].id);
    	// console.log(result[0].notifyTools.includes(result[0].id));
    	// return true;
    	if (result.length > 0) {
			result.map(function(item){
				var desc_html = '';

				if(item.toolColor == "green"){
					desc_html = `<td class="text-wrap text-center border-left-green">${item.description ? item.description : 'N/A'}</td>`;
				}else if(item.toolColor == "red"){
					desc_html = `<td class="text-wrap text-center border-left-red">${item.description ? item.description : 'N/A'}</td>`;
				}else{
					desc_html = `<td class="text-wrap text-center border-left-yellow">${item.description ? item.description : 'N/A'}</td>`; 
				}

				$('#toolList').append(`<tr>
				${desc_html}
				<td class="text-wrap text-center">${item.product_no}</td>
				<td class="text-wrap text-center">${item.serial_no}</td>
				<td class="text-wrap text-center">${item.owner}</td> 
				<td class="text-wrap text-center">${item.calibration_date ? item.calibration_date : 'N/A'}</td>
				<td class="text-wrap text-center">${item.site_address}</td>

				${(item.status == 'busy') ? 
				`<td class="text-wrap text-center text-danger">${item.statusMSG}<br/><input type="checkbox" id="${item.id}" name="notify[]" data-on-text="Notify"
       			data-off-text="No" data-bootstrap-switch data-off-color="danger" data-on-color="success" value="${item.id}" class="notify notify-availability"></td>` : 
       			`<td class="text-wrap text-center"><input class="requested-tool" type="checkbox" name="requestedTool[]" value="${item.id}"></td>`}
       			
				
				</tr>`);
				let status = item.notifyTools.includes(item.id.toString());

				$('.notify').bootstrapSwitch('state', status);
			});
    	}else{
    		$("#toolList").html('<td colspan="6">No Tools Found<td>');
    	}
    });
});

$("#toolRequestForm").on('click', '#previousBtn1', function () {
	$('#search-div').addClass('d-none');
});

$("#toolRequestForm").on('click', '#previousBtn2', function () {
	$('#search-div').removeClass('d-none');
});

$('.pickBy').click(function(){
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
