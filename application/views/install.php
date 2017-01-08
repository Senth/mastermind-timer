<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?><!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title>Install Database</title>
	<?php echo css_asset('style.css'); ?>
	<?php echo js_asset('jquery-3.1.1.min.js'); ?>
	<?php echo js_asset('install.js'); ?>
</head>
<body>
<h1>Install Database</h1>
<?php
if (!$installed)
	echo '<input id="install_button" type="button" value="Install" onClick="install()" />';
else
	echo 'Already installed'
?>
</body>
