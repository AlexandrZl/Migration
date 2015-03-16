<?php

	include_once('autoload/autoload.php');
	global $PDO;

	$parameters = $argv;
	array_shift($parameters);

	App::init($parameters, $PDO);

?>