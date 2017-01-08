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
				populateTable(json.items, json.elapsed_seconds);
			}
		}
	});
	
// 	setTimeout('update()', 1000);
}

function populateTable(agenda_items, elapsed_seconds) {
	participants = ['Sri', 'Matteus', 'Kevin', 'Alli', 'Jim'];

	elapsed_seconds = 700;
	var agenda_time = 0;

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
			'<div class="cell" id="time">' + time_str + '</div>' +
			'<div class="cell" id="time_left"></div>' +
			'<div class="cell" id="cursor"></div>' +
			'</div>' +
			'</div>';
		
		if (agenda_item.is_all_participants == 1) {
			for (participantIndex = 0; participantIndex < participants.length; ++participantIndex) {
				$row = $(row)
				setRowInformation($row, agenda_item, agenda_time, elapsed_seconds);
				$row.find('#participant').html(participants[participantIndex]);
				$table.append($row);
				agenda_time += agenda_item.time;
			}
		} else {
			$row = $(row)
			setRowInformation($row, agenda_item, agenda_time, elapsed_seconds);
			$table.append($row);
			agenda_time += agenda_item.time;
		}
	}	
}

function setRowInformation($row, agenda_item, start_time, elapsed_seconds) {
	end_time = start_time + agenda_item.time;

	// Start time
	start_minutes = Math.floor(start_time / 60);
	start_seconds = start_time - start_minutes * 60;
	start_time_str = str_pad_left(start_minutes, '0', 2) + ':' + str_pad_left(start_seconds, '0', 2);
	$row.find('#start_time').html(start_time_str);

	// In progress
	if (start_time <= elapsed_seconds && elapsed_seconds < end_time) {
		$row.addClass('current_item');

		// Time left
		time_left = end_time - elapsed_seconds;
		minutes = 0;
		if (time_left > 60) {
			minutes = Math.floor(time_left / 60);
		}
		seconds = time_left - minutes * 60;
		$row.find('#time_left').html(minutes + ':' + str_pad_left(seconds, '0', 2));

		// Color time left
		setColorTimeLeft($row, agenda_item.time, time_left);
		
		// Add cursor <---
		$row.find('#cursor').html('<—————');
	}
	// Passed
	else if (end_time < elapsed_seconds) {
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

	
	var percentTimeLeft = Math.round(time_left / time * 100);
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

$(document).ready(function() {
	update();
});
