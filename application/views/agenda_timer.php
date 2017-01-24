<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<h1>Mastermind Agenda Timer</h1>
<p><a class="waves-effect waves-light btn" onClick="start_agenda()">Start</a></p>
<div id="messages"></div>
<div class="table" id="mastermind_table"></div>
<div id="extra_time_frame">
<label>Total Elapsed Time</label><span id="elapsed_time"></span>
<label>Extra Time</label><span id="extra_time"></span>
<label>Next Item</label>
<div class="action_container">
<a id="skip" class="waves-effect waves-light btn" onClick="nextItem()"><i class="material-icons">fast_forward</i></a>
</div>
</div>
