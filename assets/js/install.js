function install() {
	var formData = {
		ajax: true
	}

	$.ajax({
		url: 'install/install',
		type: 'POST',
		data: formData,
		dataType: 'json',
		success: function(json) {
			if (json === null || json.success === undefined) {
				addMessage('Return message is null, contact administrator', 'error');
				return;
			}

			if (json.success) {
				$('#install_button').hide();
				$('body').append('<p class="success">Successfully installed</p>');
			}
		}
	});
}
