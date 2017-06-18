function fetch_all() {
	$.ajax({
		url: 'agenda/get_agenda',
		type: 'GET',
		dataType: 'json',
		success: function(json) {
			if (json.success) {
				$('#mastermind_table').children().remove();
				populateTable(json.items, json.participants);
				update();
				updateParticipantOrder();
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
	$('#mastermind_table').children().each(function(i) {
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
	let time_left = total_time_default - elapsed_time;
	updateTimeBox(elapsed_time, time_left, extra_time);
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
			$time_left.removeClass('red900');
			return item.time;
		} else {
			$time_left.html(time_to_string(time_left));
			$time_left.addClass('red900');
			$time_left.removeClass('green900');
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

function populateTable(agenda_items, participants) {
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

		// Editable - Increase
		if (agenda_item.is_time_editable == 1) {
			row += '<p><a id="time_up" class="waves-effect waves-light btn"><i class="material-icons">expand_less</i></a></p>';
		}

		row +=
			'<div id="time"></div>';

		// Editable - Decrease
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
			let participant = participants[agenda_item.participant_id];

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
			$row.data('participant_id', participant.id);

			if (participant.active == 0) {
				$row.hide();
			}
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

function updateTimeBox(elapsed_time, time_left, extra_time) {
	$('#elapsed_time').html(time_to_string(elapsed_time, true));

	// Time left
	$time_left = $('#total_time_left')
	if ($time_left < 0) {
		$time_left.addClass('red900');
	} else {
		$time_left.removeClass('red900');
	}
	if (time_left > -3600) {
		$time_left.html(time_to_string(time_left));
	} else {
		$time_left.html('0:00');
	}

	// Extra time
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
		url: 'participant/update_participant_time',
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

function updateParticipantOrder() {
	$.ajax({
		url: 'participant/get_participant_order',
		type: 'GET',
		dataType: 'json',
		success: function(json) {
			if (json === null || json.success === undefined) {
				addMessage('Return message is null, contact administrator', 'error');
				return;
			}

			if (json.success) {
				let participants = json.participants;
				let $participant_order = $('#participant_order');
				$participant_order.html('');

				for (i = 0; i < participants.length; ++i) {
					let participant = participants[i];
					color = 'blue active'
					if (participant.active == 0) {
						color = 'red inactive';
					}
					let $participant_button = $('<a class="participant_button waves-effect waves-light btn ' + color + '">' + participant.name + '</a>');
					$participant_button.data('participant_id', participant.id);
					$participant_button.click(function() {
						changeParticipantState($(this));
					});
					$participant_order.append($participant_button);
				}
			}
		}
	});

	setTimeout('updateParticipantOrder()', 30000);
}

function setParticipantOrder() {
	
}

function changeParticipantState($button) {
	var formData = {
		is_active: !$button.hasClass('active'),
		participant_id: $button.data('participant_id')
	}

	$.ajax({
		url: 'participant/set_participant_state',
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

	if (!formData.is_active) {
		$button.removeClass('active');
		$button.removeClass('blue');
		$button.addClass('inactive');
		$button.addClass('red');
	} else {
		$button.removeClass('inactive');
		$button.removeClass('red');
		$button.addClass('active');
		$button.addClass('blue');
	}
	hideShowParticipantRows();
}

function hideShowParticipantRows() {
	let participants = [];
	$('#participant_order').children().each(function() {
		let participant_id = $(this).data('participant_id');
		let is_active = $(this).hasClass('active');
		participants[participant_id] = is_active;
	});

	$('#mastermind_table').children().each(function() {
		$row = $(this);
		
		participant_id = $row.data('participant_id');
		if (participant_id !== undefined && participant_id >= 1) {
			if (participants[participant_id]) {
				$row.show();
			} else {
				$row.hide();
			}
		}
	});
}

$(document).ready(function() {
	fetch_all();
});
