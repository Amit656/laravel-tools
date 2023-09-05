function getCities(provinceID) {
	return $.ajax({
		type: "POST",
		url: APP_URL + "/get-cities",
		data:{'id':provinceID, "_token": $('meta[name="csrf-token"]').attr('content')}
	});
}

function getSites(cityID) {
	return $.ajax({
		type: "POST",
		url: APP_URL + "/get-sites",
		data:{'id':cityID, "_token": $('meta[name="csrf-token"]').attr('content')}
	});
}

function getTools(modalityID, search = '') {
	let returnData = null;
	return $.ajax({
		type: "POST",
		url: APP_URL + "/get-tools-by-modality",
		data:{'id':modalityID, 'search':search, "_token": $('meta[name="csrf-token"]').attr('content')}
	});
}
