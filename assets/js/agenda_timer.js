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
				populateTable(json.items, json.participant_times, json.seconds_elapsed);
			}
		}
	});
	
	setTimeout('update(true)', 500);
}

function populateTable(agenda_items, participant_times, elapsed_seconds) {
	var total_time = 0;

	$table = $('#mastermind_table');
	for (i = 0; i < agenda_items.length; ++i) {
		agenda_item = agenda_items[i];
		agenda_item.time = parseInt(agenda_item.time);
		
		time_minutes = Math.floor(agenda_item.time / 60);
		time_seconds = agenda_item.time - time_minutes * 60;
		time_str = time_minutes + ':' + str_pad_left(time_seconds, '0', 2);

		row = '<div class="row">' +
			'<div class="row_background"></div>' +
			'<div class="row_content">' +
			'<div class="cell" id="start_time"></div>' +
			'<div class="cell" id="description">' + agenda_item.description + '</div>' +
			'<div class="cell" id="participant"></div>' +
			'<div class="cell" id="time_outer">';

		// Editable
		if (agenda_item.is_time_editable == 1) {
			row += '<p><a id="time_up" class="waves-effect waves-light btn"><i class="material-icons">expand_less</i></a></p>';
		}

		row +=
			'<div id="time">' + time_str + '</div>';

		if (agenda_item.is_time_editable == 1) {
			row += '<p><a id="time_down" class="waves-effect waves-light btn"><i class="material-icons">expand_more</i></a></p>';
		}

		row +=
			'</div>' +
			'<div class="cell" id="time_left"></div>' +
			'<div class="cell" id="cursor"></div>' +
			'</div>' +
			'</div>';
		
		if (agenda_item.is_all_participants == 1) {
			for (let participantIndex = 0; participantIndex < participant_times.length; ++participantIndex) {
				$row = $(row)
				let item_time = agenda_item.time;

				// Use participant time
				if (agenda_item.is_time_editable == 1) {
					item_time = parseInt(participant_times[participantIndex].time);
					minutes = Math.floor(item_time / 60);
					seconds = item_time - minutes * 60;
					$row.find('#time').html(minutes + ':' + str_pad_left(seconds, '0', 2));

					// Add onClick
					let participant_id = participant_times[participantIndex].id;
					$row.find('#time_up').click(function() {
						increase_participant_time(participant_id)});
					$row.find('#time_down').click(function() {
						decrease_participant_time(participant_id)
					});
				}

				setRowInformation($row, item_time, total_time, elapsed_seconds);
				$row.find('#participant').html(participant_times[participantIndex].name);
				$table.append($row);
				total_time += item_time;
			}
		} else {
			$row = $(row)
			var item_time = agenda_item.time;

			// Use extra time
			if (agenda_item.is_time_extra == 1) {
				item_time = 540 * 5;
				for (participantIndex = 0; participantIndex < participant_times.length; ++participantIndex) {
					item_time -= parseInt(participant_times[participantIndex].time);
				}
				minutes = Math.floor(item_time / 60);
				seconds = item_time - minutes * 60;
				$row.find('#time').html(minutes + ':' + str_pad_left(seconds, '0', 2));
			}

			setRowInformation($row, item_time, total_time, elapsed_seconds);
			$table.append($row);
			total_time += item_time;
		}
	}	
}

function setRowInformation($row, item_time, start_time, elapsed_seconds) {
	var end_time = start_time + item_time;

	// Start time
	var start_minutes = Math.floor(start_time / 60);
	var start_seconds = start_time - start_minutes * 60;
	var start_time_str = str_pad_left(start_minutes, '0', 2) + ':' + str_pad_left(start_seconds, '0', 2);
	$row.find('#start_time').html(start_time_str);

	// In progress
	if (start_time <= elapsed_seconds && elapsed_seconds < end_time) {
		$row.addClass('current_item');

		// Time left
		time_left = end_time - elapsed_seconds;
		minutes = Math.floor(time_left / 60);
		seconds = time_left - minutes * 60;
		$row.find('#time_left').html(minutes + ':' + str_pad_left(seconds, '0', 2));

		// Color time left
		setColorTimeLeft($row, item_time, time_left);
		
		// Add cursor <---
		$row.find('#cursor').html('<—————');
	}
	// Passed
	else if (end_time <= elapsed_seconds) {
		$row.addClass('passed_item');
	}
	// Incoming
	else {
		$row.addClass('incoming_item');
	}
}

function setColorTimeLeft($row, time, time_left) {
	var redTimeThreshold = 0;
	var orangeTimeThreshold = 0;

	// At least 3 minutes
	if (time >= 180) {
		redTimeThreshold = 60;
		orangeTimeThreshold = 120;
	}
	// At least 2 minutes
	else if (time >= 120) {
		redTimeThreshold = 30;
		orangeTimeThreshold = 60;
	}
	// At least 1 minute
	else if (time >= 60) {
		redTimeThreshold = 15;
		orangeTimeThreshold = 30;
	}
	// At least 30 seconds
	else if (time >= 30) {
		redTimeThreshold = 10;
		orangeTimeThreshold = 20;
	}
	// Less than 30 seconds
	else {
		redTimeThreshold = 5;
		orangeTimeThreshold = 10;
	}

	
	var percentTimeLeft = time_left / time * 100;
	var backgroundWidth = 100 - percentTimeLeft + "%";
	var color = '#E8F5E9'; // Green

	// Red
	if (time_left < redTimeThreshold) {
		color = '#FFEBEE';		
	}
	// Orange
	else if (time_left < orangeTimeThreshold) {
		color = '#FFF3E0';
	}

	$row.find('.row_background').css({
		width: backgroundWidth,
		'background-color': color
	});
}

function str_pad_left(string,pad,length) {
    return (new Array(length+1).join(pad)+string).slice(-length);
}

function start_agenda() {
	$.ajax({
		url: 'agenda/start',
		type: 'POST',
		data: null,
		dataType: 'json',
		success: function(json) {
			if (json === null || json.success === undefined) {
				addMessage('Return message is null, contact administrator', 'error');
				return;
			}

			if (json.success) {
				$message = $('<p class="success">Timer started!</p>');
				$message
					.delay(2000)
					.fadeOut(2000, function() {
						$(this).remove();
					});
				$('#messages').append($message);
			}
		}
	});
}

function increase_participant_time(participant_id) {
	update_participant_time(participant_id, 60);
}

function decrease_participant_time(participant_id) {
	update_participant_time(participant_id, -60);
}

function update_participant_time(participant_id, diff_time) {
	var formData = {
		participant_id: participant_id,
		diff_time: diff_time
	}

	$.ajax({
		url: 'agenda/update_participant_time',
		type: 'POST',
		data: formData,
		dataType: 'json',
		success: function(json) {
			if (json === null || json.success === undefined) {
				addMessage('Return message is null, contact administrator', 'error');
				return;
			}

			if (json.success) {
			}
		}
	});

}

$(document).ready(function() {
	update();
});
