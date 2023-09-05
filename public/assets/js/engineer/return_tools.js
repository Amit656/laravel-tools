$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    },
});

$('body').on('click','.checkAll',function(e){
	if ($(this).is(':checked')) {
	    $('.return-tool').prop('checked', true);
	} else {
	    $('.return-tool').prop('checked', false);
	}
});

$('body').on('click','.return-tools-next',function(e){
	var toolIDs = new Array();

	$.each($("input[name='returnTool[]']:checked"), function(index, item) {
	  toolIDs.push($(this).val());
	});

	$.ajax({
		type: "POST",
		url: APP_URL + "/engineer/get-requested-tools-by-id",
		data:{'id':toolIDs, "_token": $('meta[name="csrf-token"]').attr('content')},
		success: function(data){

			$("#finalCheckData").html('');
	    	if (data.length > 0) {

	    		if (data.length > 15) {
	    			$('#finalCheckDataDiv').height('600px');
	    		}

	    		$('.submit').removeAttr('disabled');
				data.map(function(item){
					var desc_html = '';

					if(item.toolColor == "green"){
						desc_html = `<td class="text-wrap text-center border-left-green">${item.tool_description ? item.tool_description : 'N/A'}</td>`;
					}else if(item.toolColor == "red"){
						desc_html = `<td class="text-wrap text-center border-left-red">${item.tool_description ? item.tool_description : 'N/A'}</td>`;
					}else{
						desc_html = `<td class="text-wrap text-center border-left-yellow">${item.tool_description ? item.tool_description : 'N/A'}</td>`;
					}

					 $('#finalCheckData').append(`<tr>
					 ${desc_html}
					<td class="text-wrap text-center">${item.tool_product_no}</td>
					<td class="text-wrap text-center">${item.tool_serial_no}</td>
					<td class="text-wrap text-center">
					<input class="form-check-input" id="good${item.id}" value="good" type="radio" name="condition[${item.id}]" checked>
					<label for="good${item.id}" class="form-check-label">Good</label><br>
					<input class="form-check-input" id="bad${item.id}" value="bad" type="radio" name="condition[${item.id}]">
					<label for="bad${item.id}" class="form-check-label">Bad&nbsp;&nbsp;&nbsp;</label>
					</td>
					<td class="text-wrap text-center">
					<textarea class="form-control" maxlength=100 id="comment${item.id}" name="comment[${item.id}]"></textarea>
					</td>
					</tr>`);
				});
	    	}else{
	    		$("#finalCheckData").html('<td colspan="5">No Tools Found<td>');
	    	}
		}
	});
});


$('body').on('click','#nextBtn',function(e){

	if($('input[name="returnTool[]"]:checked').length == 0){
		toastr.error('Please select Tools.')
	}else{
		$('#search-div').addClass('d-none');
		stepper.next()
	}
});

$('#returnToolForm').submit(function (event) {
    event.preventDefault();
    const formData = new FormData(this);
    $('.returnToolBtn').prop('disabled', true);

    $.ajax({
        url: APP_URL + '/engineer/return-tools/store',
        type: 'POST',
        cache: false,
        contentType: false,
        processData: false,
        data: formData,
        success: function (response) {
            if (response.success != true) {
                toastr.error(response.message);
				$('.returnToolBtn').prop('disabled', false);
            } else {
                toastr.success(response.message)

                setTimeout(function(){ window.location.href= APP_URL + '/engineer/history-return-tools' }, 2000); 
            }
        },
        
        error: function(xhr, status, error)
        {
		  $('.returnToolBtn').prop('disabled', false);
          $.each(xhr.responseJSON.errors, function (key, item)
          {
            toastr.error(item);
          });

        }
    });
});

$("#returnToolForm").on('input', '#search', function (e) {
	var toolIDs = new Array();
    var search = $('#search').val();
	console.log('search', search);
	$.ajax({
		type: "POST",
		url: APP_URL + "/engineer/get-requested-tools-by-id",
		data:{'id':toolIDs, 'search':search, "_token": $('meta[name="csrf-token"]').attr('content')},
		success: function(data){

			$("#tableData").html('');
	    	if (data.length > 0) {

				data.map(function(item){
					var desc_html = '';

					if(item.toolColor == "green"){
						desc_html = `<td class="text-wrap text-center border-left-green">${item.tool_description ? item.tool_description : 'N/A'}</td>`;
					}else if(item.toolColor == "red"){
						desc_html = `<td class="text-wrap text-center border-left-red">${item.tool_description ? item.tool_description : 'N/A'}</td>`;
					}else{
						desc_html = `<td class="text-wrap text-center border-left-yellow">${item.tool_description ? item.tool_description : 'N/A'}</td>`;
					}

					 $('#tableData').append(`<tr>
					 ${desc_html}
					<td class="text-wrap text-center">${item.tool_product_no}</td>
					<td class="text-wrap text-center">${item.tool_serial_no}</td>
					<td class="text-wrap text-center">${item.tool_calibration_date ? item.tool_calibration_date : 'N/A'}</td>
					<td class="text-wrap text-center">${item.expected_return_date}</td>
					<td class="text-center">
					<input class="return-tool" type="checkbox" name="returnTool[]" value="${item.id}">
					</td>
					</tr>`); 
				});
	    	}else{
	    		$("#tableData").html('<td colspan="5">No Tools Found<td>');
	    	}
		}
	});
});

$("#returnToolForm").on('click', '#previousbtn', function () {
	$('#search-div').removeClass('d-none');
});

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