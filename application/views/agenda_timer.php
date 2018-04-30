<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<h1>Mastermind Agenda Timer</h1>
<p id="participant_order"></p>
<p>
<a class="waves-effect waves-light btn" target="_blank" rel="noopener noreferrer" href="https://appear.in/thevegastriangle">Video Call</a>
<a class="waves-effect waves-light btn" target="_blank" rel="noopener noreferrer" href="https://docs.google.com/document/d/1jIIbhS2WJPpv6apkbUr21GvIhd5onSjwjVWp_PhyHx0/edit?usp=sharing">Guidelines Doc</a>
<a class="waves-effect waves-light btn" target="_blank" rel="noopener noreferrer" href="https://docs.google.com/spreadsheets/d/1saeeHV5i5QVfL-8Jq8HyjT10llobxmcBAYLA1uZ2z-Y/edit#gid=0">My One Thing</a>
<a class="waves-effect waves-light btn" target="_blank" rel="noopener noreferrer" href="https://docs.google.com/spreadsheets/d/15kjMjo-TnQs1IJEH92hvG4eAjZ5YQ4Mz4jTt7JF3fis/edit?usp=sharing">Shared Resources</a>
</p>
<p><a class="waves-effect waves-light green btn" onClick="startAgenda()">Start</a></p>
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
<p class="attribution">
Sound attribution:
<a target="_blank" href="https://freesound.org/people/Fratz/sounds/239967/">half-time</a>,
<a target="_blank" href="https://freesound.org/people/Fratz/sounds/239966/">almost up</a>,
<a target="_blank" href="https://freesound.org/people/psynaptix/sounds/160165/">times up</a>,
<a target="_blank" href="https://freesound.org/people/psynaptix/sounds/160165/">overdue</a>.
<p>
