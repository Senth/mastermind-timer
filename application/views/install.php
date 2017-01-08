<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<h1>Install Database</h1>
<p>
<?php
if (!$installed)
	echo '<input id="install_button" type="button" value="Install" onClick="install()" />';
else
	echo 'Already installed'
?>
</p>
<p><a class="waves-effect waves-light btn light-blue accent-3" onClick="resetItems()">Reset Agenda Items</a></p>
