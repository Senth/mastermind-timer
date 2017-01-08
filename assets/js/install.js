function install() {
	var formData = null;

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

function resetItems() {
	var formData = null;

	$.ajax({
		url: 'install/reset_items',
		type: 'POST',
		data: formData,
		dataType: 'json',
		success: function(json) {
			if (json === null || json.success === undefined) {
				addMessage('Return message is null, contact administrator', 'error');
				return;
			}

			if (json.success) {
				$message = $('<p class="success">Reset successful!</p>');
				$message
					.delay(2000)
					.fadeTo(2000, 0)
					.remove();
				$('body').append($message);
			}
		}
	});
}