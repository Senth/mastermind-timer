function fetch_all() {
	$.ajax({
		url: 'agenda/get_agenda',
		type: 'GET',
		dataType: 'json',
		success: function(json) {
			if (json.success) {
				$('#mastermind_table').children().remove();
				populateTable(json.items, json.participant_times);
				update();
			}
		}
	});
}

function update() {
	$.ajax({
		url:  'agenda/get_times',
		type: 'GET',
		dataType: 'json',
		success: function(json) {
			if (json.success) {
				updateTimes(json.items, json.elapsed_time);
			}
		}
	});

	setTimeout('update()', 100);
}

function nextItem() {
	$.ajax({
		url: 'agenda/next_item',
		type: 'POST',
		success: function(json) {
			if (json.success) {
				// Does nothing
			}
		}
	});
}

function updateTimes(items, elapsed_time) {
	let total_time = 0;
	let total_time_default = 0;

	// Update table times
	$rows = $('#mastermind_table').children().each(function(i) {
		let item = items[i];
		item.time = parseInt(item.time);

		$(this).find('#start_time').html(time_to_string(total_time, true));
		$(this).find('#time').html(time_to_string(item.time));

		// Update time left
		time_used = updateTimeLeft($(this), item, elapsed_time);

		if (item.time_default != undefined) {
			total_time_default += parseInt(item.time_default);
		} else {
			total_time_default += item.time;
		}
		total_time += time_used;
	});

	// Update time box
	let extra_time = total_time_default - total_time;
	updateTimeBox(elapsed_time, extra_time);
}

function updateTimeLeft($row, item, elapsed_time) {
	$time_left = $row.find('#time_left');
	
	// Item done
	if (item.start_time != undefined && item.end_time != null) {
		$row.addClass('passed_item');
		$rowBackground = $row.find('#row_background');
		$rowBackground.removeClass();
		$rowBackground.css('width', '100%');

		let time_used = item.end_time - item.start_time;
		let time_left = item.time - time_used;

		if (time_left >= 0) {
			$time_left.html('+' + time_to_string(time_left));
			$time_left.addClass('green900');
			$time_left.removeClass('red900');
		} else {
			$time_left.html(time_to_string(time_left));
			$time_left.addClass('red900');
			$time_left.removeClass('green900');
		}

		return time_used;
	}
	// In progress
	else if (item.start_time != undefined) {
		$row.removeClass('passed_item');
		
		let time_used = elapsed_time - item.start_time;
		let time_left = item.time - time_used;
		setColorTimeLeft($row, item.time, time_left);
		
		if (time_left >= 0) {
			$time_left.html('+' + time_to_string(time_left));
			$time_left.addClass('green900');
			return item.time;
		} else {
			$time_left.html(time_to_string(time_left));
			$time_left.addClass('red900');
			return time_used;
		}
	} else {
		// Clear any classes
		$rowBackground = $row.find('#row_background');
		$rowBackground.removeClass();
		$row.removeClass('passed_item');
		$time_left.html('');
		return item.time;
	}
}

function populateTable(agenda_items, participant_times) {
	let total_time = 0;

	elapsed_seconds = 30;

	$table = $('#mastermind_table');
	for (i = 0; i < agenda_items.length; ++i) {
		agenda_item = agenda_items[i];
		agenda_item.time = parseInt(agenda_item.time);
		
		row = '<div class="row">' +
			'<div id="row_background"></div>' +
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
			'<div id="time"></div>';

		if (agenda_item.is_time_editable == 1) {
			row += '<p><a id="time_down" class="waves-effect waves-light btn"><i class="material-icons">expand_more</i></a></p>';
		}

		row +=
			'</div>' +
			'<div class="cell" id="time_left"></div>' +
			'</div>' +
			'</div>';
		
		
		$row = $(row)

		// Has participant
		if (agenda_item.participant_id !== undefined) {
			let participant = participant_times[agenda_item.participant_id];
			
			// Add buttons
			if (agenda_item.is_time_editable == 1) {
				// Add onClick
				$row.find('#time_up').click(function() {
					increase_participant_time(participant.id)});
				$row.find('#time_down').click(function() {
					decrease_participant_time(participant.id)
				});
			}

			$row.find('#participant').html(participant.name);
		}

		$table.append($row);
	}
}

function time_to_string(time, pad_minutes=false) {
	let negate = time < 0;
	if (negate) {
		time *= -1;
	}
	let minutes = Math.floor(time / 60);
	let seconds = time - minutes * 60;
	
	if (pad_minutes) {
		minutes = str_pad_left(minutes, '0', 2);
	}
	let formatted_time = '';
	if (negate) {
		formatted_time = '-';
	}
	formatted_time += minutes + ':' + str_pad_left(seconds, '0', 2);
	return formatted_time;
}

function updateTimeBox(elapsed_time, extra_time) {
	$('#elapsed_time').html(time_to_string(elapsed_time, true));
	$extra_time = $('#extra_time')

	let prefix = '';
	if (extra_time < 0) {
		$extra_time.removeClass('green900');
		$extra_time.addClass('red900');
	} else {
		$extra_time.removeClass('red900');
		$extra_time.addClass('green900');
		prefix = '+';
	}

	$extra_time.html(prefix + time_to_string(extra_time));
}

function setColorTimeLeft($row, time, time_left) {
	let redTimeThreshold = 0;
	let orangeTimeThreshold = 0;

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

	
	let percentTimeLeft = time_left / time * 100;
	if (percentTimeLeft < 0) {
		percentTimeLeft = 0;
	}
	let backgroundWidth = 100 - percentTimeLeft + "%";
	$rowBackground = $row.find('#row_background');
	$rowBackground.removeClass();
	
	// Overdue
	if (time_left < 0) {
		$rowBackground.addClass('overdue');
	}
	// Red
	else if (time_left < redTimeThreshold) {
		$rowBackground.addClass('red100');
	}
	// Orange
	else if (time_left < orangeTimeThreshold) {
		$rowBackground.addClass('orange100');
	// Green
	} else {
		$rowBackground.addClass('green100');
	}

	$rowBackground.css({
		width: backgroundWidth,
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
	let formData = {
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
	fetch_all();
});
