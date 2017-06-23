 <?php
defined('BASEPATH') OR exit('No direct script access allowed');
?><!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title><?php echo $title; ?></title>
	<?php echo js_asset('jquery-3.1.1.min.js'); ?>
	<?php echo js_asset('jquery-ui.min.js'); ?>
	<?php echo js_asset($js_asset); ?>
	<link href="https://fonts.googleapis.com/css?family=Roboto+Condensed|Roboto+Slab" rel="stylesheet">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/0.97.8/css/materialize.min.css">
	<script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/0.97.8/js/materialize.min.js"></script>
	<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
	<?php echo css_asset('style.css'); ?>
	<?php echo css_asset('jquery-ui.min.css'); ?>
</head>
<body>
