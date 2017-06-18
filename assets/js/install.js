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
				$message = $('<p class="success">Successfully installed</p>');
				$message
					.delay(2000)
					.fadeOut(2000, function() {
						$(this).remove();
					});
				$('body').append($message);
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
					.fadeOut(2000, function() {
						$(this).remove();
					});
				$('body').append($message);
			}
		}
	});
}
