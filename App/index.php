<?php

	include_once('autoload/autoload.php');
	global $PDO;

	$parameters = $argv;
	$invocationFile = array_shift($parameters);

	App::init($invocationFile, $parameters, $PDO);
?>