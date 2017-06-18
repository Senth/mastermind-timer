<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<h1>Mastermind Agenda Timer</h1>
<p id="participant_order"></p>
<p><a class="waves-effect waves-light btn" onClick="start_agenda()">Start</a>
<a class="waves-effect waves-light btn" target="_blank" rel="noopener noreferrer" href="https://hangouts.google.com/hangouts/_/6s35zznzwvfj5hn4kdx3ulciwue">Google Hangout</a>
</p>
<div id="messages"></div>
<div class="table" id="mastermind_table"></div>
<div id="extra_time_frame">
<label>Elapsed Time</label><span id="elapsed_time"></span>
<label>Time Left</label><span id="total_time_left"></span>
<label>Extra Time</label><span id="extra_time"></span>
<label>Next Item</label>
<div class="action_container">
<a id="skip" class="waves-effect waves-light btn" onClick="nextItem()"><i class="material-icons">fast_forward</i></a>
</div>
</div>
