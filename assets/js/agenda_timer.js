function update() {
	var $formData = {}

	var formData = {
	ajax: true
	}

	$.ajax({
		url: 'agenda/get_agenda',
		type: 'POST',
		data: formData,
		dataType: 'json',
		success: function(json) {
			if (json.success) {
				$('#mastermind_table').children().remove();

				elapsed_seconds = json.elapsed_seconds;
				agenda_items = json.agenda_items;

				for (i = 0; i < agenda_items.length; ++i) {
					
				}		
			}
		}
	});
	
	setTimeout('update()', 1000);
}


$(document).ready(function() {
	update();
});
